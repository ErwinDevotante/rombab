<?php 
$a = 4;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
	$result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id'");
	$row = mysqli_fetch_array($result);

if (isset($_POST['log_item'])) {
    $item_id = $_POST['item-id'];
    $item_qty = $_POST['item-qty'];

    date_default_timezone_set('Asia/Manila');
    // Get the current date and time in the Philippines
    $currentDateTime = new DateTime();
    $datetimeValue = $currentDateTime->format('Y-m-d H:i:s'); 

    // Check if the item is available in the inventory
    $inventory_query = "SELECT * FROM inventory WHERE item_id = '$item_id' AND stock >= $item_qty";
    $inventory_result = mysqli_query($connection, $inventory_query);
    if (mysqli_num_rows($inventory_result) > 0) {
        // Item is available, so proceed to log it
        $insert_query = "INSERT INTO log_reports (report_item_id, report_qty, report_user_id, date_time) VALUES ('$item_id', '$item_qty', '$id', '$datetimeValue')";
        $insert_result = mysqli_query($connection, $insert_query);

        if ($insert_result) {
            // Log entry successfully inserted
            // Update the inventory stock
            $update_stock_query = "UPDATE inventory SET stock = stock - '$item_qty' WHERE item_id = '$item_id'";
            $update_stock_result = mysqli_query($connection, $update_stock_query);
        
            if ($update_stock_result) {
                echo '<script>alert("Item successfully logged!");</script>';
            } else {
                echo '<script>alert("Error updating stock. Please try again.");</script>';
            }
        } 
    } else {
        // Item is not available in sufficient quantity
        echo '<script>alert("Item is not available in the desired quantity.");</script>';
    }
    unset($_POST);
    header('Location: log-reports.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Kitchen</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="../assets/rombab-logo.png">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../node_modules/ionicons/css/ionicons.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../node_modules/admin-lte/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../node_modules/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- JQuery -->
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../node_modules/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../node_modules/admin-lte/js/adminlte.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<style>
  /* Custom styles for DataTables */
  #sortTable_wrapper .dataTables_length,
  #sortTable_wrapper .dataTables_filter,
  #sortTable_wrapper .dataTables_paginate
  #sortTable_wrapper .dataTables_paginate .paginate_button {
    color: white; /* Set the text color for "Show entries," search, and pagination */
  }
  #sortTable_info {
    color: white; /* Set the text color for "No. of entries" text */
  }
   /* Custom styles for DataTables */
   #sortTable {
    color: white; /* Set the text color for the entire table */
  }

  #sortTable thead th {
    color: white; /* Set the text color for table headers */
  }

  #sortTable tbody td {
    color: white; /* Set the text color for table cells */
  }

  #sortTable_length .dataTables_length select option,
  #sortTable_length .dataTables_length label,
  #sortTable_length .dataTables_length span {
    color: white; /* Set the text color for "Show entries" text inside the drop-down box */
  }

  #sortTable_info, #sortTable_log_info,
  #sortTable_length .dataTables_length label,
  #sortTable_filter input[type="search"] {
    color: white; /* Set the text color for "No. of entries" text and search input */
  }

  #sortTable_wrapper .dataTables_paginate .paginate_button {
    color: white; /* Set the text color for pagination buttons */
    background-color: transparent; /* Optional: Set the background-color of pagination buttons to transparent */
  }

  #sortTable_length {
        display: none;
    }
