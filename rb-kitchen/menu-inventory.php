<?php 
$a = 5;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
	$result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id'");
	$row = mysqli_fetch_array($result);
    date_default_timezone_set('Asia/Manila');

    // Check if the update form is submitted
    if (isset($_POST["confirm_update"])) {
        // Get the updated data from the form
        $item_id = $_POST['update-id'];
        $item_name = $_POST['update-name'];
        $description = $_POST['update-description'];
        $oum = $_POST['update-measure'];
        $currentStock = $_POST['update-stocks'];
        $additionalStock = $_POST['add-update-stocks'];
        $stock = $currentStock + $additionalStock;
        $status = $_POST['update-status'];

        $currentDateTime = new DateTime();
        $datetimeValue = $currentDateTime->format('Y-m-d H:i:s'); 

        // Perform the UPDATE query to update the inventory record
        $update_query = "UPDATE inventory SET item_name='$item_name', item_desc='$description', unit_of_measure='$oum', stock='$stock', item_status='$status' WHERE item_id='$item_id'";
        $result_update = mysqli_query($connection, $update_query);

        if ($additionalStock != 0) {
        $log_query = "INSERT INTO log_reports (user_roles, report_item_id, report_qty, action, report_user_id, date_time, as_archived) VALUES ('{$row['user_role']}', '$item_id', '$additionalStock', '1', '$id', '$datetimeValue', '0')";
        $result_log = mysqli_query($connection, $log_query);
        }

        if ($result_update) {
            // Redirect back to the inventory.php page after updating
            header('Location: menu-inventory.php');
            exit(); 
        } else {
            // Handle the error if the update fails
            echo "Error updating inventory record: " . mysqli_error($connection);
        }
    }

    if (isset($_POST["activate_btn"])) {
        $status_menu = $_POST["activate_btn"];
    
        // Perform the update query
        $update_query = "UPDATE `menus` SET menu_availability = '0' WHERE menu_id = '$status_menu'";
        mysqli_query($connection, $update_query);

        $delete_query = "DELETE FROM menu_notif WHERE menu_id = '$status_menu'";
        mysqli_query($connection, $delete_query);

        $another_delete_query = "DELETE FROM deact_menu_notif WHERE menu_id = '$status_menu'";
        mysqli_query($connection, $another_delete_query);
    
        // Redirect back to the add-menu.php page after deleting
        header('Location: menu-inventory.php');
        exit();
    }
    
    if (isset($_POST["deactivate_btn"])) {
        $status_menu = $_POST["deactivate_btn"];
        $currentDateTime = date('Y-m-d H:i:s');
    
        // Perform the update query
        $update_query = "UPDATE `menus` SET menu_availability = '1' WHERE menu_id = '$status_menu'";
        mysqli_query($connection, $update_query);

        $insert_query = "INSERT INTO menu_notif(menu_id, date_time) VALUES ('$status_menu', '$currentDateTime')";
        mysqli_query($connection, $insert_query);
    
        // Redirect back to the add-menu.php page after deleting
        header('Location: menu-inventory.php');
        exit();
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
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>
    <div class="content-wrapper bg-black">
        <div class="content p-4">
            <div class="container-fluid text-center p-4">
                <h1>Menu and Inventory</h1>
            </div>

            <!-- Search -->
            <div class="d-flex w-100 justify-content-end p-0">
                <div class="d-flex justify-content-end gap-2">
                    <div class="input-group mb-3 d-flex">
                        <button class="btn " type="button" name="query" disabled><i class="ion ion-ios-search-strong"></i></button>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search Here...">
                    </div>
                </div>
            </div>

            <div style="overflow-x:auto;">
            <table class="table table-hover table-bordered table-dark mt-2">
            <thead>
                <tr><th colspan="3"><h4><strong>Activate/Deactivate Menu</strong></h4></th></tr>
                <tr>
                    <th class="text-center" scope="col">Image</th>
                    <th class="text-center" scope="col">Name</th>
                    <th class="text-center" scope="col">Action</th>
                </tr>
            </thead>
                <tbody id = "menu_table">
                <?php 
                    $view_menus = mysqli_query($connection, "SELECT * FROM menus ORDER BY menu_id DESC");
                    if(mysqli_num_rows($view_menus) > 0) {
                    while ($row = mysqli_fetch_array($view_menus)) { ?>
                    <form method="post" action="menu-inventory.php" enctype="multipart/form-data" onsubmit="rememberScrollPosition()">
                        <tr id="<?php echo $row["menu_id"]; ?>">
                            <td style="display: none"><?php echo $row["menu_id"]; ?></td> <!--hidden-->
                            <td class="text-center w-25"><img src ='../rb-admin/menu-images/<?php echo $row["menu_image"]; ?>' class="img-fluid img-thumbnail custom-image">
                                <?php if($row["menu_availability"] == 1) { ?>
                                    <div class="font-weight-bold"><a class="badge badge-danger">[DEACTIVATED]</a></div>
                                <?php } else if($row["menu_availability"] == 0) { ?>
                                    <div class="font-weight-bold"><a class="badge badge-success">[ACTIVATED]</a></div>
                                <?php } ?>
                             </td>
                            <td class="text-center"><?php echo $row["menu_name"]; ?></td>
                            <td class="text-center">
                                <?php if($row["menu_availability"] == 1) { ?>
                                    <div class="p-2"><button type="submit" class="btn btn-success btn-xs" name="activate_btn" value="<?php echo $row["menu_id"]; ?>">ACTIVATE <i class="bi bi-check-circle-fill"></i></button></div>
                                <?php } else if($row["menu_availability"] == 0) { ?>
                                    <div class="p-2"><button type="submit" class="btn btn-danger btn-xs" name="deactivate_btn" value="<?php echo $row["menu_id"]; ?>">DEACTIVATE <i class="bi bi-x-circle-fill"></i></button></div>
                                <?php } ?>
                            </td>
                        </tr>
                    </form>
                    <?php } } else {?>
                        <tr>
                            <td class="text-center" colspan="3">No record found!</td>
                        </tr>
                    <?php } ?>
                </tbody>  
            </table>
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered table-dark mt-2">
                <thead>
                    <tr><th colspan="6"><h4><strong>Update Inventory</strong></h4></th></tr>
                    <tr>
                        <th class="text-center" scope="col">Item</th>
                        <th class="text-center" scope="col">Description</th>
                        <th class="text-center" scope="col">UOM</th>
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
                            <form method="post" action="menu-inventory.php" enctype="multipart/form-data" onsubmit="rememberScrollPosition()">
                                <tr>
                                    <td style="display: none"><?php echo $row["item_id"]; ?></td>
                                    <td><?php echo $row["item_name"]; ?></td>
                                    <td><?php echo $row["item_desc"]; ?></td>
                                    <td><?php echo $row["unit_of_measure"]; ?></td>
                                    <td><?php echo $row["stock"]; ?></td>
                                    <td><?php if ($row["status_id"] == 0) {?>
                                        <a class="badge badge-success"><?php echo $row["status"];?></a>
                                        <?php } else { ?>
                                        <a class="badge badge-danger"><?php echo $row["status"];?></a>
                                        <?php }?>
                                    </td>
                                    <td><button type="button" class="btn btn-xs btn-primary update_btn" name="update_btn" id="update_btn">UPDATE <i class="bi bi-pencil-square"></i></button></td>
                                </tr>
                            </form>
                        <?php } } else {?>
                            <tr>
                                <td class="text-center" colspan="7">No record found!</td>
                            </tr>
                        <?php } ?>
                    </tbody>  
                </table>
            </div>
            
        </div>
    </div>

    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="menu-inventory.php" method="POST" enctype="multipart/form-data" onsubmit="rememberScrollPosition()">
                    <div class="modal-body">
                        <input type="hidden" name="update-id" id="update-id">

                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" class="form-control" id="update-name" name="update-name" placeholder="Enter Menu Name" required readonly>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <select name="update-description" class="form-control" required readonly>
                                <option value="Meats">Meats</option>
                                <option value="Ingredients">Ingredients</option>
                                <option value="Beverages">Beverages</option>
                                <option value="Sauces">Sauces</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>UOM</label>
                            <select name="update-measure" class="form-control" required readonly>
                                <option value="kg">kg</option>
                                <option value="can">can</option>
                                <option value="pack">pack</option>
                                <option value="box">box</option>
                                <option value="gal">gal</option>
                                <option value="tub">tub</option>
                                <option value="case">case</option>
                            </select>
                        </div>

                        <div class="form-group row">
                            <label>Stocks</label>
                            <div class="col-md-2">
                                <span type="text" class="form-control" id="update-stocks-display"></span>
                                <input type="hidden" name="update-stocks" id="update-stocks">
                            </div>
                            <div class="col-md-10 input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">+</div>
                                </div>
                                <input type="number" class="form-control" name="add-update-stocks" id="add-update-stocks" step="any" min="0" placeholder="Enter Number of Additional Stock" value="0" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="update-status" class="form-control" required readonly>
                                <option value="0">activate</option>
                                <option value="1">deactivate</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">CLOSE</button>
                        <button type="submit" name="confirm_update" class="btn btn-primary">UPDATE</button>
                    </div>
                </form>
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
    $(document).ready(function () {
    $('.update_btn').on('click', function () {
        $('#editmodal').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();
        console.log(data);
        $('#update-id').val(data[0]);
        $('#update-name').val(data[1]);
        $('#update-description').val(data[2]);
        $('#update-measure').val(data[3]);
        // Set the value of the span to the current stock value
        $('#update-stocks-display').text(data[4]);
        $('#update-stocks').val(data[4]);
        $('#update-status').val(data[5]);
    });
    });

    $(document).ready(function(){  
           $('#search').keyup(function(){  
                search_table($(this).val());  
           });  
           function search_table(value){  
                $('#menu_table tr').each(function(){  
                     var found = 'false';  
                     $(this).each(function(){  
                          if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0)  
                          {  
                               found = 'true';  
                          }  
                     });  
                     if(found == 'true')  
                     {  
                          $(this).show();  
                     }  
                     else  
                     {  
                          $(this).hide();  
                     }  
                });  
           }  
      });


    // Get references to both input elements
    const stocksInput = document.getElementById('update-stocks');
    const itemStockInput = document.getElementsByName('item-stock')[0];

    // Add event listener for Stocks input
    stocksInput.addEventListener('input', function() {
        const stocks = parseFloat(stocksInput.value);

        if (isNaN(stocks) || stocks < 0 || stocks === 0) {
            stocksInput.value = '0.1';
        }
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