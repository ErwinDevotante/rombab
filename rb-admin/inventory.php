<?php 
$a = 2;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

    if (isset($_POST["upload_item"])) {
        // Get all the submitted data from the form
        $item_name = $_POST['item-name'];
        $description = $_POST['item-description'];
        $oum = $_POST['item-measure'];
        $stock = $_POST['item-stock'];
        $status = $_POST['item-status'];
    
        // Check for redundant data before inserting
        $check_query = "SELECT * FROM inventory WHERE item_name = '$item_name' AND item_desc = '$description'";
        $result = mysqli_query($connection, $check_query);
    
        if (mysqli_num_rows($result) > 0) {
            // Redundant data found, show an error message
            echo 'alert(Menu item with the same name and category already exists.)';
        } else {
            // No redundant data, proceed with insertion
            $insert_query = "INSERT INTO inventory (item_name, item_desc, unit_of_measure, stock, item_status) 
                            VALUES ('$item_name', '$description', '$oum', '$stock', '$status')";
            $result_insert = mysqli_query($connection, $insert_query);
    
            // Redirect back to the add-menu.php page after inserting
            header('Location: inventory.php');
            exit();
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Inventory</title>
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
                <h1>Inventory</h1>
            </div>

            <form method="post" action="inventory.php" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col">
                        <label>Item Name</label>
                        <input type="text" class="form-control" name="item-name" placeholder="Enter Item Name" required>
                    </div>

                    <div class="form-group col">
                        <label>Description</label>
                        <select name="item-description" class="form-control" required>
                            <option hidden value="">-----Select Here-----</option>
                            <option value="Meats">Meats</option>
                            <option value="Ingredients">Ingredients</option>
                            <option value="Beverages">Beverages</option>
                            <option value="Sauces">Sauces</option>
                        </select>
                    </div>
                    
                    <div class="form-group col">
                        <label>UOM</label>
                        <select name="item-measure" class="form-control" required>
                            <option hidden value="">-----Select Here-----</option>
                            <option value="kg">kg</option>
                            <option value="can">can</option>
                            <option value="pack">pack</option>
                            <option value="box">box</option>
                            <option value="gal">gal</option>
                            <option value="tub">tub</option>
                            <option value="case">case</option>
                        </select>
                    </div>

                    <div class="form-group col">
                        <label>Stocks</label>
                        <input type="number" class="form-control" name="item-stock" step="any" placeholder="Enter Number of Stock" required>
                    </div>

                    <div class="form-group col">
                        <label>Status</label>
                        <select name="item-status" class="form-control" required>
                            <option value="0">Activated</option>
                            <option value="1">Deactivated</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <input class="btn btn-primary" type="submit" name="upload_item" value="ADD ITEM">
                </div>
            </form>

            <table class="table table-hover table-bordered table-dark mt-2" id="sortTable">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Item</th>
                    <th class="text-center" scope="col">Description</th>
                    <th class="text-center" scope="col">OUM</th>
                    <th class="text-center" scope="col">Stocks</th>
                    <th class="text-center" scope="col">Status</th>
                    <th class="text-center" scope="col">Action</th>
                </tr>
            </thead>
                <tbody id = "menu_table">
                <?php 
                    $view_items = mysqli_query($connection, "SELECT * FROM inventory
                                                INNER JOIN statuses ON statuses.status_id = inventory.item_status
                                                ORDER BY item_id DESC");
                    if(mysqli_num_rows($view_items) > 0) {
                    while ($row = mysqli_fetch_array($view_items)) { ?>
                        <form method="post" action="inventory.php" enctype="multipart/form-data">
                            <tr>
                                <td><?php echo $row["item_name"]; ?></td>
                                <td><?php echo $row["item_desc"]; ?></td>
                                <td><?php echo $row["unit_of_measure"]; ?></td>
                                <td><?php echo $row["stock"]; ?></td>
                                <td><?php echo $row["status"]; ?></td>
                                <td  class="w-25">
                                    <div class="row text-center">
                                        <div class="col"><button type="button" class="btn btn-primary update_btn" id="update_btn">UPDATE</button></div>
                                        <div class="col"><button type="submit" class="btn btn-warning" name="delete_btn" value="<?php echo $row["item_id"]; ?>">DELETE</button></div> 
                                    </div>
                                </td>
                            </tr>
                        </form>
                    <?php } } else {?>
                        <tr>
                            <td class="text-center" colspan="6">No record found!</td>
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
      order: [[1, 'desc']]
    });
    });
</script>