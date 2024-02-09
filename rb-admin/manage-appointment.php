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
<body class="hold-transition sidebar-mini layout-fixed text-white" style="background: #191919;">
<div class="wrapper">

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>

    <div class="content-wrapper mt-5" style="background: #191919;">
        <div class="content p-4">

        <div class="container-fluid text-center p-4">
            <h1 class="highlight header-colorize text-white">Appointments</h1>
        </div>

            <section class="home-section">
                
                    <div style="overflow-x:auto;">
                        <table class="table mt-5">
                            <thead>
                                <tr class="bg-dark">
                                    <th class="text-center" scope="col">Table Number</th>
                                    <th class="text-center" scope="col">Customer</th>
                                    <th class="text-center" scope="col">Activate Table</th>
                                    <th class="text-center" scope="col">Deactivate Table</th>
                                    <th class="text-center" scope="col">Not Available Table</th>
                                </tr>
                            </thead>
                                <tbody>
                                    
                                    <?php 
                                    $result_tb = mysqli_query($connection, "SELECT appointment.table_id, appointment.count, appointment.appointment_name, appointment.appointment_desc, appointment.appointment_session, appointment.pwd_no, appointment.senior_no, appointment.bday_no, appointment.time, appointment.date,  appointment.note, appointment_id,
                                                                            users.user_id, users.name, users.session_tb, users.user_role 
                                                                            FROM appointment 
                                                                            RIGHT JOIN users ON users.user_id=appointment.table_id
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
                                                            <select class="form-control" name="appointment" id="appointment" data-toggle="tooltip" data-placement="top" data-bs-html="true" title="<?php echo $row["appointment_desc"]; ?>, <b>Count</b>: <?php echo $row["count"]; ?> <br><b>Date</b>: <?php echo date("F j, Y", strtotime($row["date"])); ?>, <b>Time</b>: <?php echo date("h:i A", strtotime($row["time"])); ?> <br><b>PWD</b>: <?php echo $row["pwd_no"]; ?>, <b>Senior</b>: <?php echo $row["senior_no"]; ?>, <b>Bday</b>: <?php echo $row["bday_no"]; ?>, <b>Note</b>: <?php echo $row["note"]; ?>" disabled>
                                                                    <option> [OCCUPIED] <?php echo $row['appointment_name']; ?> </option>
                                                            </select>
                                                            <div class="d-flex flex-column flex-sm-row">
                                                                <a name="reset" type="submit" class="btn btn-info mt-2 mr-3 btn-xs" href="activate-table-edit.php?id_reset=<?php echo $row["appointment_id"]; ?>&reset=2&table_reset=<?php echo $row["user_id"]; ?>">RESET <i class="bi bi-arrow-clockwise"></i></a>
                                                                <a name="show" type="submit" value="<?php $row['appointment_id']; ?>" href="#editModal?appointmentId=<?php echo $row["appointment_id"]; ?>" class="btn btn-danger mt-2 btn-xs update_btn" data-id="<?php echo $row['appointment_id']; ?>" data-name="<?php echo $row['appointment_name']; ?>" data-count="<?php echo $row['count']; ?>" data-time="<?php echo $row['time']; ?>" data-date="<?php echo $row['date']; ?>" data-senior="<?php echo $row['senior_no']; ?>" data-pwd="<?php echo $row['pwd_no']; ?>" data-number="<?php echo $row['bday_no']; ?>" data-note="<?php echo $row['note']; ?>">DETAILS <i class="bi bi-eye"></i></a>
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
            
            </section>
        </div>
    </div>
</div>
</body>
    <div class="modal fade text-black" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manage-appointment.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="update-id" id="update-id">
                        <label class="bg-dark">Customer Information</label>
                            
                            <div class="form-group row">
                                <div class="col-sm-6">
                                <label for="name" class="col-form-label-sm">Name</label>
                                <input type="text" class="form-control form-control-sm" id="name" placeholder="Name" disabled>
                                </div>

                                <div class="col-sm-6">
                                <label for="count" class="col-form-label-sm">Count</label>
                                <input type="number" class="form-control form-control-sm" id="count" placeholder="Count">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                <label for="time" class="col-form-label-sm">Time</label>
                                <input type="time" class="form-control form-control-sm" id="time" placeholder="Time" disabled>
                                </div>

                                <div class="col-sm-6">
                                <label for="date" class="col-form-label-sm">Date</label>
                                <input type="date" class="form-control form-control-sm" id="date" placeholder="Date" disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                <label for="senior" class="col-form-label-sm">Senior</label>
                                <input type="number" class="form-control form-control-sm" id="senior" placeholder="senior">
                                </div>

                                <div class="col-sm-4">
                                <label for="pwd" class="col-form-label-sm">PWD</label>
                                <input type="number" class="form-control form-control-sm" id="pwd" placeholder="PWD">
                                </div>

                                <div class="col-sm-4">
                                <label for="bday" class="col-form-label-sm">Bday Promo</label>
                                <input type="number" class="form-control form-control-sm" id="number" placeholder="Bday Promo">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note" class="col-form-label-sm">Note</label>
                                <textarea type="text" class="form-control form-control-sm" id="note" placeholder="Note" rows="2"></textarea>
                            </div>
                        <!--<button type="submit" name="confirm_update" class="btn btn-primary-red">UPDATE</button>-->
                    </form>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <label class="bg-dark">Ordered Products</label>
                            <table class="table" id="summary_orders">

                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">CLOSE</button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
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

    $(document).ready(function () {
        $('.update_btn').on('click', function () {
            // Get data attributes from the clicked button
            var appointmentId = $(this).data('id');
            var name = $(this).data('name');
            var count = $(this).data('count');
            var time = $(this).data('time');
            var date = $(this).data('date');
            var senior = $(this).data('senior');
            var pwd = $(this).data('pwd');
            var number = $(this).data('number');
            var note = $(this).data('note');

            // Set the values in the modal input fields
            $('#update-id').val(appointmentId);
            $('#name').val(name);
            $('#count').val(count);
            $('#time').val(time);
            $('#date').val(date);
            $('#senior').val(senior);
            $('#pwd').val(pwd);
            $('#number').val(number);
            $('#note').val(note);

            $.ajax({
                url: 'summary_orders.php', // Replace with the actual PHP file to fetch updated modal content
                method: 'GET',
                data: {
                    appointmentId: appointmentId
                },
                success: function(response) {
                    // Update modal content
                    $('#summary_orders').html(response);
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });

            // Show the modal
            $('#editmodal').modal('show');
        });
    });
</script>