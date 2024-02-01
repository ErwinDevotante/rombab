<?php 
$a = 1;
include '../conn.php';
include 'auth.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Dashboard</title>
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
    <!-- Chart.js -->
    <script src="../node_modules/chart.js"></script>
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
    
</head>
<body class="hold-transition sidebar-mini layout-fixed bg-black">
<div class="wrapper">

    <?php 
    include "topbar.php";
    include "sidebar.php"; 
    ?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-black mt-5">

    <div class="content-header" id="appointment">
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

        <?php 
        // Query to retrieve the menu data.
        $query_table = "SELECT * FROM users WHERE user_role = '4'";
        $result_table = mysqli_query($connection, $query_table);

        // Initialize counters.
        $tableCount = 0;
        $activatedCount = 0;
        $deactivatedCount = 0;
        $not_availableCount = 0;
        $occupiedCount = 0;
        $tableNamesNotAvailable = ''; // Initialize the table names string.
        $tableNamesActivate = '';
        $tableNamesDeactivate = '';
        $tableNamesOccupied = '';


        while ($row_table = mysqli_fetch_assoc($result_table)) {
            $tableCount++; // Increment the menu count for each menu.
            
            // Check the session_tb field and update the counts accordingly.
            if ($row_table['session_tb'] == 0) {
                $not_availableCount++;
                if ($not_availableCount > 1) {
                    $tableNamesNotAvailable .= ", "; // Add a comma if there are multiple not available tables.
                }
                $tableNamesNotAvailable .= $row_table['name']; // Add the table name.
            } else if ($row_table['session_tb'] == 1) {
                $activatedCount++;
                if ($activatedCount > 1) {
                  $tableNamesActivate .= ", ";
                }
                $tableNamesActivate .= $row_table['name'];
            } else if ($row_table['session_tb'] == 2) {
                $deactivatedCount++;
                if ($deactivatedCount > 1) {
                  $tableNamesDeactivate .= ", ";
                }
                $tableNamesDeactivate .= $row_table['name'];
            } else if ($row_table['session_tb'] == 3) {
                $occupiedCount++;
                if ($occupiedCount > 1) {
                  $tableNamesOccupied .= ", ";
                }
                $tableNamesOccupied .= $row_table['name'];
            } 
        }

        
        ?>

			      <a href="manage-appointment.php" class="small-box-footer">
              <div class="small-box bg-redbg text-white">
                <div class="inner">
                 <h5 class="font-weight-bold">Table Availability</h5>
                  <p>Check table availability</p>
                  <p>Total no. of tables: <?php echo $tableCount; ?></p>
                    <div style="overflow-x:auto;">
                      <table class="table ">
                        <thead>
                          <tr>
                            <th scope="col">Status</th>
                            <th scope="col">No.</th>
                            <th scope="col">Table Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Activate</td>
                            <td><?php echo $activatedCount; ?></td>
                              <?php 
                              if($activatedCount === 0) { ?>
                              <td>There is/are no activated table.</td>
                              <?php } else { ?>
                                <td><span class='badge badge-pill badge-success text-black text-uppercase'><?php echo $tableNamesActivate; ?></span></td>
                              <?php } ?>
                          </tr>
                          <tr>
                            <td>Deactivate</td>
                            <td><?php echo $deactivatedCount; ?></td>
                              <?php 
                              if($deactivatedCount === 0) { ?>
                              <td>There is/are no deactivated table.</td>
                              <?php } else { ?>
                                <td><span class='badge badge-pill badge-danger text-black text-uppercase'><?php echo $tableNamesDeactivate; ?></span></td>
                              <?php } ?>
                          </tr>
                          <tr>
                            <td>Not Available</td>
                            <td><?php echo $not_availableCount; ?></td>
                              <?php 
                              if($not_availableCount === 0) { ?>
                              <td>There is/are available table.</td>
                              <?php } else { ?>
                                <td><span class='badge badge-pill badge-warning text-black text-uppercase'><?php echo $tableNamesNotAvailable; ?></span></td>
                              <?php } ?>
                          </tr>
                          <tr>
                            <td>Occupied</td>
                            <td><?php echo $occupiedCount; ?></td>
                              <?php 
                                if($occupiedCount === 0) { ?>
                                <td>There is/are occupied table.</td>
                                <?php } else { ?>
                                  <td><span class='badge badge-pill badge-info text-black text-uppercase'><?php echo $tableNamesOccupied; ?></span></td>
                                <?php } ?>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                </div>
                <div class="icon text-white">
                  <i class="ion ion-clipboard"></i>
                </div>
             </div>
			      </a>
        </div>
    </section>

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
  function promoUpdate() {
    // Show the dialog
    $('#updatePromoModal').modal('show');
    }

  function closeModal(){
    $('#updatePromoModal').modal('hide');
  }

  function jumpToDiv(divId) {
    var element = document.getElementById(divId);
      if (element) {
         element.scrollIntoView({ behavior: 'smooth' });
      }
  }
</script>