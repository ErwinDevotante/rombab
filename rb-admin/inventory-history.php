<?php 
$a = 3;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

    date_default_timezone_set('Asia/Manila');
    // Get the current date in the Philippines timezone in the format "Y-m-d"
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');
    // Set the default value for the button status
    $buttonStatus = 'enabled'; //disabled
    // Check if the current time is 10 PM (22:00) Philippine time
    if ($currentTime === '22:00:00') {
        // Enable the button if it's 10 PM
        $buttonStatus = 'enabled';
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Inventory Reports</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="../../assets/rombab-logo.png">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../../node_modules/admin-lte/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../../node_modules/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- JQuery -->
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../../node_modules/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../node_modules/admin-lte/js/adminlte.js"></script>

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

  #sortTable_info, #sortTable_log_info, #sortTable_report,
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
    <div class="wrapper" >

        <?php 
        include "top-bar.php";
        include "side-bar.php";
        ?>

        <div class="content-wrapper bg-black">
            <div class="content p-4">

            <div class="container-fluid text-center p-4">
                <h1>Inventory Reports</h1>
                <p>Start Time: <?php echo $currentDate ?></p>
                <p>End Time: <?php echo $currentDate ?></p>
                <form method="POST" action="generate_daily_inventory_report.php" target="_blank">
                    <input type="submit" class="btn btn-danger" name="pdf_creater" value="PRINT DAILY REPORT" <?php echo $buttonStatus; ?>>
                </form>
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered table-dark mt-2" id="sortTable">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">Item</th>
                        <th class="text-center" scope="col">Description</th>
                        <th class="text-center" scope="col">UOM</th>
                        <th class="text-center" scope="col">Stocks</th>
                        <th class="text-center" scope="col">Status</th>
                    </tr>
                </thead>
                    <tbody id = "menu_table">
                    <?php 
                        $view_items = mysqli_query($connection, "SELECT * FROM inventory
                                                    INNER JOIN statuses ON statuses.status_id = inventory.item_status
                                                    ORDER BY item_id DESC");
                        if(mysqli_num_rows($view_items) > 0) {
                        while ($row = mysqli_fetch_array($view_items)) { ?>
                            <form method="post" action="inventory.php" enctype="multipart/form-data">
                                <tr>
                                    <td><?php echo $row["item_name"]; ?></td>
                                    <td><?php echo $row["item_desc"]; ?></td>
                                    <td><?php echo $row["unit_of_measure"]; ?></td>
                                    <td><?php echo $row["stock"]; ?></td>
                                    <td><?php echo $row["status"]; ?></td>
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
                <table class="table table-hover table-bordered table-dark mt-5" id="sortTable_log">
                <thead>
                    <tr><th colspan="5">Inventory Reports</th></tr>
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Item</th>
                        <th class="text-center" scope="col">Kitchen User</th>
                        <th class="text-center" scope="col">Quantity</th>
                        <th class="text-center" scope="col">Date and Time</th>
                    </tr>
                </thead>
                    <tbody id="menu_table">
                    <?php 
                        $view_items = mysqli_query($connection, "SELECT * FROM log_reports
                                                                LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                                                LEFT JOIN users ON users.user_id = log_reports.report_user_id");
                        if(mysqli_num_rows($view_items) > 0) {
                        while ($row = mysqli_fetch_array($view_items)) { ?>
                            <form method="post" action="inventory.php" enctype="multipart/form-data">
                                <tr>
                                    <td><?php echo $row["item_id"]; ?></td>
                                    <td><?php echo $row["item_name"]; ?></td>
                                    <td><?php echo $row["name"]; ?></td>
                                    <td><?php echo $row["report_qty"]; ?><?php echo $row["unit_of_measure"]; ?></td>
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

            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered table-dark mt-5" id="sortTable_report">
                <thead>
                    <tr><th colspan="2">File Reports</th></tr>
                    <tr>
                        <th class="text-center" scope="col">Report File</th>
                        <th class="text-center" scope="col">Report Date</th>
                    </tr>
                </thead>
                    <tbody id = "menu_table">
                    <?php 
                        $view_reports = mysqli_query($connection, "SELECT * FROM daily_reports");
                        if(mysqli_num_rows($view_reports) > 0) {
                        while ($row = mysqli_fetch_array($view_reports)) { ?>
                                <tr>
                                    <td><a href="daily_reports/<?php echo $row["report_file"]; ?>" target="_blank">
                                        <?php echo $row["report_file"]; ?>
                                    </a></td>
                                    <td><?php 
                                        $formattedDate = date('F j, Y | g:i A', strtotime($row["report_time"]));
                                        echo $formattedDate;
                                    ?></td>
                                </tr>
                        <?php } } else {?>
                            <tr>
                                <td class="text-center" colspan="2">No record found!</td>
                            </tr>
                        <?php } ?>
                    </tbody>  
                </table>
            </div>

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
            order: [[0, 'desc']]
        });

        // Check if DataTable is not already initialized for the table with id "sortTable_log"
        if (!$.fn.DataTable.isDataTable('#sortTable_log')) {
            // Initialize DataTable for the table element with id "sortTable_log"
            $('#sortTable_log').DataTable({
                order: [[5, 'desc']]
            });
        }
    });

    $('#sortTable_log').dataTable( {
        searching: false,
        lengthChange: false
    } );

    $('#sortTable_report').dataTable( {
        searching: false,
        lengthChange: false
    } );
</script>