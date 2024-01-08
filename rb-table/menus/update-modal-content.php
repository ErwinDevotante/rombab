<?php
// Include your database connection file
include '../../conn.php';
include '../table-auth.php';

$table = $row["user_id"];
$customer_result = mysqli_query($connection, "SELECT * FROM appointment WHERE table_id = '$table' AND appointment_session = '1'");
$customer = mysqli_fetch_array($customer_result);

$user_id = $customer["appointment_id"];

// Fetch the latest orders for the specified table
$orders_query = mysqli_query($connection, "SELECT * FROM orders WHERE user_table_id = '$user_id' AND user_table = '$table' ORDER BY time_date DESC");

// Check if there are orders
if (mysqli_num_rows($orders_query) > 0) {
    ?>
    <div style="overflow-x:auto;">
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
        <?php }}
        ?>
    </div>

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
    </script>
<?php
} else {
    // No orders for the specified table
    echo "<div class='text-center'><em>No notifications.</em></div>";
}
?>
