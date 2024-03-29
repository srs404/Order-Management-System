<?php

/**
 * Title: Check User Login Session
 * ~ Description: This function will check if the user is logged in or not
 * ~ This function is called when the page is loaded
 */
if (!isset($_SESSION['agent']['loggedin'])) {
    if (!$_SESSION['agent']['loggedin']) {
        include_once "index.php";
    }
}

/**
 * Title: Logout
 * ~ Description: This function will logout the user
 * ~ This function is called when the logout button is clicked
 */
if (isset($_GET['logout'])) {
    if ($_GET['logout'] == true) {
        session_destroy();
        header("Refresh:0");
    }
}

/**
 * Title: Generate Receipt Object
 * ~ Description: This function will generate a new receipt object
 */

use Server\Model\Receipt; # Import the Receipt class

$receipt = new Receipt(); # Create a new receipt object

$receipt_id = $receipt->generateReceiptID(); # Generate a new receipt ID

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TripUp Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600&display=swap" rel="stylesheet">
    <!-- Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="Assets/CSS/receipt.css">
    <link rel="stylesheet" href="Assets/CSS/main.css">

    <input type="hidden" id="agent_id" value="<?php echo $_SESSION['agent']['id']; ?>">

    <!-- Modal: 1 -->
    <!-- Title: Customer Information Modal -->
    <!-- ~ Description: This is the infomation page of the modal or first page -->

    <div class="modal fade" id="customerInformationModal" tabindex="-1" aria-labelledby="customerInformationModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="customerInformationModal">Receipt No: <span id="receipt_number_1"></span></h1>
                    <button type="button" id="modalCloseBtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <form class="row g-3">

                        <div class="col-md-4 position-relative">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Sami Rahman">
                        </div>
                        <div class="col-md-4 position-relative">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" placeholder="sami@rahman.dev">
                        </div>
                        <div class="col-md-4 position-relative">
                            <label for="phone-number" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text" id="phone-Number-Prepend">+880</span>
                                <input type="number" max="1999999999" placeholder="1625469920" class="form-control" id="phone-number" aria-describedby="phone-Number-Prepend">
                            </div>
                        </div>
                        <div class="col-md-6 position-relative">
                            <label for="payment-date" class="form-label">Payment Date</label><input class="form-check-input bg-dark" type="checkbox" style="margin-left: 10px;" id="payment-date-checkbox" onchange="enableCurrentDateCheckbox(this)">
                            <input type="date" id="payment-date" class="form-control" onchange="checkDate('unlock-due-date')" disabled>

                        </div>
                        <div class="col-md-6 position-relative">
                            <label for="due-date" class="form-label">Due Date</label>
                            <input type="date" id="due-date" class="form-control" onchange="checkDate('')">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modalDiscardBtn" class="btn btn-danger" data-bs-dismiss="modal" style="position: absolute; left: 10px;">Discard</button>
                    <button type="submit" id="nextModalBtn" class="btn btn-outline-dark"><span class="fa fa-arrow-right"></span></button>
                </div>

            </div>
        </div>
    </div>

    <!-- 
        ! ==========================================
        ! END: customerInformationModal
        ! ==========================================
    -->


    <!-- Modal: 2 -->
    <!-- 
        Title: Item Modal
        ~ Description: This is the primary modal for creating new invoice
    -->
    <div class="modal fade" id="createNewModal" tabindex="-1" aria-labelledby="createNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createNewLabel">Receipt No: <span id="receipt_number_2"></span></h1>
                    <button type="button" id="tableModalCloseBtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3" style="height: 400px;">
                    <div class="col-md-12" style="text-align: right; margin-bottom: 10px;">
                        <button class="btn btn-primary" id="addNewItemBtn"><span class="fa fa-plus"></span> New
                            Item</button>
                    </div>
                    <form id="item-form" class="row g-3">
                        <div class="col-md-12">
                            <div class="table-responsive-sm" style="max-height: 400px; margin-bottom: 10px; min-height: 300px; border-bottom: 1px solid black; overflow-y: auto;">
                                <table class="table text-center" id="item-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Item Description</th>
                                            <th><label for="price-checkbox">Price</label> <input class="form-check-input" type="checkbox" value="" id="price-checkbox">
                                            </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                    </form>
                </div>

                <!-- Subtotal Input Form -->
                <div id="row" style="margin-bottom: 10px;">
                    <div class="input-group">
                        <div class="form-floating">
                            <input style="text-align: right; cursor: not-allowed;" type="number" disabled class="form-control" id="subtotal" name="subtotal" placeholder="0000" onchange="if(document.getElementById('discount').disabled) { handleDiscount('total-payable'); } else if(document.getElementById('discountAmount').disabled) {handleDiscount('total-payable')} else { handleDiscount('discount'); }">
                            <label for="discount">Subtotal</label>
                        </div>
                        <span class="input-group-text" style="cursor: not-allowed;">BDT</span>
                    </div>
                </div>

                <!-- Discount Input Form -->
                <div class="col-md-12 position-relative">
                    <div class="col-md-12 position-absolute top-0 end-0">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="input-group">
                                <div class="form-floating">
                                    <input style="text-align: right;" type="number" class="form-control" min="0" max="100" oninput="handleDiscount('discount')" id="discount" name="discount" placeholder="Discount"> <label for="discount">Discount</label>
                                </div>
                                <span class="input-group-text" style="cursor: not-allowed;"><span class="fa fa-percent"></span></span>
                                <div class="form-floating">
                                    <input style="text-align: right;" type="number" class="form-control" id="discountAmount" oninput="handleDiscount('discountAmount')" placeholder="1000">
                                    <label for="discountAmount">Amount</label>
                                </div>
                                <span class="input-group-text" style="cursor: not-allowed; width: 55px;">BDT</span>
                            </div>
                        </div>


                        <!-- Payable Input Form -->
                        <div id="row" style="margin-bottom: 10px;">
                            <div class="input-group">
                                <div class="form-floating">
                                    <input style="text-align: right;" type="number" class="form-control" min="0" oninput="handleDiscount('total-payable')" id="total-payable" name="total-payable" placeholder="0000"> <label for="total-payable">Payable</label>
                                </div>
                                <span class="input-group-text" style="cursor: not-allowed;">BDT</span>
                            </div>
                        </div>
                        <!-- Convenience fee Input Form -->
                        <div id="row" style="margin-bottom: 10px;">
                            <div class="input-group">
                                <div class="form-floating">
                                    <input style="text-align: right;" type="number" class="form-control" id="convenience-fee" placeholder="0000"> <label for="convenience-fee" oninput="duePaymentCalculator()">Convenience
                                        Fee</label>
                                </div>
                                <span class="input-group-text" style="cursor: not-allowed;">BDT</span>
                            </div>
                        </div>

                        <!-- Advance Payment Input Form -->
                        <div id="row" style="margin-bottom: 10px;">
                            <div class="input-group">
                                <div class="form-floating">
                                    <input style="text-align: right;" type="number" class="form-control" id="advance-payment" placeholder="0000"> <label for="advance-payment" oninput="duePaymentCalculator()">Advance
                                        Payment</label>
                                </div>
                                <span class="input-group-text" style="cursor: not-allowed;">BDT</span>
                            </div>
                        </div>

                        <!-- Due Payment Input Form -->
                        <div id="row" style="margin-bottom: 10px;">
                            <div class="input-group">
                                <div class="form-floating">
                                    <input style="text-align: right;" type="number" class="form-control" id="due-payment" disabled value="0" name="due-payment"> <label for="due-payment">Due
                                        Payment</label>
                                </div>
                                <span class="input-group-text" style="cursor: not-allowed;">BDT</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button id="previewInvoiceBTN" class="btn btn-info text-white"><span class="fa fa-eye"></span></button>
                <button id="backModalBtn" class="btn btn-outline-dark" style="position: absolute; left: 10px; bottom: 10px;"><span class="fa fa-arrow-left"></span></button>
                <button id="submitReceiptBTN" class="btn btn-primary">Create</button>
                <button id="updateReceiptBTN" style="display: none;" class="btn btn-primary">Update</button>
            </div>

        </div>
    </div>
    </div>

    <!-- 
        ! ==========================================
        ! END: Item Modal
        ! ==========================================
    -->


    <!-- Modal 3 -->
    <!-- 
        Title: Item Input Modal
        ~ Description: This is the modal to get table content information
    -->

    <div class="modal fade" id="addNewTableItem" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="addNewTableItem" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h1 class="modal-title fs-5" id="addNewTableItem">Add New Item</h1>
                    <button type="button" id="modalItemCloseBtn" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-3">
                        <label for="item-nameModal" class="form-label">Item Name</label>
                        <select class="form-select" id="item-nameModal">
                            <option selected disabled value="">Choose...</option>
                            <option value="Hotel/Resort">Hotel/Resort</option>
                            <option value="Ship">Ship</option>
                            <option value="Flight">Flight</option>
                            <option value="Package">Package</option>
                            <option value="Bus">Bus</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="item-description-modal" class="form-label">Item Description</label>
                        <textarea class="form-control" id="item-description-modal" rows="6"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="item-price-modal" class="form-label">Item Price</label>
                        <div class="input-group" id="item-price-container-modal">
                            <span class="input-group-text">BDT</span>
                            <input type="number" class="form-control" style="text-align: right;" id="item-price-modal" oninput="subtotalCalculator()" placeholder="0" aria-label="Item Quantity">
                            <span class="input-group-text">৳</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-dark text-bold">
                    <button type="button" id="modalItemClearBtn" class="btn btn-outline-danger" data-bs-dismiss="modal" style="position: absolute; font-weight: bold; left: 10px; bottom: 10px;">Discard</button>
                    <button type="button" class="btn btn-primary" id="modalItemSubmitBtn" data-bs-dismiss="modal">Insert</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 
        ! ==========================================
        ! END: Item Input Modal
        ! ==========================================
    -->


    <!-- PRINT PREVIEW IFRAME -->
    <!-- 
        Title: Receipt Preview IFRAME
        ~ Description: This is the modal to preview the receipt
    -->

    <div class="modal" id="previewModal">
        <div id="iframeModal"></div>
    </div>



    <!-- 
        ! ==========================================
        ! END: PRINT PREVIEW IFRAME
        ! ==========================================
    -->


