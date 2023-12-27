<?php
// order-update.php

include '../conn.php';

if (isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];

    // Retrieve the elapsed seconds from the POST data
    $elapsedSeconds = isset($_POST['elapsedSeconds']) ? $_POST['elapsedSeconds'] : 0;

    // Update the orders table with the served status and elapsed seconds
    $updateQuery = "UPDATE `orders` SET `status` = '1', `serve_time` = '$elapsedSeconds' WHERE `order_id` = '$orderID'";
    $result = mysqli_query($connection, $updateQuery);

    if ($result) {
        echo "Order marked as served successfully.";
    } else {
        echo "Error marking order as served: " . mysqli_error($connection);
    }
} else {
    echo "Invalid request.";
}
?>
