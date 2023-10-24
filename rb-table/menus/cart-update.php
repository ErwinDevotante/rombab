<?php 
include '../../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_id']) && isset($_POST['update_value'])) {
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
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

?>