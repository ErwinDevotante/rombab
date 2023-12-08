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
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../../style.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../../node_modules/ionicons/css/ionicons.min.css">
    <!-- JQuery -->
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
        $product_table= $table;
        $user_cart_id = $customer["appointment_id"];
    
        $select_cart = mysqli_query($connection, "SELECT * FROM `cart` WHERE cart_name = '$product_name' AND cart_table = '$table'");
    
        if(mysqli_num_rows($select_cart) > 0) {
            $_SESSION['exist'] = true;
            unset($_POST);
        } else {
            $insert_product = mysqli_query($connection, "INSERT INTO `cart`(user_cart_id, cart_table, cart_name, cart_image, cart_quantity) VALUES ('$user_cart_id', '$product_table', '$product_name', '$product_image', '$product_quantity')");
            $_SESSION['added'] = true;
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
                                <button type="submit" class="btn btn-md btn-outline-danger text-white w-100 mt-1" name="add_to_cart">ADD TO ORDER <i class="bi bi-cart-plus-fill"></i></button>
                            </div>
                        </form>
                        <?php }
                        }  
            ?>
        </div>
    </div>

    <!-- Added alert modal -->
    <div id="addedModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addedModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="addedModalLabel">Added</h5>
          </div>
          <div class="modal-body">
            <p>Product Added Successfully!</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End of added alert modal -->
    <!-- Exist alert modal -->
    <div id="existModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="existModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="existModalLabel">Existed</h5>
          </div>
          <div class="modal-body">
            <p>Product Already Added!</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End of success exist -->
    
<!-- Footer -->
<footer class="main-footer bg-black text-center">
    <div class="float-right d-none d-sm-block">
        <!-- Additional footer content or links can go here -->
    </div>
    Romantic Baboy – SM City Sta. Rosa Branch
 &copy; <?php echo date("Y"); ?>
</footer>
</body>
</html>

<?php if (isset($_SESSION['added'])) { ?>
<script>
  $(document).ready(function() {
  $("#addedModal").modal("show");
})
</script>
<?php
  unset($_SESSION['added']);
  exit();
  } else if (isset($_SESSION['exist'])) {
  ?>
<script>
  $(document).ready(function() {
  $("#existModal").modal("show");
})
</script>
<?php
  unset($_SESSION['exist']);
  exit();
  }
?>