<?php 
$a = 6;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

    if(isset($_POST["submit"])){
        $name = $_POST["customer"];
        $pax = $_POST["pax"];
        $note = $_POST["note"];
        $date = date('Y-m-d');
        $time = date('H:i:s');
    
        $query = "INSERT INTO appointment VALUES('','$name', NULL ,'$pax','$date','$time', '$note', NULL)";
        $result_add = mysqli_query($connection, $query);

        if ($result_add){
            echo
            "<script> alert('Registration Successful'); </script>";
        }
        unset($_POST);
        header('Location: create-walkin-appointment.php');
    }
    //important!
    

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
        <h1>Walk-in Appointment</h1>
    </div>

    <section class="home-section">

    <form action="" method="post">
    <div class="form-group">
        <label>Customer's Name</label>
        <input type="text" class="form-control" id="customer" name="customer" placeholder="Enter name" required>
    </div>
    <div class="form-group">
        <label>No of people on the table</label>
        <input type="number" class="form-control" id="pax" name="pax" placeholder="Enter no of people" required>
    </div>
    <div class="form-group">
        <label>Note</label>
        <textarea type="text" class="form-control" id="note" name="note" placeholder="Enter note" rows="2"></textarea>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Submit</button>

    
        <table class="table table-hover table-bordered table-dark mt-5">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Name</th>
                    <th class="text-center" scope="col">Table No</th>
                    <th class="text-center" scope="col"># of People</th>
                    <th class="text-center" scope="col">Date</th>
                    <th class="text-center" scope="col">Time</th>
                    <th class="text-center" scope="col">Note</th>
                </tr>
            </thead>
                <tbody>
                <?php 
                    $result_tb = mysqli_query($connection, "SELECT * FROM appointment where table_id IS NULL");
                    while ($row = mysqli_fetch_array($result_tb)) { ?> 
                        <tr>
                            <td class="text-center"><?php echo $row["appointment_name"]; ?></td>
                            <td class="text-center"><?php echo $row["table_id"]; ?></td>
                            <td class="text-center"><?php echo $row["count"]; ?></td>
                            <td class="text-center"><?php echo $row["date"]; ?></td>
                            <td class="text-center"><?php echo $row["time"]; ?></td>
                            <td><?php echo $row["note"]; ?></td>
                        </tr>
                        <?php 
                    } 
                    ?>
                </tbody>  
        </table>
    </form>
    </section>
    </div>
    </div>
</div>

</body>
</html>