<?php

session_start();
if (!isset($_SESSION['agent']['loggedin'])) {
    exit;
}

require_once "autoloader.php";

use Server\Model\Receipt;

/**
 * ~ Description: Read JSON data from the request body
 * ~ Retrieve JSON data from the request body
 */
$str_json = file_get_contents('php://input');
$data = json_decode($str_json, true);

if ($data['receipt_action'] === 'create') {
    /**
     * TITLE: Create Receipt
     * ~ Description: Create a new receipt
     * ~ Retrieve JSON data from the request body
     */
    $receipt = new Receipt();

    if ($data['dueDate'] == "" || $data['dueDate'] == null && $data['dueDate'] == 0 || $data['dueDate'] == "0000-00-00" || $data['dueDate'] == "1970-01-01" || $data['dueDate'] == "1969-12-31") {
        $data['dueDate'] = NULL;
    } else {
        $data['dueDate'] = date("Y-m-d", strtotime($data['dueDate']));
    }

    // Create a new receipt
    $receipt->create(
        $data['name'],
        $data['email'],
        $data['phone'],
        date("Y-m-d", strtotime($data['paymentDate'])),
        $data['dueDate'],
        json_encode($data['tableData']),
        $data['subtotal'],
        $data['discount'],
        $data['discountAmount'],
        $data['totalPayable'],
        $data['convenienceFee'],
        $data['advancePayment'],
        $data['duePayment'],
        $data['agent_id']
    );

    // Send the response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} else if ($data['receipt_action'] == 'delete') {
    /**
     * TITLE: Delete Receipt
     * ~ Description: Delete a receipt
     * ~ Retrieve JSON data from the request body
     */

    $receipt = new Receipt();
    $receipt->delete($data['receiptID']);

    // Send the response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} else if ($data['receipt_action'] == 'edit') {
    /**
     * TITLE: Edit Receipt
     * ~ Description: Edit a receipt
     * ~ Retrieve JSON data from the request body
     */
    if ($data['dueDate'] == "" || $data['dueDate'] == null && $data['dueDate'] == 0 || $data['dueDate'] == "0000-00-00" || $data['dueDate'] == "1970-01-01" || $data['dueDate'] == "1969-12-31") {
        $data['dueDate'] = NULL;
        $data['payment_status'] = "Paid";
    } else {
        $data['dueDate'] = date("Y-m-d", strtotime($data['dueDate']));
        if ($data['duePayment'] == 0) {
            $data['payment_status'] = "Paid";
        } else {
            $data['payment_status'] = "Partially Paid";
        }
    }

    $receipt = new Receipt();
    $receipt->update(
        $data['receiptID'],
        $data['name'],
        $data['email'],
        $data['phone'],
        date("Y-m-d", strtotime($data['paymentDate'])),
        $data['dueDate'],
        $data['payment_status'],
        json_encode($data['tableData']),
        $data['subtotal'],
        $data['discount'],
        $data['discountAmount'],
        $data['totalPayable'],
        $data['convenienceFee'],
        $data['advancePayment'],
        $data['duePayment'],
        $data['agent_id']
    );

    // Send the response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} else if ($data['receipt_action'] == 'get') {
    /**
     * TITLE: Get Receipt
     * ~ Description: Get a receipt
     * ~ Retrieve JSON data from the request body
     * ~ Return the receipt data
     * 
     * @param string $receipt_id
     * 
     * @return array $receipt
     */

    $receipt = new Receipt();
    $data = $receipt->get($data['receiptID']);

    // Send the response
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => array(
            'receipt_id' => $data['receipt_id'],
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'payment_date' => $data['payment_date'],
            'due_date' => $data['due_date'],
            'payment_status' => $data['payment_status'], // 'paid' or 'unpaid
            'item_list' => json_decode($data['item_list']),
            'subtotal' => $data['subtotal'],
            'discount_percentage' => $data['discount_percentage'],
            'discount_amount' => $data['discount_amount'],
            'payable' => $data['payable'],
            'convenience_fee' => $data['convenience_fee'],
            'advance_payment' => $data['advance_payment'],
            'due_payment' => $data['due_payment'],
            'agent_id' => $data['agent_id']
        )
    ]);
} else if ($data['receipt_action'] == 'getAll') {
    /**
     * TITLE: Get All Receipts
     * ~ Description: Get all receipts
     * ~ Retrieve JSON data from the request body
     * ~ Return the receipt data
     * 
     * @return array $receipt
     */

    $receipt = new Receipt();

    // Create a new database connection
    $all_receipts = $receipt->getAll();

    // Create an array to store all receipts
    $allData = array();

    foreach ($all_receipts as $row) {
        $data = array(
            'receipt_id' => $row['receipt_id'],
            'customer_name' => $row['customer_name'],
            'customer_email' => $row['customer_email'],
            'customer_phone' => $row['customer_phone'],
            'payment_date' => $row['payment_date'],
            'due_date' => $row['due_date'],
            'payment_status' => $row['payment_status'], // 'paid' or 'unpaid
            'item_list' => json_decode($row['item_list']),
            'subtotal' => $row['subtotal'],
            'discount_percentage' => $row['discount_percentage'],
            'discount_amount' => $row['discount_amount'],
            'payable' => $row['payable'],
            'convenience_fee' => $row['convenience_fee'],
            'advance_payment' => $row['advance_payment'],
            'due_payment' => $row['due_payment'],
            'agent_id' => $row['agent_id']
        );

        // Append the data for the current receipt to the array
        $allData[] = $data;
    }

    header('Content-Type: application/json');
    echo json_encode($allData);
    exit; // or die;
} else {
    /**
     * ! Error
     * ~ Description: Return an error
     */

    // Send the response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error']);
}
