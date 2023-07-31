<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="/assets/rombab-logo.png">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../../style.css">
    <!-- JQuery -->
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://kit.fontawesome.com/fe96d845ef.js" crossorigin="anonymous"></script>
</head>
<body class="bg-black">

    <!-- Image and text -->
	<?php 
    include '../../conn.php';
    include 'navbar.php';

    if (isset($_POST['add_to_cart'])) {
        $product_name = $_POST['product_name'];
        $product_image = $_POST['product_image'];
        $product_quantity = 1;
        $product_table= $row["user_id"];
    
        $select_cart = mysqli_query($connection, "SELECT * FROM `cart` WHERE cart_name = '$product_name' AND cart_table = '$table'");
    
        if(mysqli_num_rows($select_cart) > 0) {
            echo '<script type="text/javascript">'; 
            //unset($_POST);
            echo 'alert("Product Already Added!");'; echo 'window.location.href = "side-dishes.php";';
            echo '</script>';
        } else {
            $insert_product = mysqli_query($connection, "INSERT INTO `cart`(cart_table, cart_name, cart_image, cart_quantity) VALUES ('$product_table', '$product_name', '$product_image', '$product_quantity')");
            echo '<script type="text/javascript">'; 
            echo 'alert("Product Added Successfully!");'; echo 'window.location.href = "samgyupsal.php";';
            echo '</script>';
            unset($_POST);
        }
    }

    ?>

    <div class="container-fluid text-center p-1 text-white">
        <h1>SIDE DISHES MENU</h1>
    </div>

    <div class="container py-5">
        <div class="card-columns-container">
                <?php 
                    $result_tb = mysqli_query($connection, "SELECT * FROM `menus`
                                            WHERE menu_category = 'Side Dishes' and menu_availability = '0'");
                        if(mysqli_num_rows($result_tb) > 0){
                        while ($row = mysqli_fetch_array($result_tb)) { ?> 
                        <form action="" method="post">
                            <div class="card p-2 mb-2 card-red text-center">
                                <div class="img"><img class="img-fluid rounded-top custom-image" alt="Responsive Image" src ='../../rb-admin/menu-images/<?php echo $row["menu_image"]; ?>'></div>
                                <div class="productname bg-white text-black rounded-bottom"><h5 class="text-truncate text-uppercase"><?php echo $row["menu_name"]; ?></h5></div>
                                <input type="hidden" name="product_image" value="<?php echo $row["menu_image"]; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $row["menu_name"]; ?>">
                                <input type="submit" class="btn btn-md btn-outline-danger text-white w-100 mt-1" value="ADD TO ORDER" name="add_to_cart">
                            </div>
                        </form>
                        <?php }
                        }  
                ?>
        </div>
    </div>

</body>
</html>