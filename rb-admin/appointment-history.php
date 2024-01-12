<?php 
$a = 7;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

    date_default_timezone_set('Asia/Manila');
    // Get the current date in the Philippines timezone in the format "Y-m-d"
    $currentDateTime = date('Y-m-d H:i:s');

    if (isset($_POST["archive_btn_history"])) {
      $item_id_to_archive_history = $_POST['archive_btn_history'];
  
          // Delete the inventory record
          $update_history_query = "UPDATE appointment_history SET as_archived = '1', archived_at = '$currentDateTime' WHERE history_id = '$item_id_to_archive_history'";
          $result_update_history = mysqli_query($connection, $update_history_query);
  
          if ($result_update_history) {
              // Redirect back to the inventory.php page after archiving
              header('Location: appointment-history.php');
              exit();
          } else {
              // Handle the error if the deletion fails
              echo "Error deleting inventory record: " . mysqli_error($connection);
          }
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Appointment History</title>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<style>
  /* Custom styles for DataTables */
  #sortTable_wrapper .dataTables_length,
  #sortTable_wrapper .dataTables_filter,
  #sortTable_wrapper .dataTables_paginate
  #sortTable_wrapper .dataTables_paginate .paginate_button {
    color: white; /* Set the text color for "Show entries," search, and pagination */
  }
  #sortTable_info {
    color: white; /* Set the text color for "No. of entries" text */
  }
   /* Custom styles for DataTables */
   #sortTable {
    color: white; /* Set the text color for the entire table */
  }

  #sortTable thead th {
    color: white; /* Set the text color for table headers */
  }

  #sortTable tbody td {
    color: white; /* Set the text color for table cells */
  }

  #sortTable_length .dataTables_length select option,
  #sortTable_length .dataTables_length label,
  #sortTable_length .dataTables_length span {
    color: white; /* Set the text color for "Show entries" text inside the drop-down box */
  }

  #sortTable_info,
  #sortTable_length .dataTables_length label,
  #sortTable_filter input[type="search"] {
    color: white; /* Set the text color for "No. of entries" text and search input */
  }

  #sortTable_wrapper .dataTables_paginate .paginate_button {
    color: white; /* Set the text color for pagination buttons */
    background-color: transparent; /* Optional: Set the background-color of pagination buttons to transparent */
  }
  
  #sortTable_length {
        display: none;
    }

  /* Custom styles for DataTables search input */
  .dataTables_filter input {
    width: 200px; /* Set the desired width */
    height: 30px;
    }
</style>
<body class="hold-transition sidebar-mini layout-fixed bg-black">
    <div class="wrapper" >

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>

    <div class="content-wrapper bg-black mt-5">
        <div class="content p-4">
            <div class="container-fluid text-center p-4">
                <h1>Appointment History</h1>
            </div>
            <div style="overflow-x:auto;">
              <table class="table table-hover table-striped table-dark mt-5 text-white" id="sortTable">
              <thead>
                  <tr>
                      <th class="text-center" scope="col">Name</th>
                      <th class="text-center" scope="col">Description</th>
                      <th class="text-center" scope="col">Table No</th>
                      <th class="text-center" scope="col">Count</th>
                      <th class="text-center" scope="col">SN / PWD / Bday</th>
                      <th class="text-center" scope="col">Date</th>
                      <th class="text-center" scope="col">In</th>
                      <th class="text-center" scope="col">Out</th>
                      <th class="text-center" scope="col">Note</th>
                      <th class="text-center" scope="col">Action</th>
                  </tr>
              </thead>
                  <tbody>
                  <?php 
                      $result_tb = mysqli_query($connection, "SELECT * FROM appointment_history
                      INNER JOIN users ON users.user_id=appointment_history.table_history_id
                      INNER JOIN appointment ON appointment.appointment_id=appointment_history.appointment_user_id
                      WHERE appointment.appointment_session = '2' AND as_archived = '0'");
                      if(mysqli_num_rows($result_tb) > 0) {
                      while ($row = mysqli_fetch_array($result_tb)) { ?> 
                          <tr>
                              <td class="text-center"><?php echo $row["appointment_name"]; ?></td>
                              <td class="text-center"><?php echo $row["appointment_desc"]; ?></td>
                              <td class="text-center"><?php echo $row["name"]; ?></td>
                              <td class="text-center"><?php echo $row["count"]; ?></td>
                              <td class="text-center"><?php echo $row["senior_no"]; ?> / <?php echo $row["pwd_no"]; ?> / <?php echo $row["bday_no"]; ?></td>
                              <td class="text-center"><?php $formattedDate = date('F j, Y', strtotime($row["date"])); 
                                                      echo $formattedDate;?></td>
                              <td class="text-center"><?php $formattedDateTime = date('g:i A', strtotime($row["time"])); 
                                                      echo $formattedDateTime;?></td>
                              <td class="text-center"><?php $formattedDateTimeout = date('g:i A', strtotime($row["time-out"])); 
                                                      echo $formattedDateTimeout;?></td>
                              <td><?php echo $row["note"]; ?></td>
                              <td>
                                <form method="POST" action="appointment-history.php" enctype="multipart/form-data" onsubmit="rememberScrollPosition()">
                                  <button type="submit" class="btn btn-xs btn-warning" name="archive_btn_history" value="<?php echo $row["history_id"]; ?>">ARCHIVE <i class="bi bi-archive"></i></button>
                                </form>
                              </td>
                          </tr>
                          <?php 
                      } } else { ?>
                          <tr>
                              <td class="text-center" colspan="10">No record found!</td>
                          </tr>
                      <?php } ?>
                  </tbody>  
              </table>
            </div>
        
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
     $(document).ready(function() {
    // Initialize DataTable for the table element with class "table"
    $('#sortTable').DataTable({
      order: [[5, 'desc']]
    });
    });

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
</script>