</head>

<body>
    <div id="mainBodyDiv">
        <!--Main Navigation-->
        <header>
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">

                <div class="position-sticky">

                    <div class="list-group list-group-flush mx-3 mt-4">

                        <a href="#" class="list-group-item list-group-item-action py-2 ripple" id="sidebarCreateNew">
                            <i class="fas fa-chart-area fa-book me-3"></i><span>Create New</span>
                        </a><br><br>
                        <a href="#" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
                            <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Main Dashboard</span>
                        </a>

                        <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i class="fas fa-chart-line fa-fw me-3"></i><span>Analytics (Soon)</span></a>
                        <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i class="fas fa-users fa-fw me-3"></i><span>User Settings</span></a>

                    </div>

                </div>
                <a href="<?php echo $_SERVER['PHP_SELF'] ?>?logout=true" class="btn btn-danger" style="position: absolute; bottom: 0px; padding: 15px; width: 100%; border-radius: 0px;"><i class="fas fa-money-bill fa-power-off me-3"></i><span>Logout</span></a>
            </nav>
            <!-- Sidebar -->


            <!-- Navbar -->
            <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
                <!-- Container wrapper -->
                <div class="container-fluid">
                    <!-- Toggle button -->
                    <!-- <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button> -->

                    <!-- Brand -->
                    <a class="navbar-brand" href="#" style="position: fixed; left: 20px; top: 10px;">
                        <img src="Assets/Images/tripupmainlogo.png" height="55" />
                    </a>

                    <!-- Right links -->
                    <ul class="navbar-nav ms-auto d-flex flex-row">
                        <!-- Search form -->

                        <li class="nav-item">
                            <a class="nav-link me-3 me-lg-3" href="#" id="createNewNavBtn" role="button">
                                <i class="fas fa-folder-plus"></i>
                            </a>
                        </li>

                        <!-- Icon -->
                        <li class="nav-item">
                            <a class="nav-link me-3 me-lg-3" href="#">
                                <i class="fas fa-user-pen"></i>
                            </a>
                        </li>

                        <!-- Icon -->
                        <li class="nav-item">
                            <a class="nav-link me-3 me-lg-3" href="<?php echo $_SERVER['PHP_SELF'] ?>?logout=true'">
                                <i class="fas fa-power-off"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Container wrapper -->
            </nav>
            <!-- Navbar -->

        </header>
        <!--Main layout-->
        <main id="mainBody" style="min-height: calc(100vh - 58px);">


            <div class="card" style="max-height:600px !important; overflow: auto;">
                <div class="card-body">
                    <div class="d-flex justify-content-between" style="padding-left: 12px; padding-bottom: 10px; padding-right: 12px;">
                        <form class="input-group w-25">
                            <input id="mySearch" id="myTable_filter" autocomplete="off" type="search" class="form-control rounded border-3" placeholder='Search (ctrl + "/" to focus)' />
                        </form>
                        <button class="btn btn-primary" id="newInvoice"><span class="fa fa-plus"></span> New</button>
                    </div>
                    <div class="container">
                        <div class="table-responsive text-center">
                            <table class="table table-striped" id="myTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>


        <!--Main layout-->

        <footer id="footerMenu" class="bg-light text-center shadow-lg border-top">
            <!-- Copyright -->
            <div class="text-center p-3">
                © 2024 Copyright:
                <a class="text-dark" href="https://www.linkedin.com/in/srs404">TripUp Inc.</a>
                | All Rights Reserved
            </div>
            <!-- Copyright -->
        </footer>
    </div>


    <!-- Bootstrap JS and Popper.js (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="Assets/JS/main.js"></script>
    <script src="Assets/JS/scripts.js"></script>

</body>

</html>