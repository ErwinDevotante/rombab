<?php 
$a = 2;
session_start();
include '../conn.php';
  $id = $_SESSION['user_id'];
	$result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id'");
	$row = mysqli_fetch_array($result);

  // Page refresh after 5 seconds
  $refreshAfterSeconds = 5;
  // The URL of the page you want to refresh (current page in this case)
  $refreshUrl = $_SERVER['PHP_SELF'];
  // Set the "Location" header to refresh after the specified seconds
  //header("Refresh: $refreshAfterSeconds; URL=$refreshUrl");

  if(isset($_POST['serve_order'])) {
    $update_id = $_POST['update_id'];
    $update_query = mysqli_query($connection, "UPDATE `orders` SET status = '1' WHERE order_id = '$update_id'");
        if($update_query){
           header('location:see-orders.php');
        };
    }

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
    <div class="card-body">
        <div class="card-columns-container">
            <?php 

                    function calculateElapsedTime($timeDate) {
                      date_default_timezone_set('Asia/Manila'); // Set the timezone to Philippine Time
                      $targetDateTime = new DateTime($timeDate);
                      $currentTime = new DateTime(); // Current time
                      $interval = $currentTime->diff($targetDateTime);
                      $elapsedSeconds = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
                      return $elapsedSeconds;
                    }

                    $result_tb = mysqli_query($connection, "SELECT * FROM `orders`
                                              LEFT JOIN `users` ON orders.user_table = users.user_id
                                              WHERE status = 0");
                        if(mysqli_num_rows($result_tb) > 0){
                        while ($row = mysqli_fetch_array($result_tb)) { 
                          $skillsArray = explode(",", $row['total_products']);
                          //$elapsedSeconds = calculateElapsedTime($row['time_date']);
                          ?>
                            <form action="" method="post">
                                <div class="card p-2 mb-2 card-red text-black">
                                <input type="hidden" name="update_id" value="<?php echo $row["order_id"]; ?>" >
                                <h2 class="card-title mb-3"><?php echo $row["name"]; ?></h2>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $row["time_date"]; ?></h6>
                                <?php foreach ($skillsArray as $skill) {
                                  echo "<p class='card-text m-0'>" . trim($skill) . "</p>";
                                  echo "<hr class='bg-dark m-1'>";
                                } ?>
                              <p class="card-text m-0">Elapsed time: <span id="timer_<?php echo $row['order_id']; ?>"></span> seconds</p>
                                <input type="submit" class="btn btn-md btn-outline-danger w-100 mt-1" value="SERVE ORDER" name="serve_order">
                                </div>
                            </form>
                        <?php }
                        } else { ?>
                          <div>
                            <p>No available orders!</p>
                          </div>
                        <?php }  ?>
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
        // JavaScript code to update the timers in real-time
        function updateTimers() {
            <?php 
            $result_tb = mysqli_query($connection, "SELECT * FROM `orders` ");
            if(mysqli_num_rows($result_tb) > 0){
                while ($row = mysqli_fetch_array($result_tb)) { 
            ?>
                var timerElement_<?php echo $row['order_id']; ?> = document.getElementById('timer_<?php echo $row['order_id']; ?>');
                var timeDate_<?php echo $row['order_id']; ?> = '<?php echo $row['time_date']; ?>';
                var elapsedSeconds_<?php echo $row['order_id']; ?> = <?php echo calculateElapsedTime($row['time_date']); ?>;

                setInterval(function() {
                    var currentTime = new Date().getTime();
                    var targetTime = new Date(timeDate_<?php echo $row['order_id']; ?>).getTime();
                    var diffSeconds = Math.floor((currentTime - targetTime) / 1000);
                    var elapsedSeconds = elapsedSeconds_<?php echo $row['order_id']; ?> + diffSeconds;
                    timerElement_<?php echo $row['order_id']; ?>.innerText = elapsedSeconds;
                }, 1000);
            <?php 
                } 
            }
            ?>
        }

        // Call the function to update the timers in real-time
        updateTimers();
    </script>