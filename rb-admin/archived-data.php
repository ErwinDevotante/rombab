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

    if (isset($_POST["retrieve_btn_menu"])) {
        $menu_id_to_retrive = $_POST['retrieve_btn_menu'];

        $select_query_menu = "SELECT * FROM menus_archive WHERE menu_id_archive='$menu_id_to_retrive'";
        $result_select_menu = mysqli_query($connection, $select_query_menu);
        $row_select_menu = mysqli_fetch_array($result_select_menu);

        // Insert data into menu_archive
        $insert_retrieve_menu_query = "INSERT INTO menus (menu_id, menu_image, menu_name, menu_category, menu_price, menu_availability)
        VALUES ('{$row_select_menu['menu_id']}', '{$row_select_menu['menu_image']}', '{$row_select_menu['menu_name']}', '{$row_select_menu['menu_category']}',
        '{$row_select_menu['menu_price']}', '{$row_select_menu['menu_availability']}')";
        $result_insert_retrieve_menu = mysqli_query($connection, $insert_retrieve_menu_query);

        if ($result_insert_retrieve_menu) {
            // Delete the inventory record
            $delete_query_menu = "DELETE FROM menus_archive WHERE menu_id_archive='$menu_id_to_retrive'";
            $result_delete_menu = mysqli_query($connection, $delete_query_menu);

            if ($result_delete_menu) {
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

    if (isset($_POST["retrieve_btn_history"])) {
        $item_id_to_retrive_history = $_POST['retrieve_btn_history'];
    
            // Delete the inventory record
            $update_history_query = "UPDATE appointment_history SET as_archived = '0', archived_at = NULL WHERE history_id = '$item_id_to_retrive_history'";
            $result_update_history = mysqli_query($connection, $update_history_query);
    
            if ($result_update_history) {
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css"/>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
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

    .dataTables_info {
      display: none;
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
            <table class="table table-hover table-striped table-bordered table-dark mt-5" style="width:100%" id="sortTable_stocks">
                <thead>
                    <tr><th colspan="5"><h3>Inventory Stocks</h3></th></tr>
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
                                    <td><?php echo $row["item_name"]; ?></td>
                                    <td><?php echo $row["item_desc"]; ?></td>
                                    <td><?php echo $row["unit_of_measure"]; ?></td>
                                    <td><?php echo $row["stock"]; ?></td>
                                    <td>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-xs btn-info" name="retrive_btn_inventory" value="<?php echo $row["item_id_archive"]; ?>">RETRIEVE <i class="bi bi-download"></i></button>
                                        </div>
                                    </form>
                                    </td>
                                </tr>
                        <?php } }?>
                    </tbody>  
                </table>
            </div>

            <div style="overflow-x:auto;" class="mt-5">
                <table class="table table-hover table-striped table-bordered table-dark mt-5" style="width:100%;" id="sortTable_log">
                <thead>
                    <tr><th colspan="6"><h3>Inventory Reports</h3></th></tr>
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
                                            <button type="submit" class="btn btn-xs btn-info" name="retrive_btn_kitchen" value="<?php echo $row["report_id"]; ?>">RETRIEVE <i class="bi bi-download"></i></button>
                                        </form>
                                    </td>
                                </tr>
                        <?php } } ?>
                    </tbody>  
                </table>
            </div>

            <div style="overflow-x:auto;" class="mt-5">
                <table class="table table-hover table-striped table-bordered table-dark mt-5" style="width:100%" id="sortTable_report">
                <thead>
                    <tr><th colspan="3"><h3>File Reports</h3></th></tr>
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
                                        <button type="submit" class="btn btn-xs btn-info" name="retrive_btn_reports" value="<?php echo $row["report_id"]; ?>">RETRIEVE <i class="bi bi-download"></i></button>
                                        </form>
                                    </td>
                                </tr>
                        <?php } } ?>
                    </tbody>  
                </table>
            </div>
            
            <div style="overflow-x:auto;" class="mt-5">
            <table class="table table-striped table-hover table-bordered table-dark mt-5" style="width:100%" id="sortTable_menus">
            <thead>
                <tr><th colspan="5"><h3>Archived Menus</h3></th></tr>
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
                        <tr>
                            <td class="text-center w-25"><img src ='menu-images/<?php echo $row["menu_image"]; ?>' class="img-fluid img-thumbnail custom-image"></td>
                            <td class="text-center"><?php echo $row["menu_name"]; ?></td>
                            <td class="text-center"><?php echo $row["menu_price"]; ?></td>
                            <td class="text-center"><?php echo $row["menu_category"]; ?></td>
                            <td class="text-center">
                            <form method="post" enctype="multipart/form-data">
                               <button type="submit" class="btn btn-info btn-xs" name="retrieve_btn_menu" value="<?php echo $row["menu_id_archive"]; ?>">RETRIEVE <i class="bi bi-download"></i></button>
                            </form>
                            </td>
                        </tr>
                    <?php } }?>
                </tbody>  
            </table>
            </div>

            <div style="overflow-x:auto;" class="mt-5">
              <table class="table table-hover table-striped table-bordered table-dark mt-5 text-white" style="width:100%" id="sortTable_history">
              <thead>
                <tr><th colspan="8"><h3>Appointment History</h3></th></tr>
                  <tr>
                      <th class="text-center" scope="col">Name</th>
                      <th class="text-center" scope="col">Description</th>
                      <th class="text-center" scope="col">Table No</th>
                      <th class="text-center" scope="col">Count</th>
                      <th class="text-center" scope="col">Date</th>
                      <th class="text-center" scope="col">In</th>
                      <th class="text-center" scope="col">Out</th>
                      <th class="text-center" scope="col">Action</th>
                  </tr>
              </thead>
                  <tbody>
                  <?php 
                      $result_tb = mysqli_query($connection, "SELECT * FROM appointment_history
                      INNER JOIN users ON users.user_id=appointment_history.table_history_id
                      INNER JOIN appointment ON appointment.appointment_id=appointment_history.appointment_user_id
                      WHERE appointment.appointment_session = '2' AND as_archived = '1'");
                      if(mysqli_num_rows($result_tb) > 0) {
                      while ($row = mysqli_fetch_array($result_tb)) { ?> 
                          <tr>
                              <td class="text-center"><?php echo $row["appointment_name"]; ?></td>
                              <td class="text-center"><?php echo $row["appointment_desc"]; ?></td>
                              <td class="text-center"><?php echo $row["name"]; ?></td>
                              <td class="text-center"><?php echo $row["count"]; ?></td>
                              <td class="text-center"><?php $formattedDate = date('F j, Y', strtotime($row["date"])); 
                                                      echo $formattedDate;?></td>
                              <td class="text-center"><?php $formattedDateTime = date('g:i A', strtotime($row["time"])); 
                                                      echo $formattedDateTime;?></td>
                              <td class="text-center"><?php $formattedDateTimeout = date('g:i A', strtotime($row["time-out"])); 
                                                      echo $formattedDateTimeout;?></td>
                              <td>
                                <form method="POST" enctype="multipart/form-data">
                                  <button type="submit" class="btn btn-xs btn-info" name="retrieve_btn_history" value="<?php echo $row["history_id"]; ?>">RETRIEVE <i class="bi bi-download"></i></button>
                                </form>
                              </td>
                          </tr>
                          <?php 
                      } } ?>
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

    $(document).ready( function () {
    $('#sortTable_stocks').DataTable({
        lengthChange: false
    });
    } );

    $(document).ready( function () {
    $('#sortTable_log').DataTable({
        lengthChange: false
    });
    } );

    $(document).ready( function () {
    $('#sortTable_report').DataTable({
        lengthChange: false
    });
    } );

    $(document).ready( function () {
    $('#sortTable_history').DataTable({
        lengthChange: false
    });
    } );

    $(document).ready( function () {
    $('#sortTable_menus').DataTable({
        lengthChange: false
    }); 
    } );
    
</script>