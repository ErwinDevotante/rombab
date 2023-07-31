<?php 
$a = 1;
session_start();
include '../conn.php';
  $id = $_SESSION['user_id'];
	$result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id'");
	$row = mysqli_fetch_array($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Admin Dashboard</title>
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
<div class="wrapper">

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-black">
  <?php if($row['user_role'] == '1' || $row['user_role'] == '2') { ?>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-white">Inventory System</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          
        <div class="col-lg-3 col-6">
			    <a href="inventory.php" class="small-box-footer">
            <div class="small-box bg-red">
              <div class="inner">
                <h3>Inventory</h3> <h4 class="font-weight-bold">Items</h4>
                <p>Managing Inventory</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-filing"></i>
              </div>
             </div>
			    </a>
        </div>

        <div class="col-lg-3 col-6">
			    <a href="inventory-history.php" class="small-box-footer">
            <div class="small-box bg-red">
              <div class="inner">
                <h4 class="font-weight-bold">Inventory</h4>
                <h3>Reports</h3><p>Check Reports</p>
              </div>
              <div class="icon">
                <i class="ion ion-document-text"></i>
              </div>
            </div>
			    </a>
        </div>

        <div class="col-lg-3 col-6">
			    <a href="add-menu.php" class="small-box-footer">
            <div class="small-box bg-red">
              <div class="inner">
                <h4 class="font-weight-bold">Add</h4>
                <h3>Menu</h3><p>Add Restaurant Menu</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-restaurant"></i>
              </div>
            </div>
			    </a>
        </div>
    
        </div>
      </div>
    </section>
    <?php } if($row['user_role'] == '1' || $row['user_role'] == '2' || $row['user_role'] == '5') { ?>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-white">Appointment System</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
       
          <div class="col-lg-3 col-6">
			      <a href="manage-appointment.php" class="small-box-footer">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>Manage</h3><h5 class="font-weight-bold">Appointment</h5>
                  <p>Managing tables</p>
                </div>
                <div class="icon">
                  <i class="ion ion-clipboard"></i>
                </div>
             </div>
			      </a>
          </div>
          <div class="col-lg-3 col-6">
			      <a href="online-appointment.php" class="small-box-footer">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>Online</h3><h5 class="font-weight-bold">Appointment</h5>
                  <p>Create for online appointment</p>
                </div>
                <div class="icon">
                  <i class="ion ion-mouse"></i>
                </div>
             </div>
			      </a>
          </div>

          <div class="col-lg-3 col-6">
			      <a href="create-walkin-appointment.php" class="small-box-footer">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>Walk-in</h3><h5 class="font-weight-bold">Appointment</h5>
                  <p>Create for walk-in appointment</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-walk"></i>
                </div>
             </div>
			      </a>
          </div>

          <div class="col-lg-3 col-6">
			      <a href="appointment-history.php" class="small-box-footer">
              <div class="small-box bg-red">
                <div class="inner">
                <h5 class="font-weight-bold">Appointment</h5><h3>History</h3>
                  <p>View Appoinment History</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-box"></i>
                </div>
              </div>
			      </a>
          </div>

        </div>
    </section>
    <?php } ?>

      <?php
      if ($row['user_role'] == '1') { ?>
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0 text-white">Add Account</h1>
              </div>
            </div>
          </div>
      </div>
  
      <section class="content">
        <div class="container-fluid">
        <div class="row">

          <div class="col-lg-3 col-6">
			      <a href="add-account.php" class="small-box-footer">
            <div class="small-box bg-red">
              <div class="inner">
                <h3>Table</h3>
                <p>Add Account</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
            </div>
			      </a>
          </div>

        </div>
        </div>
      </section>
    <?php } ?>

  </div>
</div>

</body>
</html>