</style>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>
    <div class="content-wrapper bg-black">
        <div class="content p-4">
            <div class="container-fluid text-center p-4">
                <h1>Inventory Reports</h1>
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered table-dark mt-2" id="sortTable">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Item</th>
                        <th class="text-center" scope="col">Description</th>
                        <th class="text-center" scope="col">UOM</th>
                        <th class="text-center" scope="col">Stocks</th>
                    </tr>
                </thead>
                    <tbody id = "menu_table">
                    <?php
                        $inventory_tb = "SELECT * FROM inventory
                        INNER JOIN statuses ON statuses.status_id = inventory.item_status
                        WHERE item_status = '0'";
                        $view_items = mysqli_query($connection, $inventory_tb);
                        if(mysqli_num_rows($view_items) > 0) {
                        while ($row = mysqli_fetch_array($view_items)) { ?>
                            <form method="post" action="inventory.php" enctype="multipart/form-data">
                                <tr>
                                    <td class="text-center"><?php echo $row["item_id"]; ?></td>
                                    <td><?php echo $row["item_name"]; ?></td>
                                    <td><?php echo $row["item_desc"]; ?></td>
                                    <td><?php echo $row["unit_of_measure"]; ?></td>
                                    <td class="text-center"><?php echo $row["stock"]; ?></td>
                                </tr>
                            </form>
                        <?php } } else {?>
                            <tr>
                                <td class="text-center" colspan="5">No record found!</td>
                            </tr>
                        <?php } ?>
                    </tbody>  
                </table>
            </div>

            <div style="overflow-x:auto;">
            <div class="p-4">
                <form method="post" action="log-reports.php" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col">
                            <label>Item ID</label>
                            <input type="number" class="form-control" name="item-id" placeholder="Enter Item ID"  min="1" required>
                        </div>
                        <div class="form-group col">
                            <label>Quantity</label>
                            <input type="number" class="form-control" name="item-qty" step="any" placeholder="Enter Number of Stock" min="0.1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="log_item" value="LOG ITEM">
                    </div>
                </form>
            </div>

            
                <table class="table table-hover table-bordered table-dark mt-2" id="sortTable_log">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Item</th>
                        <th class="text-center" scope="col">Quantity</th>
                        <th class="text-center" scope="col">Date and Time</th>
                    </tr>
                </thead>
                    <tbody id = "menu_table">
                    <?php 
                        $view_items = mysqli_query($connection, "SELECT * FROM log_reports
                                                                LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                                                WHERE report_user_id = $id");
                        if(mysqli_num_rows($view_items) > 0) {
                        while ($row = mysqli_fetch_array($view_items)) { ?>
                            <form method="post" action="inventory.php" enctype="multipart/form-data">
                                <tr>
                                    <td class="text-center"><?php echo $row["item_id"]; ?></td>
                                    <td><?php echo $row["item_name"]; ?></td>
                                    <td class="text-center"><?php echo $row["report_qty"]; ?><?php echo $row["unit_of_measure"]; ?></td>
                                    <td><?php echo $row["date_time"]; ?></td>
                                </tr>
                            </form>
                        <?php } } else {?>
                            <tr>
                                <td class="text-center" colspan="4">No record found!</td>
                            </tr>
                        <?php } ?>
                    </tbody>  
                </table>
            </div>
            
        </div>
    </div>
</body>
<!-- Footer -->
<footer class="main-footer bg-black text-center">
    <div class="float-right d-none d-sm-block">
        <!-- Additional footer content or links can go here -->
    </div>
    Romantic Baboy – SM City Sta. Rosa Branch
 &copy; <?php echo date("Y"); ?>
</footer>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize DataTable for the table element with id "sortTable"
        $('#sortTable').DataTable({
            order: [[0, 'asc']]
        });

        // Check if DataTable is not already initialized for the table with id "sortTable_log"
        if (!$.fn.DataTable.isDataTable('#sortTable_log')) {
            // Initialize DataTable for the table element with id "sortTable_log"
            $('#sortTable_log').DataTable({
                order: [[3, 'desc']]
            });
        }
    });

    $('#sortTable_log').dataTable( {
        searching: false,
        lengthChange: false
    } );

    // Get references to both input elements
    const qtyInput = document.querySelector('input[name="item-qty"]');
    const itemIdInput = document.querySelector('input[name="item-id"]');

    // Add event listener for Quantity input
    qtyInput.addEventListener('input', function() {
        let qty = parseFloat(qtyInput.value);
        
        // Check for negative zero (-0.0) and set it to 0.1
        if (qty === 0) {
            qtyInput.value = '0.1';
            qty = 0.1;
        } else if (qty < 0) {
            qtyInput.value = '0.1'; // You can change the default value
            qty = 0.1;
        }
    });

    // Add event listener for Item ID input
    itemIdInput.addEventListener('input', function() {
        let itemId = parseFloat(itemIdInput.value);
        
        // Check for negative zero (-0.0) and set it to an empty string
        if (itemId === 0) {
            itemIdInput.value = '';
        } else if (itemId < 0) {
            itemIdInput.value = '';
        }
    });
</script>