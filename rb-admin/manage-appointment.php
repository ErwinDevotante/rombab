<?php 
$a = 4;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Appointment</title>
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
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" >

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>

    <div class="content-wrapper bg-black mt-5">
    <div class="content p-4">

    <div class="container-fluid text-center p-4">
        <h1>Manage Appointment</h1>
    </div>

    <section class="home-section">

    <form action="manage-appointment.php" method="post" enctype="multipart/form-data" onsubmit="rememberScrollPosition()">
    <div style="overflow-x:auto;">
        <table class="table table-hover table-bordered table-dark mt-5">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Table Number</th>
                    <th class="text-center" scope="col">Customer</th>
                    <th class="text-center" scope="col">Activate Table</th>
                    <th class="text-center" scope="col">Deactivate Table</th>
                    <th class="text-center" scope="col">Not Available Table</th>
                </tr>
            </thead>
                <tbody>
                    
                    <?php 
                    $result_tb = mysqli_query($connection, "SELECT appointment.table_id, appointment.count, appointment.appointment_name, appointment.appointment_desc, appointment.appointment_session, appointment.pwd_no, appointment.senior_no, appointment.bday_no, appointment.time, appointment.date, appointment_id,
                                                            users.user_id, users.name, users.session_tb, users.user_role 
                                                            FROM appointment RIGHT JOIN users 
                                                            ON users.user_id=appointment.table_id
                                                            WHERE users.user_role = '4'
                                                            ORDER BY users.user_id ASC");
                    if(mysqli_num_rows($result_tb) > 0){
                    while ($row = mysqli_fetch_array($result_tb)) { ?> 
                        <tr>
                            <td> <?php echo $row['name'];
                                
                                if($row['session_tb'] == '0') {  ?>
                                    <p class="text-black font-weight-bold"><a class="badge badge-warning">[NOT AVAILABLE]</a></p>
                                <?php }
                                else if($row['session_tb'] == '1')  { ?>
                                    <p class="font-weight-bold"><a class="badge badge-success">[ACTIVATED]</a></p>
                                <?php }
                                else if ($row['session_tb'] == '2')  {?>
                                    <p class="font-weight-bold"><a class="badge badge-danger">[DEACTIVATED]</a></p>
                                <?php } else { ?>
                                    <p class="font-weight-bold"><a class="badge badge-primary">[OCCUPIED]</a></p>
                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                    if ($row['session_tb'] == '3' && ($row['appointment_session'] == '1' && $row['table_id'] !== null)){ ?>
                                            <select class="form-control" name="appointment" id="appointment" data-toggle="tooltip" data-placement="top" data-bs-html="true" title="<?php echo $row["appointment_desc"]; ?>, <b>Count</b>: <?php echo $row["count"]; ?> <br><b>Date</b>: <?php echo date("F j, Y", strtotime($row["date"])); ?>, <b>Time</b>: <?php echo date("h:i A", strtotime($row["time"])); ?> <br><b>PWD</b>: <?php echo $row["pwd_no"]; ?>, <b>Senior</b>: <?php echo $row["senior_no"]; ?>, <b>Bday</b>: <?php echo $row["bday_no"]; ?>" disabled>
                                                    <option> [OCCUPIED] <?php echo $row['appointment_name']; ?> </option>
                                            </select>
                                            <div class="d-flex flex-column flex-sm-row">
                                                <a name="reset" type="submit" class="btn btn-info mt-2 mr-3 btn-xs" href="activate-table-edit.php?id_reset=<?php echo $row["appointment_id"]; ?>&reset=2&table_reset=<?php echo $row["user_id"]; ?>">RESET <i class="bi bi-arrow-clockwise"></i></a>
                                                <a name="show" type="submit" value="<?php $row['appointment_id']; ?>" class="btn btn-danger mt-2 btn-xs">ORDERS <i class="bi bi-eye"></i></a>
                                            </div>
                                    <?php }
                                    else {?>
                                        <select class="form-control" name="appointment" id="appointment" disabled>
                                            <option>Not available</option>
                                        </select>
                                    <?php }?>                   
                            </td>
                                <?php 
                                    if ($row['session_tb'] == '3' && ($row['appointment_session'] == '1' && $row['table_id'] !== null)){ ?>
                                        <td class="text-center"><a class="btn btn-success btn-xs disabled">Activate <i class="bi bi-check-circle-fill"></i></a></td>
                                        <td class="text-center"><a class="btn btn-danger btn-xs disabled">Deactivate <i class="bi bi-x-circle-fill"></i></a></td>
                                        <td class="text-center"><a class="btn btn-warning btn-xs text-black disabled" >Not Available <i class="bi bi-ban-fill"></i></a></td>
                                    <?php }
                                    else {?>
                                        <td class="text-center"><a href="activate-table-edit.php?id=<?php echo $row["user_id"]; ?>&session=1" name="activate-tbl" type="submit" class="btn btn-success">Activate <i class="bi bi-check-circle-fill"></i></a></td>
                                        <td class="text-center"><a href="activate-table-edit.php?id=<?php echo $row["user_id"]; ?>&session=2" name="deactivate-tbl" type="submit" class="btn btn-danger">Deactivate <i class="bi bi-x-circle-fill"></i></a></td>
                                        <td class="text-center"><a href="activate-table-edit.php?id=<?php echo $row["user_id"]; ?>&session=0" name="not-available-tbl" type="submit" class="btn btn-warning" >Not Available <i class="bi bi-ban-fill"></i></a></td>
                                    <?php }?> 
                        </tr>
                    <?php 
                    } }
                    ?>
                </tbody>
        </table>
    </div>
    </form>
    </section>
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
    function rememberScrollPosition() {
        // Store the current scroll position in session storage
        sessionStorage.setItem('scrollPosition', window.scrollY);
    }

    function restoreScrollPosition() {
        // Retrieve the stored scroll position from session storage
        const scrollPosition = sessionStorage.getItem('scrollPosition');

        // If there is a stored scroll position, scroll to that position
        if (scrollPosition !== null) {
            window.scrollTo(0, parseInt(scrollPosition));
        }
    }

    // Call restoreScrollPosition when the document is ready
    $(document).ready(function () {
        restoreScrollPosition();
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>