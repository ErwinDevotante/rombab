<?php 
include '../../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['update_id']) && isset($_POST['update_value']))) {
        $update_id = $_POST['update_id'];
        $update_value = $_POST['update_value'];

        // Ensure the value is within the range of 1 to 5
        $update_value = max(1, min(5, $update_value));

        // Update the database
        $update_quantity_query = mysqli_query($connection, "UPDATE `cart` SET cart_quantity = '$update_value' WHERE cart_id = '$update_id'");
        if ($update_quantity_query) {
            // Successfully updated the database
            echo json_encode(['success' => true, 'message' => 'Cart updated successfully']);
        } else {
            // Error updating the database
            echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
        }
    } else {
        // Invalid request parameters
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];

    // Update the read_notif_session in the orders table
    $updateQuery = "UPDATE orders SET read_notif_session = '1' WHERE order_id = '$orderID' AND status = '1'";
    if (mysqli_query($connection, $updateQuery)) {
        // Return a success response
        echo 'Success';
    } else {
        // Handle the error if the update fails
        echo 'Error';
    }
} else {
    // Handle invalid or missing POST data
    echo 'Invalid Request';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['totalBill']) && isset($_POST['userId']) && isset($_POST['tableNo']))) {
    // Get the totalBill, userId, and tableNo from the POST request
    $bill = $_POST['totalBill'];
    $user = $_POST['userId'];
    $table = $_POST['tableNo'];

    // Perform the SQL query to insert data into the billing_history table
    $insertBillingQuery = "INSERT INTO billing_history (user_id, table_no, total_bill) VALUES ('$user', '$table', '$bill')";

        if (mysqli_query($connection, $insertBillingQuery)) {
            // Now, perform the SQL query to update summary_orders
            $updateSummaryOrders = "UPDATE summary_orders SET summary_status = '1' WHERE user_summary_id = '$user' AND summary_table_no = '$table'";

            if (mysqli_query($connection, $updateSummaryOrders)) {
                // Update was successful
                echo 'success';
            } else {
                // Update failed
                echo 'Error updating summary_orders: ' . mysqli_error($connection);
            }
        } else {
            // Insertion into billing_history failed
            echo 'Error inserting data into billing history: ' . mysqli_error($connection);
        }
} else {
    echo 'Error: Invalid Request';
}
?>