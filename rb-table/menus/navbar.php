<?php
    include '../table-auth.php';
    if($_SESSION['user_id']==''){
        header('location:../../index.php');
        exit();
    } 
    if ($row["session_tb"] != '3'){
        header('location:../table-index.php');
        exit();
    }
    $table = $row["user_id"];
    $select_rows =  mysqli_query($connection, "SELECT  * FROM `cart` WHERE cart_table = '$table'");
    $row_count = mysqli_num_rows($select_rows);

    $customer_result = mysqli_query($connection, "SELECT * FROM appointment WHERE table_id = '$table' AND appointment_session = '1'");
	  $customer = mysqli_fetch_array($customer_result);

    $user_id = $customer["appointment_id"];

    $select_orders = mysqli_query($connection, "SELECT * FROM `orders` WHERE user_table_id = '$user_id'");
    $orders_count = mysqli_num_rows($select_orders);

    if ($row_count == 5 && $orders_count == 0) {
        $_SESSION['stop'] = true;
        if ($select_rows && mysqli_num_rows($select_rows) > 0) {
            $cart = mysqli_fetch_assoc($select_rows);
            $cart_id_to_delete = $cart['cart_id'];
        }
        $delete_query = mysqli_query($connection, "DELETE FROM `cart` WHERE cart_id = '$cart_id_to_delete'");
    }
?>
<style>
    .large-icon {
        font-size: 25px; /* Adjust the size as needed */
    }
</style>
<nav class="navbar navbar-expand-lg navbar-dark p-3">
    <div class="container-fluid">
        <a href="activated-table.php"><img class="p-2" src="/assets/rombab-logo.png" alt="Romantic Baboy Logo" width="130"> </a>
        <a class="navbar-brand" href="activated-table.php">
            <img src="/assets/unlimited_korean_grill.png" alt="Romantic Baboy Logo" width="400">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto order-2 order-lg-1">
            </ul>
            <ul class="navbar-nav ms-auto order-3 order-lg-3 gap-3">
                <li class="nav-item">
                    <h6 class="text-white"><?php echo $row["name"]; ?>, Pax: <?php echo $customer["count"]; ?></h6>
                    <h6 class="text-white">Customer Name: <?php echo $customer["appointment_name"]; ?></h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" id="openModalButton">
                        <i class="bi bi-bell-fill large-icon"></i>
                        <span class="position-absolute translate-middle badge rounded-pill bg-red" id="notif_num">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" href="cart.php">
                        <i class="bi bi-cart-fill large-icon"></i>
                        <span id="cartCount" class="position-absolute translate-middle badge rounded-pill bg-red">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" href="activated-table.php"><i class="bi bi-menu-button large-icon"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Your modal code -->
<div id="statusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel"><?php echo $row["name"]; ?> Notification</h5>
      </div>
      <div class="modal-body" id="notification_desc">
            
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Your modal code -->
<div id="stopModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="stopModalLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="stopModalLabel">STOP</h5>
      </div>
      <div class="modal-body">
        <p>Customers are limited to selecting a maximum of four products for their first order.</p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" href="cart.php">Close</a>
      </div>
    </div>
  </div>
</div>

<?php if (isset($_SESSION['stop'])) { ?>
<script>
  $(document).ready(function() {
  $("#stopModal").modal("show");
})
</script>
<?php
  unset($_SESSION['stop']);
  exit();
  }
?>

<script>
  $(document).ready(function() {
    // Define a click event handler for the button
    $('#openModalButton').click(function() {
      // Use jQuery to show the modal
      $('#statusModal').modal('show');
    });
  });

  $(document).ready(function() {
    // Function to fetch count from the server
    function fetchCount() {
        // Fetch notif_num
        $.ajax({
        url: 'get-notif.php',
        method: 'POST',
        data: {
            action: 'getCount'
        },
        success: function(response) {
            $('#notif_num').html(response);

        },
        error: function(error) {
            console.error('Error:', error);
        }
        });

        // Fetch cart count
        $.ajax({
        url: 'get-notif.php',
        method: 'POST',
        data: {
            action: 'getCartCount'
        },
        success: function(response) {
            $('#cartCount').html(response);
        },
        error: function(error) {
            console.error('Error:', error);
        }
        });

        // Fetch and update modal content
        $.ajax({
            url: 'update-modal-content.php', // Replace with the actual PHP file to fetch updated modal content
            method: 'POST',
            data: {
                table: '<?php echo $table; ?>'
            },
            success: function(response) {
                // Update modal content
                $('#notification_desc').html(response);
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }

    // Call the fetchCount function initially
    fetchCount();

    // Set up an interval to call fetchCount every second (1000 milliseconds)
    setInterval(fetchCount, 5000);

    });

</script>


