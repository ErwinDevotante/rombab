<?php 
include '../../conn.php';
include '../table-auth.php';

if(isset($_POST['update_update_btn'])){
    $update_value = $_POST['update_quantity'];
    $update_id = $_POST['update_quantity_id'];
    $update_quantity_query = mysqli_query($connection, "UPDATE `cart` SET cart_quantity = '$update_value' WHERE cart_id = '$update_id'");
    if($update_quantity_query){
       header('location:cart.php');
    };
 };
 
 if(isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    mysqli_query($connection, "DELETE FROM `cart` WHERE cart_id = '$remove_id'");
    header('location:cart.php');
 };
 
 if(isset($_GET['delete_all'])){
    mysqli_query($connection, "DELETE FROM `cart`");
    header('location:cart.php');
 }

?>
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
</head>
<body class="bg-black">

    <!-- Image and text -->
	<?php include 'navbar.php';?>

    <div class="container-fluid text-center p-1 text-white">
        <h1>Check-out</h1>
    </div>

    <div class="container py-5 text-white">
    <table class="table table-hover table-bordered table-dark mt-5">
        <thead>
        <th>image</th>
        <th>name</th>
        <th>quantity</th>
        <th>action</th>
        </thead>

        <tbody>

        <?php 
        
        $select_cart = mysqli_query($connection, "SELECT * FROM `cart` WHERE cart_table = '$table'");
        $grand_total = 0;
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
        ?>

        <tr>
            <td><img src="../../rb-admin/menu-images/<?php echo $fetch_cart['cart_image']; ?>" height="100" alt=""></td>
            <td><?php echo $fetch_cart['cart_name']; ?></td>
            <td>
                <form action="" method="post">
                    <input type="hidden" name="update_quantity_id"  value="<?php echo $fetch_cart['cart_id']; ?>" >
                    <input type="number" name="update_quantity" min="1"  value="<?php echo $fetch_cart['cart_quantity']; ?>" >
                    <input type="submit" value="update" name="update_update_btn" class="btn btn-primary">
                </form>   
            </td>
            <td><a href="cart.php?remove=<?php echo $fetch_cart['cart_id']; ?>" onclick="return confirm('remove item from cart?')" class="delete-btn btn btn-primary"> <i class="ion ion-ios-trash"></i> remove</a></td>
        </tr>
        <?php
            // $grand_total += $sub_total;  
            };
        };
        ?>
        <tr class="table-bottom">
            <td><a href="activated-table.php" class="option-btn btn btn-primary" style="margin-top: 0;">continue shopping</a></td>
            <!--<td colspan="3">grand total</td>
            <td>$<?php //echo $grand_total; ?>/-</td> -->
                <td></td>
                <td></td>
            <td><a href="cart.php?delete_all" onclick="return confirm('are you sure you want to delete all?');" class="delete-btn btn btn-primary"> <i class="ion ion-ios-trash"></i> delete all </a></td>
        </tr>

        </tbody>

        </table>

        <div class="checkout-btn">
        <a href="checkout.php" class="btn btn-primary<?= ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
        </div>
        
    </div>
</body>
</html>