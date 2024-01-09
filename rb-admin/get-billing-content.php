<?php 
include '../conn.php';
date_default_timezone_set('Asia/Manila');
$currentDate = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'billing_notif') {
$billing_notif_query = mysqli_query($connection, "SELECT * FROM billing_notif
INNER JOIN users ON users.user_id=billing_notif.table_id
WHERE DATE(billing_notif.time_date) = '$currentDate' ORDER BY billing_notif.time_date DESC");

// Check if there are orders
if (mysqli_num_rows($billing_notif_query) > 0) {
    ?>
    <div style="overflow-x:auto;">
        <?php
        foreach ($billing_notif_query as $billing_row) {
            $id_notif = $billing_row['billing_notif_id'];
            $time_date = $billing_row['time_date'];
            $timestamp = strtotime($time_date);
            $formatted_time = date('g:i A', $timestamp);
            if ($billing_row['marked_as_read'] == '0') {
            ?>
            <button class="btn btn-light btn-outline-dark mt-2 w-100 text-danger" type="button">
                <?= $billing_row['name']; ?> <strong>is calling for bill-out</strong>.
                <small><p class="m-0 text-secondary font-weight-light font-italic">Bill out taken at <?php echo $formatted_time; ?></p></small>
                <span class="btn btn-light btn-outline-dark btn-sm btn-mark-as-served " data-order-id="<?=$id_notif;?>">
                    Okay <i class="bi bi-check2"></i>
                </span>
            </button> 
        <?php
            } else if ($billing_row['marked_as_read'] == '1' ) { ?>
            <button class="btn btn-light btn-outline-dark mt-2 w-100" type="button">
                <?= $billing_row['name']; ?> <strong>already bill-out</strong>.
                <small><p class="m-0 text-secondary font-weight-light font-italic">Bill out taken at <?php echo $formatted_time; ?></p></small>
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
                url: "get-billing-notif.php", // Adjust the URL as needed
                data: { orderID: orderID },
                success: function(response) {
                    // If successful, update the button class and text
                    var button = $('[data-order-id="' + orderID + '"]');
                    button.find('.bi-check2').remove(); // Optionally remove the check icon
                    button.text('Marked as Already Bill-out');
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
}
?>