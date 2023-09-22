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
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-white">Kitchen System</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row"> 
        
        <?php
          date_default_timezone_set('Asia/Manila');
          $todayDate = date('Y-m-d');
          // Query to retrieve the count of today's served orders for the specific user.
          $query_served_orders_count = "SELECT COUNT(*) AS served_order_count FROM `orders`
                                        LEFT JOIN `users` ON orders.user_table = users.user_id
                                        WHERE DATE(orders.time_date) = '$todayDate'
                                        AND orders.status = '1'";

          // Execute the query.
          $result_served_orders_count = mysqli_query($connection, $query_served_orders_count);
          ?>
          <div class="col-lg-6">
            <a href="order-history.php" class="small-box-footer">
              <div class="small-box bg-redbg text-white">
                <div class="inner">
                  <h4 class="font-weight-bold text-center">Served Orders</h4>
                  <p class="fs-6 text-center">Total of served order for <?php echo $todayDate; ?>.</p>
                  <?php
                    // Check if the query was successful.
                    if ($result_served_orders_count) {
                      // Fetch the result as an associative array.
                      $row_count = mysqli_fetch_assoc($result_served_orders_count);

                      // Get the served order count.
                      $servedOrderCount = $row_count['served_order_count'];

                      // Display the served order count inside the small-box.
                      echo "<h1 class='text-center font-weight-bold'>$servedOrderCount</h1>";
                    } else {
                        // Handle the case where the query fails.
                        echo "<p>Error retrieving served orders count.</p>";
                    }
                  ?>
                  </table>
                </div>
                <div class="icon text-white">
                  <i class="ion ion-clipboard"></i>
                </div>
              </div>
            </a>
          </div>

          <?php
          // Query to retrieve the serve_time for today's served orders for the specific user.
          $query_serve_time = "SELECT AVG(orders.serve_time) AS avg_serve_time
                              FROM `orders`
                              LEFT JOIN `users` ON orders.user_table = users.user_id
                              WHERE DATE(orders.time_date) = '$todayDate'";

          // Execute the query.
          $result_serve_time = mysqli_query($connection, $query_serve_time);
          ?>

          <div class="col-lg-6">
              <a href="order-history.php" class="small-box-footer">
                  <div class="small-box bg-redbg text-white">
                      <div class="inner"> 
                          <h4 class="font-weight-bold text-center">Serving Time</h4>
                          <p class="fs-6 text-center">Average serving time for <?php echo $todayDate; ?>.</p>
                          <?php
                          // Check if the query was successful.
                          if ($result_serve_time) {
                              // Fetch the result as an associative array.
                              $row_serve_time = mysqli_fetch_assoc($result_serve_time);

                              // Get the average serve time.
                              $averageServeTime = $row_serve_time['avg_serve_time'];

                              // Display the average serve time inside the small-box.
                              echo "<h1 class='text-center font-weight-bold'>" . round($averageServeTime, 2) . " second/s</h1>";
                          } else {
                              // Handle the case where the query fails.
                              echo "<p>Error retrieving average serve time.</p>";
                          }
                          ?>
                      </div>
                      <div class="icon text-white">
                          <i class="ion ion-android-time"></i>
                      </div>
                  </div>
              </a>
          </div>

          <?php 
          $query_inventory_item = "SELECT item_name, stock FROM inventory WHERE stock < 20"; // Modify this query to select the required columns.
          // Execute the query.
          $result_inventory_item = mysqli_query($connection, $query_inventory_item);

          // Fetch the item data (item_name and stock) from the query result.
          $itemData = array();
          while ($row_item = mysqli_fetch_assoc($result_inventory_item)) {
              $itemData[] = $row_item;
          }
          ?>
          <div class="col-lg-6">
            <a href="log-reports.php" class="small-box-footer">
              <div class="small-box bg-redbg text-white">
                <div class="inner">
                  <h4 class="font-weight-bold">Inventory Items</h4><p> (Low level stocks less than 20)</p>
                    <table class="table">
                      <?php
                        // Loop through the item data and display item_name and stock in table rows.
                      if(mysqli_num_rows($result_inventory_item) > 0) {
                        foreach ($itemData as $item) {
                          if ($item['stock'] <= 10) {
                            echo "<tr><td><span class='badge badge-pill badge-warning text-uppercase'>[WARNING] {$item['item_name']}</span></td>";
                            echo "<td> <span class='badge badge-pill badge-warning text-uppercase'>Stock: {$item['stock']}</span></td></tr>";
                          } else {
                          echo "<tr><td>{$item['item_name']}</td>";
                          echo "<td>Stock: {$item['stock']}</td></tr>";
                          }
                        }
                      } else {
                        echo "<tr><td class='text-center' colspan='2'>All stocks are in good levels.</td></tr>";
                      }
                      ?>
                    </table>
                  </div>
                    <div class="icon text-white">
                    <i class="ion ion-ios-filing"></i>
                  </div>
              </div>
            </a>
          </div>

          <?php
          $query_inventory_reports = "SELECT inventory.item_name, users.name, log_reports.report_qty, log_reports.date_time
                                      FROM log_reports
                                      LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                      LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                      WHERE DATE(log_reports.date_time) = '$todayDate' AND users.user_id = '$id'";
          // Execute the query.
          $result_inventory_reports = mysqli_query($connection, $query_inventory_reports);
          ?>
          <div class="col-lg-6">
            <a href="log-reports.php" class="small-box-footer">
              <div class="small-box bg-redbg text-white">
                <div class="inner">
                  <h4 class="font-weight-bold">Inventory Reports</h4>
                  <p>Reports for <?php echo $todayDate;?>.</p>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Item</th>
                        <th>User</th>
                        <th>Qty</th>
                        <th>Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if(mysqli_num_rows($result_inventory_reports) > 0) {
                        while ($row_reports = mysqli_fetch_assoc($result_inventory_reports)) {
                          echo "<tr>";
                          echo "<td>{$row_reports['item_name']}</td>";
                          echo "<td>{$row_reports['name']}</td>";
                          echo "<td>{$row_reports['report_qty']}</td>";
                          // Format date_time in 12-hour time format
                          $formattedTime = date('h:i A', strtotime($row_reports['date_time']));
                          echo "<td>{$formattedTime}</td>";
                          echo "</tr>";
                        }
                      }
                      else {
                        echo "<tr><td class='text-center' colspan='4'>No reports for today.</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <div class="icon text-white">
                  <i class="ion ion-document-text"></i>
                </div>
              </div>
            </a>
          </div>

        </div>
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