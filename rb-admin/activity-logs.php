<?php 
$a = 1;
session_start();
include '../conn.php';
if($_SESSION['user_id']==''){
	header('location:../index.php');
}
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);
    date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Activity Log</title>
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
<body class="hold-transition sidebar-mini layout-fixed text-white" style="background: #191919;">
    <div class="wrapper" >

        <?php 
        include "top-bar.php";
        include "side-bar.php";
        ?>

        <div class="content-wrapper mt-5" style="background: #191919;">
            <div class="content p-4">
                <div class="container-fluid text-center p-4">
                    <h1>Activity Log</h1>
                    <p><small>An Activity Log is a chronological record that tracks and details a user's actions providing a comprehensive history of activities.</small></p>
                </div>

                <form method="post" action="">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="chosen_date">Select Date:</label>
                            <input type="date" class="form-control" id="chosen_date" name="chosen_date" max="<?php echo date("Y-m-d"); ?>" min="2022-11-09">
                        </div>
                    </div>  

                    <button type="submit" name="show_table" class="btn bg-red">SHOW</button>
                </form>

                <?php
                if (isset($_POST["show_table"])) {
                    if (!empty($_POST["chosen_date"])) {
                        $selectedDate = $_POST["chosen_date"];

                        $activity_logs = mysqli_query($connection, "SELECT * FROM activity_log 
                                                        INNER JOIN users ON activity_log.log_user_id = users.user_id 
                                                        INNER JOIN user_role ON users.user_role = user_role.user_role_id
                                                        WHERE DATE(activity_log.date_time) = '$selectedDate'");

                        echo "<h5 class='mt-3'>Date: ". date('F j, Y', strtotime($selectedDate))."</h5>";

                        if (!$activity_logs) {
                            // Display an error message if the query fails
                            echo '<p class="text-danger">Error executing the query: ' . mysqli_error($connection) . '</p>';
                        } else { ?>
                                <div>
                                    <table class="table mb-5">
                                        <thead>
                                            <tr class="bg-dark">
                                                <th>No</th>
                                                <th>User</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                                <th>Time</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            if (mysqli_num_rows($activity_logs) > 0) {
                                                while ($row = mysqli_fetch_array($activity_logs)) {
                                                    echo "<tr>";
                                                        echo "<td>".$i."</td>";
                                                        echo "<td>".$row['name']."</td>";
                                                        echo "<td>".$row['roles']."</td>";
                                                        echo "<td>".$row['action']."</td>";
                                                        echo "<td>".date('g:i A', strtotime($row['date_time']))."</td>";
                                                        echo "<td>".date('F j, Y', strtotime($row['date_time']))."</td>";
                                                    echo "<tr>";
                                                    $i++;
                                                }
                                            } else {
                                                echo "<td class='text-center' colspan='6'>No record available!</td>";
                                            }   
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                </div> 
                        <?php }
                    } else {
                        // Display a message or take any other action if no duration is selected
                        echo '<p class="text-danger">Please select a date to see activty logs.</p>';
                    }

                }
                ?>
            </div>
        </div>
    </div>
</body>
<!-- Footer -->
<footer class="main-footer text-center" style="background: #191919;">
    <div class="float-right d-none d-sm-block">
        <!-- Additional footer content or links can go here -->
    </div>
    Romantic Baboy – SM City Sta. Rosa Branch
 &copy; <?php echo date("Y"); ?>
</footer>
</html>

<script>

</script>