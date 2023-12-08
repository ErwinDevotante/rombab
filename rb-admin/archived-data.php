<?php 
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
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" >

        <?php 
        include "top-bar.php";
        include "side-bar.php";
        ?>

        <div class="content-wrapper bg-black">
            <div class="content p-4">

            <div class="container-fluid text-center p-4">
                <h1>Archived Data</h1>
                <p><small><em>Note: This is a reminder that our system is set to automatically delete data every 30 days. As part of our regular data management process, any information older than 30 days will be permanently removed.</em></small></p>
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered table-dark mt-5" id="sortTable_log">
                <thead>
                    <tr><th colspan="6">Inventory Reports</th></tr>
                    <tr>
                        <th></th>
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