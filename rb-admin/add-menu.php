<?php 
$a = 11;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

$msg = " ";
if(isset($_POST["upload"])) {
    // the path to sotre the upload image
    $target = "menu-images/".basename($_FILES['menu-image']['name']);
    // Get all the submitted data from the form
    $image = $_FILES['menu-image']['name'];
    $menu_text = $_POST['menu-text'];
    $category = $_POST['menu-category'];

    $insert = "INSERT INTO menus (menu_image, menu_name, menu_category) VALUES ('$image', '$menu_text', '$category')";
    mysqli_query($connection, $insert);

    if (move_uploaded_file($_FILES['menu-image']['tmp_name'], $target)){
        $msg = "Image uploaded successfully";
    } else {
        $msg = "There was a problem uploading image";
    }

    unset($_POST);
    header('Location: add-menu.php');
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
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" >

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>

    <div class="content-wrapper bg-black">
        <div class="content p-4">
            <div class="container-fluid text-center p-4">
                <h1>Add Menu</h1>
            </div>

            <form method="post" action="add-menu.php" enctype="multipart/form-data">
                <input type="hidden" name="size" value="1000000">
                <div class="form-group">
                    <label>Menu Image</label>
                    <input type="file" class="form-control" name="menu-image" required>
                </div>
                <div class="form-group">
                    <label>Menu Name</label>
                    <input type="text" class="form-control" name="menu-text" placeholder="Enter Menu Name" required>
                </div>
                <div class="form-group">
                    <label>Menu Category</label>
                    <select name="menu-category" class="form-control" id="category" required>
                        <option hidden value="">-----Select Here-----</option>
                        <option value="Samgyupsal">Samgyupsal</option>
                        <option value="Side Dishes">Side Dishes</option>
                        <option value="Others">Others</option>
                        <option value="New Offers">New Offers</option>
                    </select>
                </div>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" name="upload" value="Add Menu">
                </div>
            </form>

            <!-- Search -->
            <div class="d-flex w-100 justify-content-end p-0">
                <div class="d-flex justify-content-end gap-2">
                    <div class="input-group mb-3 d-flex">
                        <button class="btn " type="button" name="query" disabled><i class="ion ion-ios-search-strong"></i></button>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search Here...">
                    </div>
                </div>
            </div>

            <table class="table table-hover table-bordered table-dark mt-2">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Image</th>
                    <th class="text-center" scope="col">Name</th>
                    <th class="text-center" scope="col">Category</th>
                    <th class="text-center" scope="col">Action</th>
                </tr>
            </thead>
                <tbody id = "menu_table">
                <?php 
                    $view_menus = mysqli_query($connection, "SELECT * FROM menus ORDER BY menu_id DESC");
                    while ($row = mysqli_fetch_array($view_menus)) { ?> 
                        <tr>
                            <td class="text-center w-25"><img src ='menu-images/<?php echo $row["menu_image"]; ?>' class="img-fluid img-thumbnail"></td>
                            <td class="text-center"><?php echo $row["menu_name"]; ?></td>
                            <td class="text-center"><?php echo $row["menu_category"]; ?></td>
                            <td class="text-center w-25">
                                <a href="#" class="btn btn-primary">UPDATE</a>
                                <a href="#" class="btn btn-danger">DELETE</a>
                            </td>
                        </tr>
                        <?php
                        } ?>
                </tbody>  
            </table>
        </div>
    </div>
</body>
</html>

<script>
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
</script>