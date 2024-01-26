<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print </title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Stylesheet -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- <link rel="stylesheet" href="../Assets/CSS/receipt.css"> -->

    <style>
        @media print {
            @page {
                size: 210mm 297mm;
                margin: 0;

                .mainCircleLogo {
                    position: fixed;
                    bottom: -170px;
                    right: -300px;
                    opacity: 20%;
                }


                .receiptTopPart {
                    width: 100%;
                    position: absolute;
                    top: 20px;
                    left: 20px;
                }
            }

            /* Ensures the background color and text color in the table headers are printed */
            .itemReceiptTable thead tr th {
                color: white !important;
                background-color: #1965af !important;
                -webkit-print-color-adjust: exact;
            }



        }

        .mainCircleLogo {
            position: fixed;
            bottom: -170px;
            right: -300px;
            opacity: 20%;
        }

        .mainCircleLogo img {
            width: 600px;
        }

        body {
            padding: 10px;
            overflow: hidden;
            height: 100%;
            width: 100%;
            font-size: 14px;
        }

        .receiptTopPart {
            padding-top: 20px;
            width: 100%;
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .receiptTopPart .receiptTopLeftPart {
            position: absolute;
            left: 20px;
            width: 50%;
        }

        .customerDetailsTable {
            width: 250px;
        }

        .customerDetailsTable tbody tr td:nth-child(2) {
            text-align: right;
        }

        .itemReceiptTable thead tr th {
            color: white;
            background-color: #000;
        }

        .receiptTopPart .receiptTopRightPart {
            position: absolute;
            top: 20px;
            right: 60px;
            background-color: #fff;
            width: 39%;
        }

        .receiptTopPart .receiptTopRightPart {
            text-align: right;
        }

        .receiptMiddlePart {
            background-color: transparent !important;
            width: 100%;
            position: absolute;
            top: 355px;
            left: 0;
            padding: 0px;
        }

        .itemReceiptTable {
            width: 100%;
            border-collapse: collapse;
        }

        .itemReceiptTable thead tr th {
            text-align: center;
            padding: 15px;
        }

        .itemReceiptTable tbody tr td {
            text-align: center;
        }

        .itemReceiptTable tbody tr td:nth-child(1) {
            width: 10%;
            max-width: 10%;
        }

        .itemReceiptTable tbody tr td:nth-child(2) {
            width: 30%;
            max-width: 30%;
        }

        .itemReceiptTable tbody tr td:nth-child(3) {
            width: 40%;
            max-width: 40%;
        }

        .itemReceiptTable tbody tr td:nth-child(4) {
            width: 20%;
            max-width: 20%;
        }

        .noborder {
            border: none;
        }

        .subtotalTable {
            min-width: fit-content;
            border-top: 1px solid gray;
            background-color: transparent;
        }

        .subtotalTable tbody tr td {
            background-color: transparent;
            font-weight: bold;
            border: none;
        }

        .subtotalTable tbody tr td:nth-child(1) {
            text-align: right;
        }

        #footerMenu {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            z-index: 999;
            border-top: 1px solid #ccc;
        }

        .disclaimer {
            padding-left: 30px;
            padding-right: 30px;
        }
    </style>
</head>

