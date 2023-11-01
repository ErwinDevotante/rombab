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
    $select_notif = mysqli_query($connection, "SELECT * FROM orders WHERE user_table_id = '$user_id' AND read_notif_session = '0' 
        AND user_table = '$table' AND status = '1'");
    $notif_count = mysqli_num_rows($select_notif);

?>
<style>
    .large-icon {
        font-size: 25px; /* Adjust the size as needed */
    }
</style>
<nav class="navbar navbar-expand-lg navbar-dark p-3">
    <div class="container-fluid">
        <img class="p-2" src="/assets/rombab-logo.png" alt="Romantic Baboy Logo" width="130">
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
                        <i class="ion ion-android-notifications large-icon"></i>
                        <span id="cartCount" class="position-absolute translate-middle badge rounded-pill bg-red"><?php echo $notif_count; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" href="cart.php">
                        <i class="ion ion-android-restaurant large-icon"></i>
                        <span id="cartCount" class="position-absolute translate-middle badge rounded-pill bg-red"><?php echo $row_count; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" href="activated-table.php"><i class="ion ion-grid large-icon"></i></a>
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
        <h5 class="modal-title" id="statusModalLabel"><?php echo $row["name"]; ?> Summary of Order/s</h5>
      </div>
      <div class="modal-body">
            <div class="accordion p-4" id="faqAccordion">
            <?php
            $notif = mysqli_query($connection, "SELECT * FROM orders WHERE user_table_id = '$user_id'
            AND user_table = '$table' ORDER BY time_date DESC");
                    if(mysqli_num_rows($notif) > 0){
                        foreach($notif as $notif_row) {
                            $time_date = $notif_row['time_date'];
                            $timestamp = strtotime($time_date);
                            $formatted_time = date('g:i A', $timestamp);
                            $id_notif = $notif_row['order_id'];
                            if ($notif_row['read_notif_session'] == '0' && $notif_row['status'] == '1') { ?>
                            <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?=$id_notif ;?>">
                            <button class="accordion-button collapsed text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$id_notif ;?>" aria-expanded="true" aria-controls="collapse<?=$id_notif ;?>" data-order-id="<?=$id_notif ;?>">
                                Ready to serve.
                            </button>
                            </h2>
                            <div id="collapse<?=$id_notif ;?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$id_notif ;?>">
                                <div class="accordion-body">
                                    <?php $skillsArray = explode(",", $notif_row['total_products']);
                                    foreach ($skillsArray as $skill) {
                                        echo "<p class='m-0'>" . trim($skill) . "</p>";
                                        echo "<hr class='bg-dark m-1'>";
                                        } ?>
                                    <p class="m-0 text-secondary font-weight-light font-italic">Order taken at <em><?php echo $formatted_time; ?></em></p>
                                    <p class="m-0 text-secondary font-weight-light font-italic">Elapsed time: <?=$notif_row['serve_time'];?> seconds</p>
                                </div>
                            </div>
                        </div>
                        <?php } elseif ($notif_row['read_notif_session'] == '1' && $notif_row['status'] == '1') { ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?=$id_notif ;?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$id_notif ;?>" aria-expanded="true" aria-controls="collapse<?=$id_notif ;?>" data-order-id="<?=$id_notif ;?>">
                                Already served.
                            </button>
                            </h2>
                            <div id="collapse<?=$id_notif;?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$id_notif;?>">
                                <div class="accordion-body">
                                    <?php $skillsArray = explode(",", $notif_row['total_products']);
                                    foreach ($skillsArray as $skill) {
                                        echo "<p class='m-0'>" . trim($skill) . "</p>";
                                        echo "<hr class='bg-dark m-1'>";
                                        } ?>
                                    <p class="m-0 text-secondary font-weight-light font-italic">Order taken at <em><?php echo $formatted_time; ?></em></p>
                                    <p class="m-0 text-secondary font-weight-light font-italic">Elapsed time: <?=$notif_row['serve_time'];?> seconds</p>
                                </div>
                            </div>
                        </div>
                        <?php } elseif ($notif_row['status'] == '0' && ($notif_row['read_notif_session'] == '1' || $notif_row['read_notif_session'] == '0')) { ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?=$id_notif;?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$id_notif;?>" aria-expanded="true" aria-controls="collapse<?=$id_notif ;?>" data-order-id="<?=$id_notif;?>">
                                For preparing.
                            </h2>
                            <div id="collapse<?=$id_notif;?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$id_notif;?>">
                                <div class="accordion-body">
                                    <?php $skillsArray = explode(",", $notif_row['total_products']);
                                    foreach ($skillsArray as $skill) {
                                        echo "<p class='m-0'>" . trim($skill) . "</p>";
                                        echo "<hr class='bg-dark m-1'>";
                                        } ?>
                                    <p class="m-0 text-secondary font-weight-light font-italic">Order taken at <em><?php echo $formatted_time; ?></em></p>
                                </div>
                            </div>
                        </div>
                        <?php }
                    } 
                } else { ?>
                     <div class="text-center"><em>No notifications.</em></div>
                <?php }?>     
            </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" onclick="closeModal()">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Define a click event handler for the button
    $('#openModalButton').click(function() {
      // Use jQuery to show the modal
      $('#statusModal').modal('show');
    });
  });

  // Close the modal when needed
  function closeModal() {
    $('#statusModal').modal('hide');
  }

  $(document).ready(function() {
    // Define a click event handler for the accordion items
    $('.accordion-button').click(function() {
        var orderID = $(this).data('order-id');
        updateReadNotifSession(orderID);
    });

    // Function to update read_notif_session using AJAX
    function updateReadNotifSession(orderID) {
        $.ajax({
            type: "POST",
            url: "cart-update.php", // Create this PHP file
            data: { orderID: orderID },
            success: function(response) {
                // Handle the response if needed
                // For example, you can display a success message or update the UI
            }
        });
    }
});

</script>

