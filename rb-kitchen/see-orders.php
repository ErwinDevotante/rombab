<?php 
$a = 2;
session_start();
include '../conn.php';
  $id = $_SESSION['user_id'];
	$result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id'");
	$row = mysqli_fetch_array($result);

  date_default_timezone_set('Asia/Manila'); // Set the timezone to Philippine Time


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
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-black">
    <div class="card bg-black">
    <div class="card-header">
        <h3 class="card-title">See Kitchen Orders</h3>
        <div class="card-tools">
        <!-- Maximize Button -->
        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="ion ion-android-expand text-white"></i></button>
        </div>
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
      <div class="card-body" id="see_orders">
        <div class="card-columns-container" id="ordersContainer">
          
        </div>
      </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
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
$(document).ready(function() {
  function updateOrders() {
    $.ajax({
      url: 'fetch-orders.php',
      type: 'GET',
      success: function (data) {
        $('#ordersContainer').html(data);
        // No updateTimers call here
      },
      error: function (xhr, status, error) {
        console.error('Error fetching orders:', error);
      }
    });
  }

  // Uncomment the line below if you want to periodically update orders
  setInterval(updateOrders, 5000);
});
</script>