<body>

    <div class="mainCircleLogo">
        <img src="tripupappicon.png" loading="lazy" width="400px">
    </div>

    <div class="receiptTopPart">
        <div class="receiptTopLeftPart">
            <img src="tripupmainlogo.png" loading="lazy" width="200px" alt="TripUp Logo">

            <p style="font-weight: bold; margin-top: 10px; color: #1965af;">Address: <span id="dynamicAddress" style="font-weight: normal; color: black;">143,
                    Road 01, Avenue 01,
                    Mirpur DOHS.</span>
                <br>Contact: <span id="dynamicContact" style="color: black; font-weight: normal;">+880
                    1234 567 890</span>
            </p>
            <p style="margin-top: 20px; text-align: left;">
            <h3 style="font-weight: bold;">Customer Details</h3>
            <table class="table customerDetailsTable" style="width: 250px;">
                <tr class="border-0">
                    <td class="noborder" style="font-weight: bold;">Name:</td>
                    <td class="noborder" id="dynamicCustomerName" style="font-weight: normal;">Anisul</td>
                </tr>
                <tr class="border-0">
                    <td class="noborder" style="font-weight: bold;">Contact:
                    </td>
                    <td class="noborder" id="dynamicCustomerContact" style="font-weight: normal;">
                        +880 1234
                        567 890</td>
                </tr>
                <tr class="border-0">
                    <td class="noborder" style="font-weight: bold;">Email:</td>
                    <td class="noborder" id="dynamicCustomerEmail" style="font-weight: normal;">
                        demo@email.com</td>
                </tr>
            </table>
            </p>
        </div>
        <div class="receiptTopRightPart">
            <div style="text-align: right; margin-top: 30px;">
                <h3 style="font-size: 34px; font-weight: bold;">RECEIPT</h3>
                <h3 style="font-weight: 500;" id="DynamicReceiptNumber">070124-001
                </h3>
            </div>
            <div style="margin-top: 20px;" class="table-responsive-sm">
                <table class="table">
                    <tr>
                        <td style="font-weight: bold; border: none; text-align: right;">
                            Payment Date:
                        </td>
                        <td class="border-0" id="dynamicPaymentDate">07 January 2021</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; border: none; text-align: right;">
                            Payment Status:
                        </td>
                        <td class="border-0" id="dynamicPaymentMethod">Partially Paid (Bkash)</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; border: none; text-align: right;">
                            Due Date:</td>
                        <td class="border-0" id="dynamicDueDate">15 February 2024</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <div class="receiptMiddlePart">
        <table class="table itemReceiptTable">
            <thead class="">
                <tr>
                    <th style="max-width: 10%;">#</th>
                    <th style="max-width: 30%;">Item Name</th>
                    <th style="max-width: 40%;">Item Description</th>
                    <th style="max-width: 20%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-0" id="dynamicItemDescription">1</td>
                    <td class="border-0" id="dynamicItemQuantity">Hotel/Resort</td>
                    <td class="border-0" id="dynamicItemUnitPrice">Demo Description</td>
                    <td class="border-0" id="dynamicItemSubtotal"><span class="dynamicItemSubtotalAmount">5000</span> BDT</td>
                </tr>
                <tr>
                    <td class="border-0" id="dynamicItemDescription">2</td>
                    <td class="border-0" id="dynamicItemQuantity">Ship</td>
                    <td class="border-0" id="dynamicItemUnitPrice">Dwipantor Beach Resort
                        Premium Couple Cottage (Sea
                        View)
                        BDT 6450 per night</td>
                    <td class="border-0" id="dynamicItemSubtotal"><span class="dynamicItemSubtotalAmount">5000</span> BDT</td>
                </tr>


                <tr>
                    <td class="border-0" id="dynamicItemDescription">2</td>
                    <td class="border-0" id="dynamicItemQuantity">Ship</td>
                    <td class="border-0" id="dynamicItemUnitPrice">Dwipantor Beach Resort
                        Premium Couple Cottage (Sea
                        View)
                        BDT 6450 per night</td>
                    <td class="border-0" id="dynamicItemSubtotal"><span class="dynamicItemSubtotalAmount">5000</span> BDT</td>
                </tr>
                <tr>
                    <td class="border-0" id="dynamicItemDescription">2</td>
                    <td class="border-0" id="dynamicItemQuantity">Ship</td>
                    <td class="border-0" id="dynamicItemUnitPrice">Dwipantor Beach Resort
                        Premium Couple Cottage (Sea
                        View)
                        BDT 6450 per night</td>
                    <td class="border-0" id="dynamicItemSubtotal"><span class="dynamicItemSubtotalAmount">5000</span> BDT</td>
                </tr>
                <tr>
                    <td class="border-0" id="dynamicItemDescription">2</td>
                    <td class="border-0" id="dynamicItemQuantity">Ship</td>
                    <td class="border-0" id="dynamicItemUnitPrice">Dwipantor Beach Resort
                        Premium Couple Cottage (Sea
                        View)
                        BDT 6450 per night</td>
                    <td class="border-0" id="dynamicItemSubtotal"><span class="dynamicItemSubtotalAmount">5000</span> BDT</td>
                </tr>
            </tbody>
        </table>

        <div class="col-12 d-flex justify-content-end" style="background-color:transparent !important;">
            <div class="card border-0" style="background-color:transparent !important;">
                <div class="card-body" style="background-color:transparent !important;">
                    <table class="table subtotalTable" style="background-color:transparent !important;">
                        <tbody>
                            <tr>
                                <td>
                                    Subtotal</td>
                                <td><span class="dynamicSubtotalAmount" style="font-weight: normal;">: 5,000</span> BDT</td>
                            </tr>
                            <tr>
                                <td>
                                    Advance Paid</td>
                                <td><span class="dynamicAdvanceAmount" style="font-weight: normal;">: 3,300</span> BDT</td>
                            </tr>
                            <tr>
                                <td>Due
                                </td>
                                <td><span class="dynamicDueAmount" style="font-weight: normal;">: 1,700</span> BDT</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="disclaimer">
            <p style="font-weight: bold;">Terms & Policy:</p>
            <ul>
                <li>The due amount must be paid at the time of check-in.</li>
                <li>Booking money is not refundable.</li>
                <li>In the event of political turmoil or natural disaster, we will
                    reconsider the policy and shift (booking date)
                    based on the circumstances.</li>
                <li>If guests want to change their reservation date, may be moved to the
                    next available date. However, you
                    must let us know a week before your scheduled booking. If you choose to
                    shift, 30% of your reservation
                    fee will be deducted automatically.</li>
            </ul>
        </div>
    </div>






    <!-- Bootstrap JS and Popper.js (optional) -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>