<?php
// Include your database connection file
include '../../conn.php';
include '../table-auth.php';

$table = $row["user_id"];
$customer_result = mysqli_query($connection, "SELECT * FROM appointment WHERE table_id = '$table' AND appointment_session = '1'");
$customer = mysqli_fetch_array($customer_result);
date_default_timezone_set('Asia/Manila');
$currentDate = date('Y-m-d');

$user_id = $customer["appointment_id"];

// Fetch the latest orders for the specified table
$orders_query = mysqli_query($connection, "SELECT * FROM orders WHERE user_table_id = '$user_id' AND user_table = '$table' ORDER BY time_date DESC");
$deactivated_menu = mysqli_query($connection, "SELECT deact_menu_notif.deact_menu_id, menu_notif.date_time, menus.menu_name, deact_menu_notif.marked_as_read, deact_menu_notif.user_id  FROM deact_menu_notif
                        INNER JOIN menus ON menus.menu_id = deact_menu_notif.menu_id
                        INNER JOIN appointment ON appointment.appointment_id = deact_menu_notif.user_id
                        INNER JOIN menu_notif ON menu_notif.menu_notif_id = deact_menu_notif.notif_id
                        WHERE menus.menu_availability = '1' AND DATE(menu_notif.date_time) = '$currentDate'
                        AND deact_menu_notif.user_id = '$user_id'
                        ORDER BY menu_notif.date_time DESC");

$searching_deact_menu = mysqli_query($connection, "SELECT * FROM menu_notif 
                          INNER JOIN menus ON menus.menu_id = menu_notif.menu_id
                          WHERE menus.menu_availability = '1' AND DATE(menu_notif.date_time) = '$currentDate'
                          ORDER BY menu_notif.date_time DESC");

?>
<div style="overflow-x:auto;">
    <?php
    if (mysqli_num_rows($searching_deact_menu) > 0) {
        foreach ($searching_deact_menu as $search_row) {
            $notif_id = $search_row['menu_notif_id'];
            $menu_id = $search_row['menu_id'];
            $deact_query = "SELECT * FROM deact_menu_notif WHERE notif_id = '$notif_id' AND user_id = '$user_id' AND menu_id = '$menu_id'";
            $deact_result = mysqli_query($connection, $deact_query);
                if(mysqli_num_rows($deact_result) == 0) {
                    mysqli_query($connection, "INSERT INTO deact_menu_notif(notif_id, user_id, menu_id, marked_as_read) VALUES ('$notif_id', '$user_id', '$menu_id', '0')");
                }
        }
    }

    if (mysqli_num_rows($deactivated_menu) > 0) { ?>
    <small><em>A reminder that these items are not available on the menu.</em></small>
    <?php foreach ($deactivated_menu as $menu_row) {
        $id_notif = $menu_row['deact_menu_id'];
        $time_date = $menu_row['date_time'];
        $timestamp = strtotime($time_date);
        $formatted_time = date('g:i A', $timestamp); 
        $cart_name = $menu_row['menu_name'];
        if ($menu_row['marked_as_read'] == '0') {?>
            <button class="btn btn-light btn-outline-dark mt-2 w-100 text-danger" type="button">
                Sorry, <strong><?= $menu_row['menu_name']; ?></strong> is not available.
                <small><p class="m-0 text-secondary font-weight-light font-italic">As of <?php echo $formatted_time; ?>, today.</p></small>
                <span class="btn btn-light btn-outline-dark btn-sm btn-mark-as-okay" data-order-id="<?=$id_notif;?>">
                    Okay <i class="bi bi-check2"></i>
                </span>
            </button>
        <?php } else if ($menu_row['marked_as_read'] == '1') { ?>
            <button class="btn btn-light btn-outline-dark mt-2 w-100" type="button">
                Sorry, <strong><?= $menu_row['menu_name']; ?></strong> is not available.
                <small><p class="m-0 text-secondary font-weight-light font-italic">As of <?php echo $formatted_time; ?>, today.</p></small>
            </button> 
    <?php } 
    
    mysqli_query($connection, "DELETE FROM `cart` WHERE cart_name = '$cart_name'");

    } ?>
        <hr class='bg-black m-2'>
    <?php }          

    // Check if there are orders
    if (mysqli_num_rows($orders_query) > 0) {
        ?>
        <?php
        foreach ($orders_query as $order_row) {
            $id_notif = $order_row['order_id'];
            $time_date = $order_row['time_date'];
            $timestamp = strtotime($time_date);
            $formatted_time = date('g:i A', $timestamp);
            if ($order_row['read_notif_session'] == '0' && $order_row['status'] == '1') {
            ?>
            <button class="btn btn-light btn-outline-dark mt-2 w-100 text-danger" type="button">
                Your order <?= $order_row['total_products']; ?> <strong>is/are ready to serve</strong>.
                <small><p class="m-0 text-secondary font-weight-light font-italic">Order taken at <?php echo $formatted_time; ?></p></small>
                <span class="btn btn-light btn-outline-dark btn-sm btn-mark-as-served " data-order-id="<?=$id_notif;?>">
                    Mark as Served <i class="bi bi-check2"></i>
                </span>
            </button> 
        <?php
            } else if (($order_row['read_notif_session'] == '1' && $order_row['status'] == '1')) { ?>
            <button class="btn btn-light btn-outline-dark mt-2 w-100" type="button">
                Your order <?= $order_row['total_products']; ?> <strong>was/were already served</strong>.
                <small><p class="m-0 text-secondary font-weight-light font-italic">Order taken at <?php echo $formatted_time; ?></p></small>
            </button>
        <?php }
        }?>
</div>
<?php
} else {
    // No orders for the specified table
    echo "<div class='text-center'><em>No notifications for order/s.</em></div>";
}
?>

<script>
        // Attach the event handler for "Mark as Served" button
        $('.btn-mark-as-served').off('click').on('click', function(e) {
            e.stopPropagation();
            var orderID = $(this).data('order-id');

            // Use AJAX to update read_notif_session
            $.ajax({
                type: "POST",
                url: "cart-update.php", // Adjust the URL as needed
                data: { orderID: orderID },
                success: function(response) {
                    // If successful, update the button class and text
                    var button = $('[data-order-id="' + orderID + '"]');
                    button.find('.bi-check2').remove(); // Optionally remove the check icon
                    button.text('Marked as Already Served');
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        $('.btn-mark-as-okay').off('click').on('click', function(e) {
            e.stopPropagation();
            var okayOrderID = $(this).data('order-id');

            // Use AJAX to update read_notif_session
            $.ajax({
                type: "POST",
                url: "cart-update.php", // Adjust the URL as needed
                data: { okayOrderID: okayOrderID },
                success: function(response) {
                    // If successful, update the button class and text
                    var button = $('[data-order-id="' + okayOrderID + '"]');
                    button.find('.bi-check2').remove(); // Optionally remove the check icon
                    button.text('Marked as Okay');
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
