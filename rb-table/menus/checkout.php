<?php 
include '../../conn.php';
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
    <link rel="stylesheet" href="../../node_modules/ionicons/css/ionicons.min.css">
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
	<?php include 'navbar.php';
    if(isset($_POST['done_btn'])) {
        $cart_query = mysqli_query($connection, "SELECT * FROM `cart` WHERE cart_table = '$table'");
           if(mysqli_num_rows($cart_query) > 0){
              while($product_item = mysqli_fetch_assoc($cart_query)){
                 $product_name[] = $product_item['cart_name'] .' ('. $product_item['cart_quantity'] .') ';
              };
           };
           date_default_timezone_set('Asia/Manila');
           $total_products = implode(', ',$product_name);
           $currentDateTime = date('Y-m-d H:i:s');
           $user_table_id = $customer["appointment_id"];

           if ($select_rows) {
            while ($count = mysqli_fetch_assoc($select_rows)) {
                $cart_table = $count['cart_table'];
                $cart_name = $count['cart_name'];
                $cart_quantity = $count['cart_quantity'];
                $cart_menuprice = $count['cart_menuprice'];
                $summary_session = '0';
        
                $summary_query = mysqli_query($connection, "INSERT INTO `summary_orders` (summary_table_no, summary_products, summary_qty, summary_price, summary_status) VALUES ('$cart_table', '$cart_name', '$cart_quantity', '$cart_menuprice', '$summary_session')");
            }
            } else {
                echo "Error in SELECT query: " . mysqli_error($connection) . "<br>";
            }
           
           $detail_query = mysqli_query($connection, "INSERT INTO `orders`(user_table_id, user_table, total_products, time_date) VALUES('$user_table_id','$table','$total_products','$currentDateTime')") or die('query failed');
           $deleting_cart = mysqli_query($connection, "DELETE FROM `cart` WHERE cart_table = '$table'") or die('query failed');
        
           if($cart_query && $detail_query && $deleting_cart && $summary_query){
            $_SESSION['success'] = true;
         }
         unset($_POST);
        }
    ?>

    <!-- Success alert modal -->
    <div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Thank you for ordering!</h5>
                </div>
                <div class="modal-footer">
                    <a href='activated-table.php' class='btn btn-primary'>Continue Ordering</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End of success alert modal -->

    <div class="container-fluid text-center p-1 text-white">
        <h1>Check-out</h1>
    </div>

    <div class="container mt-4">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header bg-dark text-white text-center">
                            <h4>Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <h5 class="text-center"><?=$row['name']; ?></h5>
                            <hr>
                            <table class="table table-bordered text-white">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $select_cart = mysqli_query($connection, "SELECT * FROM `cart` WHERE cart_table = '$table'");
                                    if(mysqli_num_rows($select_cart) > 0){
                                        while($fetch_cart = mysqli_fetch_assoc($select_cart)){ 
                                    ?>
                                    <tr>
                                        <td><?= $fetch_cart['cart_name']; ?></td>
                                        <td><?= $fetch_cart['cart_quantity']; ?></td>
                                        <?php if ($fetch_cart['cart_menuprice'] == '0') {?>
                                            <td>-</td>
                                        <?php } else { ?>
                                            <td>₱ <?= $fetch_cart['cart_menuprice'] * $fetch_cart['cart_quantity']; ?></td>
                                        <?php } ?> 
                                    </tr>
                                    <?php } 
                                    } else{
                                        echo "<div class='display-order'><span>your cart is empty!</span></div>";
                                    }?>
                                </tbody>
                                
                            </table>
                            
                        </div>
                        
                    </div>
                    <form action="" method="post">
                        <div class="done-btn text-center">
                            <input type="submit" name="done_btn" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
</body>
</html>
<?php
if (isset($_SESSION['success'])) {
    ?>
    <script>
        $(document).ready(function() {
            $("#successModal").modal("show");
        })
    </script>
    <?php
    unset($_SESSION['success']);
    exit();
} 
?>