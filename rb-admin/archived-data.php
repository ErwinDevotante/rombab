<?php 
$a = 1;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);
    date_default_timezone_set('Asia/Manila');

    if (isset($_POST["retrive_btn_kitchen"])) {
        $item_id_to_retrive_report = $_POST['retrive_btn_kitchen'];
    
            // Delete the inventory record
            $update_report_query = "UPDATE log_reports SET as_archived = '0', archived_at = NULL WHERE report_id = '$item_id_to_retrive_report'";
            $result_update_report = mysqli_query($connection, $update_report_query);
    
            if ($result_update_report) {
                // Redirect back to the inventory.php page after retriving
                header('Location: archived-data.php');
                exit();
            } else {
                // Handle the error if the deletion fails
                echo "Error deleting inventory record: " . mysqli_error($connection);
            }
    }

    if (isset($_POST["retrive_btn_inventory"])) {
        $item_id_to_retrive = $_POST['retrive_btn_inventory'];

        $select_query = "SELECT * FROM inventory_archive WHERE item_id_archive='$item_id_to_retrive'";
        $result_select = mysqli_query($connection, $select_query);
        $row_select = mysqli_fetch_array($result_select);

        $insert_retrive_query = "INSERT INTO inventory (item_id, item_name, item_desc, unit_of_measure, stock, item_status)
        VALUES ('{$row_select['item_id']}', '{$row_select['item_name']}', '{$row_select['item_desc']}', '{$row_select['unit_of_measure']}',
        '{$row_select['stock']}', '{$row_select['item_status']}')";
        $result_insert_retrive = mysqli_query($connection, $insert_retrive_query);

        if ($result_insert_retrive) {
            // Delete the inventory record
            $delete_query = "DELETE FROM inventory_archive WHERE item_id_archive='$item_id_to_retrive'";
            $result_delete = mysqli_query($connection, $delete_query);

            if ($result_delete) {
                // Redirect back to the inventory.php page after archiving
                header('Location: archived-data.php');
                exit();
            } else {
                // Handle the error if the deletion fails
                echo "Error deleting inventory record: " . mysqli_error($connection);
            }
        } else {
            // Handle the error if the archiving fails
            echo "Error archiving inventory record: " . mysqli_error($connection);
        }
    }

    if (isset($_POST["retrive_btn_reports"])) {
        $item_id_to_retrive_dailyreport = $_POST['retrive_btn_reports'];
    
            // Delete the inventory record
            $update_dailyreport_query = "UPDATE daily_reports SET as_archived = '0', archived_at = NULL WHERE report_id = '$item_id_to_retrive_dailyreport'";
            $result_update_dailyreport = mysqli_query($connection, $update_dailyreport_query);
    
            if ($result_update_dailyreport) {
                // Redirect back to the inventory.php page after retriving
                header('Location: archived-data.php');
                exit();
            } else {
                // Handle the error if the deletion fails
                echo "Error deleting inventory record: " . mysqli_error($connection);
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Archived Data</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="../assets/rombab-logo.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../node_modules/admin-lte/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../node_modules/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../node_modules/ionicons/css/ionicons.min.css">
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
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
</head>
<style>
   /* Custom styling for DataTables */
   .dataTables_wrapper {
        color: white; /* Set the text color to white */
    }

    .dataTables_filter label {
        color: white; /* Set the search label color to white */
    }

    .dataTables_filter input {
        color: white; /* Set the search input text color to white */
        background-color: black; /* Set the search input background color to black */
    }

    .dataTables_filter input::placeholder {
        color: white; /* Set the placeholder text color to white */
    }
        /* Custom styles for DataTables search input */
    .dataTables_filter input {
    width: 200px; /* Set the desired width */
    height: 30px;
    }
</style>
<body class="hold-transition sidebar-mini layout-fixed bg-black">
    <div class="wrapper" >

        <?php 
        include "top-bar.php";
        include "side-bar.php";
        ?>

        <div class="content-wrapper bg-black mt-5">
            <!-- <div class="mt-5 d-flex justify-content-end fixed-top fixed-center" role="group">
                <div style="overflow-x:auto;">
                <a class="btn bg-yellow" href="#" onclick="jumpToDiv('inventory')">Inventory Stocks<i class="bi bi-box2-fill"></i></a>
                <a class="btn bg-green text-white" href="#" onclick="jumpToDiv('appointment')">Inventory Reports <i class="bi bi-clipboard-fill"></i></a>
                <a class="btn bg-red text-white text-nowrap" href="#" onclick="jumpToDiv('superAdmin')">Generated Reports <i class="bi bi-person-fill"></i></a>
                <a class="btn bg-red text-white text-nowrap" href="#" onclick="jumpToDiv('superAdmin')">Archived Menus<i class="bi bi-person-fill"></i></a>
                <a class="btn bg-red text-white text-nowrap" href="#" onclick="jumpToDiv('superAdmin')">Appointment History <i class="bi bi-person-fill"></i></a>
                <a class="btn bg-red text-white text-nowrap" href="#" onclick="jumpToDiv('superAdmin')">Users <i class="bi bi-person-fill"></i></a>
                </div> 
            </div> -->
            <div class="content p-4">

            <div class="container-fluid text-center p-4">
                <h1>Archived Data</h1>
                <p><small><em>Note: This is a reminder that our system is set to automatically delete data every 30 days. As part of our regular data management process, any information older than 30 days will be permanently removed.</em></small></p>
            </div>

            <div style="overflow-x:auto;">
            <table class="table table-hover table-bordered table-dark mt-5 mb-5" id="sortTable">
                <thead>
                    <tr><th colspan="6">Inventory Stocks</th></tr>
                    <tr>
                        <th class="text-center" scope="col">Item</th>
                        <th class="text-center" scope="col">Description</th>
                        <th class="text-center" scope="col">UOM</th>
                        <th class="text-center" scope="col">Stocks</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php 
                        $view_items = mysqli_query($connection, "SELECT * FROM inventory_archive
                                                    ORDER BY item_id_archive DESC");
                        if(mysqli_num_rows($view_items) > 0) {
                            while ($row = mysqli_fetch_array($view_items)) { ?>
                                <tr>
                                    <td style="display: none"><?php echo $row["item_id"]; ?></td>
                                    <td><?php echo $row["item_name"]; ?></td>
                                    <td><?php echo $row["item_desc"]; ?></td>
                                    <td><?php echo $row["unit_of_measure"]; ?></td>
                                    <td><?php echo $row["stock"]; ?></td>
                                    <td>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-xs btn-info" name="retrive_btn_inventory" value="<?php echo $row["item_id_archive"]; ?>">RETRIEVE <i class="bi bi-download"></i></button>
                                        </div>
                                    </td>
                                    </form>
                                </tr>
                        <?php } } else {?>
                            <tr>
                                <td class="text-center" colspan="7">No record found!</td>
                            </tr>
                        <?php } ?>
                    </tbody>  
                </table>
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered table-dark mt-5" id="sortTable_log">
                <thead>
                    <tr><th colspan="6">Inventory Reports</th></tr>
                    <tr>
                    <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Item</th>
                        <th class="text-center" scope="col">Kitchen User</th>
                        <th class="text-center" scope="col">Quantity</th>
                        <th class="text-center" scope="col">Date and Time</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                    <tbody id="menu_table">
                    <?php 
                        $view_items = mysqli_query($connection, "SELECT * FROM log_reports
                                                                LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                                                LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                                                WHERE log_reports.as_archived = '1'");
                        if(mysqli_num_rows($view_items) > 0) {
                            while ($row = mysqli_fetch_array($view_items)) { ?>
                                <tr>
                                    <td><?php echo $row["item_id"]; ?></td>
                                    <td><?php echo $row["item_name"]; ?></td>
                                    <td><?php echo $row["name"]; ?></td>
                                    <td><?php echo $row["report_qty"]; ?><?php echo $row["unit_of_measure"]; ?></td>
                                    <td><?php echo $row["date_time"]; ?></td>
                                    <td>
                                        <form method="POST" enctype="multipart/form-data">
                                            <button type="submit" class="btn btn-xs btn-info" name="retrive_btn_kitchen" value="<?php echo $row["report_id"]; ?>">RETRIVE <i class="bi bi-download"></i></button>
                                        </form>
                                    </td>
                                </tr>
                        <?php } } else {?>
                            <tr>
                                <td class="text-center" colspan="8">No record found!</td>
                            </tr>
                        <?php } ?>
                    </tbody>  
                </table>
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered table-dark mt-5" id="sortTable_report">
                <thead>
                    <tr><th colspan="3">File Reports</th></tr>
                    <tr>
                        <th class="text-center" scope="col">Report File</th>
                        <th class="text-center" scope="col">Report Date</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php 
                        $view_reports = mysqli_query($connection, "SELECT * FROM daily_reports WHERE as_archived = '1'");
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
                                    <td>
                                        <form method="post" enctype="multipart/form-data">
                                        <button type="submit" class="btn btn-xs btn-info" name="retrive_btn_reports" value="<?php echo $row["report_id"]; ?>">RETRIVE <i class="bi bi-download"></i></button>
                                        </form>
                                    </td>
                                </tr>
                        <?php } } else {?>
                            <tr>
                                <td class="text-center" colspan="3">No record found!</td>
                            </tr>
                        <?php } ?>
                    </tbody>  
                </table>
            </div>
            
            <div style="overflow-x:auto;">
            <table class="table table-responsive table-hover table-bordered table-dark mt-2">
            <thead>
                <tr><th colspan="5">Archived Menus</th></tr>
                <tr>
                    <th class="text-center" scope="col">Image</th>
                    <th class="text-center" scope="col">Name</th>
                    <th class="text-center" scope="col">Price (₱)</th>
                    <th class="text-center" scope="col">Category</th>
                    <th class="text-center" scope="col">Action</th>
                </tr>
            </thead>
                <tbody>
                <?php 
                    $view_menus = mysqli_query($connection, "SELECT * FROM menus_archive ORDER BY menu_id_archive DESC");
                    if(mysqli_num_rows($view_menus) > 0) {
                    while ($row = mysqli_fetch_array($view_menus)) { ?>
                    <form method="post" enctype="multipart/form-data">
                        <tr id="<?php echo $row["menu_id"]; ?>">
                            <td style="display: none"><?php echo $row["menu_id"]; ?></td> <!--hidden-->
                            <td class="text-center w-25"><img src ='menu-images/<?php echo $row["menu_image"]; ?>' class="img-fluid img-thumbnail custom-image"></td>
                            <td class="text-center"><?php echo $row["menu_name"]; ?></td>
                            <td class="text-center"><?php echo $row["menu_price"]; ?></td>
                            <td class="text-center"><?php echo $row["menu_category"]; ?></td>
                            <td class="text-center">
                               <button type="submit" class="btn btn-info btn-xs" name="archive_btn" value="<?php echo $row["menu_id_archive"]; ?>">RETRIVE <i class="bi bi-download"></i></button>
                            </td>
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
        //searching: false,
        lengthChange: false
    } );

    table.destroy();

    $('#sortTable').dataTable( {
        lengthChange: false
    } );
</script>