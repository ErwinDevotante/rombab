<?php 
$a = 7;
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
    <!-- Bootstrap CSS-->
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../../node_modules/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../node_modules/admin-lte/js/adminlte.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
</style>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" >

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>

    <div class="content-wrapper bg-black">
        <div class="content p-4">
            <div class="container-fluid text-center p-4">
                <h1>Appointment History</h1>
            </div>
            <table class="table table-hover table-dark mt-5 text-white" id="sortTable">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Name</th>
                    <th class="text-center" scope="col">Table No</th>
                    <th class="text-center" scope="col"># of People</th>
                    <th class="text-center" scope="col">Date</th>
                    <th class="text-center" scope="col">Time-in</th>
                    <th class="text-center" scope="col">Time-out</th>
                    <th class="text-center" scope="col">Note</th>
                </tr>
            </thead>
                <tbody>
                <?php 
                    $result_tb = mysqli_query($connection, "SELECT * FROM appointment_history
                    INNER JOIN users ON users.user_id=appointment_history.table_history_id
                    INNER JOIN appointment ON appointment.appointment_id=appointment_history.appointment_user_id
                    WHERE appointment.appointment_session = '2'");
                    if(mysqli_num_rows($result_tb) > 0) {
                    while ($row = mysqli_fetch_array($result_tb)) { ?> 
                        <tr>
                            <td class="text-center"><?php echo $row["appointment_name"]; ?></td>
                            <td class="text-center"><?php echo $row["name"]; ?></td>
                            <td class="text-center"><?php echo $row["count"]; ?></td>
                            <td class="text-center"><?php echo $row["date"]; ?></td>
                            <td class="text-center"><?php echo $row["time"]; ?></td>
                            <td class="text-center"><?php echo $row["time-out"]; ?></td>
                            <td class="w-25"><?php echo $row["note"]; ?></td>
                        </tr>
                        <?php 
                    } } else { ?>
                        <tr>
                            <td class="text-center" colspan="7">No record found!</td>
                        </tr>
                    <?php } ?>
                </tbody>  
        </table>
        
        </div>
    </div>
    </div>
</body>
</html>

<script>
     $(document).ready(function() {
    // Initialize DataTable for the table element with class "table"
    $('#sortTable').DataTable({
      order: [[3, 'desc']]
    });
    });
</script>

