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
        $product_quantity = $_POST['product_quantity'];
        $product_table = $table;
        $user_cart_id = $customer["appointment_id"];
    
        $select_cart = mysqli_query($connection, "SELECT * FROM `cart` WHERE cart_name = '$product_name' AND cart_table = '$table'");
    
        if (mysqli_num_rows($select_cart) > 0 && $orders_count == 0) {
          // Product has reached its limit, prevent further changes
          $update_product = mysqli_query($connection, "UPDATE `cart` SET cart_quantity = '1' WHERE user_cart_id = '$user_cart_id' AND cart_name = '$product_name'");
        } else {
            // Check if the product is already in the cart
            if (mysqli_num_rows($select_cart) > 0) {
                $cart_data = mysqli_fetch_assoc($select_cart);
                $existing_quantity = $cart_data['cart_quantity'];
        
                // Check if cart_quantity is equal to $customer["count"] + 2
                if ($existing_quantity == $customer["count"] + 2) {
                    $_SESSION['exist'] = true;
                } else {
                    // Increment cart_quantity by 1
                    $update_product = mysqli_query($connection, "UPDATE `cart` SET cart_quantity = cart_quantity + 1 WHERE user_cart_id = '$user_cart_id' AND cart_name = '$product_name'");
                }
                unset($_POST);
            } else {
                // Insert a new row into the cart table
                $insert_product = mysqli_query($connection, "INSERT INTO `cart`(user_cart_id, cart_table, cart_name, cart_image, cart_quantity) VALUES ('$user_cart_id', '$product_table', '$product_name', '$product_image', '$product_quantity')");
                unset($_POST);
            }
        }  
    }

    ?>

    <div class="container-fluid text-center p-1 text-white">
        <h1>SIDE DISHES MENU</h1>
    </div>

    <div class="container py-5">
      <div class="card-columns-container">
        <?php
          $result_tb = mysqli_query($connection, "SELECT * FROM `menus` WHERE menu_category = 'Side Dishes'");
          if(mysqli_num_rows($result_tb) > 0){
            while ($row = mysqli_fetch_array($result_tb)) {
        ?> 
          <form action="" method="post" onsubmit="rememberScrollPosition()">
            <div class="card p-2 mb-2 card-red text-center position-relative">
              <?php if($row["menu_availability"] == 1) { ?>
                <div class="ribbon">
                  <span class="bg-danger text-white">NOT AVAILABLE</span>
                </div>
              <?php } ?>
              <div class="img">
                <img class="img-fluid rounded-top custom-image" alt="Responsive Image" src ='../../rb-admin/menu-images/<?php echo $row["menu_image"]; ?>'>
              </div>
              <div class="productname bg-white text-black rounded-bottom">
                <h5 class="text-truncate text-uppercase"><?php echo $row["menu_name"]; ?></h5>
              </div>
              <input type="hidden" name="product_image" value="<?php echo $row["menu_image"]; ?>">
              <input type="hidden" name="product_name" value="<?php echo $row["menu_name"]; ?>">
              <input type="hidden" name="product_quantity" value="1">
              <?php if($row["menu_availability"] == 1) { ?>
                <button type="submit" class="btn btn-md btn-outline-danger text-white w-100 mt-1 bg-dark" disabled>
                  NOT AVAILABLE <i class="bi bi-ban"></i>
                </button>
              <?php } else { ?>
                <button type="submit" class="btn btn-md btn-outline-danger text-white w-100 mt-1" name="add_to_cart">
                  ADD TO CART <i class="bi bi-cart-plus-fill"></i>
                </button>
              <?php } ?>
            </div>
          </form>
        <?php
            }
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
    <!-- Limit alert modal -->
    <div id="existModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="existModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="existModalLabel">Limit Reached</h5>
          </div>
          <div class="modal-body">
            <p>Product has already reached its limit!</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End of limit exist -->
    
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
<script>
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