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
	<?php 
    include '../../conn.php';
    include 'navbar.php';
     
     if(isset($_GET['remove'])){
        $remove_id = $_GET['remove'];
        mysqli_query($connection, "DELETE FROM `cart` WHERE cart_id = '$remove_id'");
        header('location:cart.php');
     };
     
     if(isset($_GET['delete_all'])){
        mysqli_query($connection, "DELETE FROM `cart` WHERE cart_table = '$table'");
        header('location:cart.php');
     }
    
     unset($_POST);
    ?>

    <div class="container-fluid text-center p-1 text-white">
        <h1>Order Cart</h1>
    </div>

    <div class="container py-5 text-white">
    <table class="table table-hover table-bordered table-dark mt-5">
        <thead>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Action</th>
        </thead>
        <tbody>
        <?php 
        $select_cart = mysqli_query($connection, "SELECT * FROM `cart` WHERE cart_table = '$table'");

        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
        ?>
        <tr>
            <td><img src="../../rb-admin/menu-images/<?php echo $fetch_cart['cart_image']; ?>" height="100" alt=""></td>
            <td><?php echo $fetch_cart['cart_name']; ?></td>
            <td><?php echo $fetch_cart['cart_menuprice']; ?></td>
            <td>
                <form action="" method="post">
                    <input type="hidden" name="update_quantity_id"  value="<?php echo $fetch_cart['cart_id']; ?>" >
                    <input type="number" name="update_quantity" min="1" max="5" class="text-center" value="<?php echo $fetch_cart['cart_quantity']; ?>" onchange="updateDatabase(this)">
                </form> 
            </td>
            <td><a href="cart.php?remove=<?php echo $fetch_cart['cart_id']; ?>" onclick="return confirm('remove item from cart?')" class="delete-btn btn btn-primary"> <i class="ion ion-ios-trash"></i> Remove</a></td>
        </tr>
        <?php
            };
        };
        ?>
        <tr class="table-bottom">
            <td><a href="activated-table.php" class="option-btn btn btn-primary">Continue Ordering</a></td>
                <td></td>
                <td></td>
                <td></td>
            <?php 
            $scan_row = "SELECT COUNT(*) as count FROM `cart` WHERE cart_table = '$table'";
            $scan_result = mysqli_query($connection, $scan_row);
            $row = mysqli_fetch_assoc($scan_result);
            $rowCount = $row['count'];
            if ($rowCount > 0) { ?>
            <td><a href="cart.php?delete_all" onclick="return confirm('Are you sure you want to delete all?');" class="delete-btn btn btn-primary"> <i class="ion ion-ios-trash"></i> Delete All</a></td>
            <?php } else { ?>
                <td></td>
            <?php } ?>
        </tr>
        </tbody>
        </table>
   
        <?php 
        if($scan_result) {
            if ($rowCount > 0) { ?>
                <div class="checkout-btn text-center">
                    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                </div>
        <?php }
        }?>
        
    </div>
    <script>
        function updateDatabase(inputField) {
            const updateValue = inputField.value;
            const updateId = inputField.parentElement.querySelector('[name="update_quantity_id"]').value;

            // Ensure the value is within the range of 1 to 5
            const updatedValue = Math.max(1, Math.min(5, updateValue));

            // Update the database using AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'cart-update.php', true); // Use 'cart.php' as the target
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(`update_id=${updateId}&update_value=${updatedValue}`);
            // You can add success/failure handling for the AJAX request here
        }
    </script>
</body>
</html>

<script>
    // Add event listener for No of people input
    const qtyInput = document.getElementById('update_quantity');
    qtyInput.addEventListener('input', function() {
        const inputValue = qtyInput.value;
        
        // Remove any non-digit characters (including decimal points)
        const sanitizedValue = inputValue.replace(/[^0-9]/g, '');
        
        // Ensure the value is not empty
        if (sanitizedValue === '') {
            qtyInput.value = '1'; // Set a default value if the input is empty
        } else {
            const qty = parseInt(sanitizedValue, 10);
            
            // Ensure the value is within the range of 1 to 10
            if (qty < 1) {
                qtyInput.value = '1'; // Set the minimum value to 1
            } else if (qty > 5) {
                qtyInput.value = '5'; // Set the maximum value to 10
            } else {
                qtyInput.value = qty; // Update the input value with the sanitized integer value
            }
        }
    });
</script>


