<?php 
$a = 1;
include '../conn.php';
include 'admin-auth.php';

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
<body class="hold-transition sidebar-mini layout-fixed" style="background: #191919;">
<div class="wrapper">

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper mt-5" style="background: #191919;">

  <!--<div class="mt-5 d-flex justify-content-end fixed-top fixed-right" role="group">
    <div style="overflow-x:auto;">
      <?php if($row['user_role'] == '1' || $row['user_role'] == '2') { ?>
        <a class="btn bg-yellow" href="#" onclick="jumpToDiv('inventory')">Inventory <i class="bi bi-box2-fill"></i></a>
      <?php } if($row['user_role'] == '1' || $row['user_role'] == '2' || $row['user_role'] == '5') { ?>
      <a class="btn bg-green text-white" href="#" onclick="jumpToDiv('appointment')">Appointment <i class="bi bi-clipboard-fill"></i></a>
      <?php } if ($row['user_role'] == '1') { ?>
      <a class="btn bg-red text-white text-nowrap" href="#" onclick="jumpToDiv('superAdmin')">Super Admin Panel <i class="bi bi-person-fill"></i></a>
      <?php } ?>
    </div>
  </div> -->

  <?php if($row['user_role'] == '1' || $row['user_role'] == '2') { ?>

    <div class="content-header" id="inventory">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="highlight header-colorize text-white mt-4">Inventory System</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">

        <?php
        // Query to retrieve the menu data.
        $query_menu = "SELECT menu_name, menu_availability FROM menus";
        $result_menu = mysqli_query($connection, $query_menu);

        // Initialize counters for menus, activated menus, and deactivated menus.
        $menuCount = 0;
        $activatedCount = 0;
        $deactivatedCount = 0;

        while ($row_menu = mysqli_fetch_assoc($result_menu)) {
            $menuCount++; // Increment the menu count for each menu.
            
            // Check the menu_availability field and update the counts accordingly.
            if ($row_menu['menu_availability'] == 0) {
                $activatedCount++;
            } else {
                $deactivatedCount++;
            }
        }
        ?>
      
          <a href="add-menu.php" class="small-box-footer">
            <div class="small-box bg-redbg text-white">
                <div class="inner">
                  <h4 class="font-weight-bold">Menu Counts</h4>
                    <p>Restaurant menu counts for today.</p>
                    <div style="overflow-x:auto;">
                      <table class="table">
                        <tbody>
                          <tr><td>TOTAL MENUS </td><td><?php echo $menuCount; ?></td></tr>
                          <tr><td>Activated Menus </td><td><?php echo $activatedCount; ?></td></tr>
                          <tr><td>Deactivated Menus </td><td><?php echo $deactivatedCount; ?></td></tr>
                        </tbody>
                      </table>
                    </div>
                </div>
                <div class="icon text-white">
                    <i class="ion ion-android-restaurant"></i>
                </div>
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
          <a href="inventory.php" class="small-box-footer">
            <div class="small-box bg-redbg text-white">
              <div class="inner">
                <h4 class="font-weight-bold">Inventory Items</h4><p> (Low level stocks less than 20)</p>
                  <div style="overflow-x:auto;">
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
                </div>
                  <div class="icon text-white">
                  <i class="ion ion-ios-filing"></i>
                </div>
            </div>
          </a>
        </div>

        <?php
        date_default_timezone_set('Asia/Manila');
        $todayDate = date('Y-m-d');
        $query_inventory_reports = "SELECT inventory.item_name, users.name, users.user_role, log_reports.report_qty, log_reports.date_time, log_reports.action, inventory.unit_of_measure
                                    FROM log_reports
                                    LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                    LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                    WHERE DATE(log_reports.date_time) = '$todayDate'";
        // Execute the query.
        $result_inventory_reports = mysqli_query($connection, $query_inventory_reports);
        ?>
        <div class="col-lg-6">
          <a href="inventory-history.php" class="small-box-footer">
            <div class="small-box bg-redbg text-white">
              <div class="inner" style="overflow-x:auto;">
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
                        if ($row_reports['action'] == 0) {
                          echo "<td>- {$row_reports['report_qty']}{$row_reports['unit_of_measure']}</td>";
                        } else {
                          echo "<td>+ {$row_reports['report_qty']}{$row_reports['unit_of_measure']}</td>";
                        }
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
    </section>

    <?php } if($row['user_role'] == '1' || $row['user_role'] == '2' || $row['user_role'] == '5') { ?>
    <div class="content-header" id="appointment">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="highlight header-colorize text-white mt-4">Appointment System</h1>
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
    <?php } if ($row['user_role'] == '1') { ?>

    <div class="content-header" id="superAdmin">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="highlight header-colorize text-white mt-4">Super Admin Panel</h1>
          </div>
        </div>
      </div>
    </div>

    <?php 
     $survey = "SELECT survey_answer FROM survey";
     $survey_result = $connection->query($survey);
 
     $surveyData = [];
     while ($survey_row = $survey_result->fetch_assoc()) {
         $surveyData[] = $survey_row['survey_answer'];
     }

     ?>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
        <a href="#" class="small-box-footer">
              <div class="small-box bg-redbg text-white">
                <div class="inner">
                <canvas id="surveyChart"></canvas>
                <script>
                    var ctx = document.getElementById('surveyChart').getContext('2d');
                    var surveyData = <?php echo json_encode($surveyData); ?>;
                    
                    var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Array.from({ length: surveyData.length }, (_, i) => i + 1),
                        datasets: [{
                            label: 'Responses when using a tabletop kiosk',
                            data: surveyData,
                            borderColor: 'white',
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                //position: 'bottom',
                                //beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Customers who answered the survey rating',
                                    color: 'white'
                                },
                                ticks: {
                                    display: false,
                                    color: 'white'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                max: 10,
                                title: {
                                    display: true,
                                    text: 'Rating Level',
                                    color: 'white'
                                },
                                ticks: {
                                    color: 'white',
                                    
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Survey Line Graph',
                                color: 'white'
                            },
                        },
                        elements: {
                            title: {
                                color: 'white'
                            },
                            subtitle: {
                                color: 'white'
                            },
                        }
                      }
                   });
              </script>
                </div>
                <div class="icon text-white">
                  <i class="ion ion-arrow-graph-up-right"></i>
                </div>
             </div>
			      </a>
        </div>
      </div>
    </section>
  
      <section class="content">
        <div class="container-fluid">
        <div class="row">

          <div class="col-lg-6">
			      <a href="archived-data.php" class="small-box-footer">
            <div class="small-box bg-redbg text-white">
              <div class="inner">
                <h3>Archived</h3>
                <p>Retrieve Data</p>
              </div>
              <div class="icon text-white">
                <i class="ion ion-android-archive"></i>
              </div>
            </div>
			      </a>
          </div>

          <?php
          $promo = mysqli_query($connection, "SELECT * FROM promo_prices");
          $promo_row= mysqli_fetch_array($promo);

          if (isset($_POST['updatePromo'])) {
            // Get the new promo_price and promoId from the form submission
            $newPrice = $_POST["newPrice"];
            $promoId = $_POST["promoId"];
        
            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }
        
            $updateQuery = "UPDATE promo_prices SET promo_price = ? WHERE promo_id = ?";
            $stmt = mysqli_prepare($connection, $updateQuery);
        
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "di", $newPrice, $promoId);
                if (mysqli_stmt_execute($stmt)) {
                    // Update successful
                    echo '<script type="text/javascript">window.location = "admin-index.php";</script>';;
                    //exit();
                } else {
                    // Update failed
                    echo "Update failed: " . mysqli_error($connection);
                }
                mysqli_stmt_close($stmt);
            } else {
                // Query preparation failed
                echo "Query preparation failed: " . mysqli_error($connection);
            }
        
            mysqli_close($connection);
        } 
          
          ?>
          <div class="col-lg-6">
            <a href="#" class="small-box-footer" onclick="promoUpdate()">
                <div class="small-box bg-redbg text-white">
                    <div class="inner">
                        <h3>Update Promo</h3>
                        <p><?php echo $promo_row['promos'];?> ₱<?php echo number_format($promo_row['promo_price'], 2);?></p>
                    </div>
                    <div class="icon text-white">
                        <i class="ion ion-ios-pricetags"></i>
                    </div>
                </div>
            </a>
          </div>

          <!-- Create the modal -->
          <div class="modal fade" id="updatePromoModal" tabindex="-1" role="dialog" aria-labelledby="updatePromoModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title text-black" id="updatePromoModalLabel">Update Promo Price</h5>
                      </div>
                      <div class="modal-body">
                          <!-- Form to update the promo_price -->
                          <form method="post" action=""> <!-- Assuming you have an "update_promo.php" script to handle the update -->
                              <div class="form-group text-black">
                                  <label for="newPrice">New Promo Price</label>
                                  <input type="number" step="0.01" class="form-control" id="newPrice" name="newPrice" value="<?php echo number_format($promo_row['promo_price'], 2); ?>">
                              </div>
                      </div>
                      <div class="modal-footer">
                        <input type="hidden" name="promoId" value="<?php echo $promo_row['promo_id']; ?>"> <!-- Assuming you have a unique identifier for the promo -->
                        <button type="submit" name="updatePromo" class="btn btn-primary">Update</button>
                        </form>
                        <button class="btn btn-secondary" onclick="closeModal()">Close</button>
                      </div>
                  </div>
              </div>
          </div>

          <div class="col-lg-6">
            <a href="reports.php" class="small-box-footer">
                <div class="small-box bg-redbg text-white">
                    <div class="inner">
                        <h3>Generate Reports</h3>
                        <p>Daily, Weekly, Monthly, and Annual Reports</p>
                    </div>
                    <div class="icon text-white">
                        <i class="ion ion-printer"></i>
                    </div>
                </div>
            </a>
          </div>

          <div class="col-lg-6">
            <a href="activity-logs.php" class="small-box-footer">
                <div class="small-box bg-redbg text-white">
                    <div class="inner">
                        <h3>Activity Log</h3>
                        <p>Monitor Activity Logs</p>
                    </div>
                    <div class="icon text-white">
                        <i class="ion ion-clock"></i>
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