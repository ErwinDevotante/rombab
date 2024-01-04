<?php 
include '../../conn.php';
include '../table-auth.php';
    if($_SESSION['user_id']==''){
        header('location:../../index.php');
        exit();
    } 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['update_id']) && isset($_POST['update_value']))) {
        $update_id = $_POST['update_id'];
        $update_value = $_POST['update_value'];

        // Ensure the value is within the range of 1 to 5
        //$update_value = max(1, min(5, $update_value));

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
        echo json_encode(['success' => true]);
    } else {
        // Handle the error if the update fails
        echo json_encode(['error' => true]);
    }
} else {
    // Handle invalid or missing POST data
    echo 'Invalid Request';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['to_pay']) && isset($_POST['userId']) && isset($_POST['tableNo']))) {
    // Get the totalBill, userId, and tableNo from the POST request
    $bill = $_POST['to_pay'];
    $user = $_POST['userId'];
    $table = $_POST['tableNo'];
    $pwdDisc = $_POST['pwdDiscount'];
    $seniorDisc = $_POST['seniorDiscount'];
    $bdayDisc = $_POST['bdayPromoDiscount'];

    date_default_timezone_set('Asia/Manila');
    $time = date('H:i:s');
    $currentDate = date('Y-m-d');
    $currentDateTime = date('Y-m-d H:i:s');

    // Perform the SQL query to insert data into the billing_history table
    $insertBillingQuery = "INSERT INTO billing_history (user_id, table_no, total_bill, pwddisc, seniordisc, bdaydisc, date_time) VALUES ('$user', '$table', '$bill', '$pwdDisc', '$seniorDisc', '$bdayDisc', '$currentDateTime')";

        if (mysqli_query($connection, $insertBillingQuery)) {
            // Now, perform the SQL query to update summary_orders
            $updateSummaryOrders = "UPDATE summary_orders SET summary_status = '1' WHERE user_summary_id = '$user' AND summary_table_no = '$table'";
            
            $query_reset= "UPDATE `appointment` SET table_id = '12', appointment_session = '2' WHERE appointment_id = '$user'";
            $result_reset = mysqli_query($connection, $query_reset);

            $query_history = "INSERT INTO `appointment_history` VALUES('', '$user', '$time', '$table', '0', NULL)";
            $result_query_history = mysqli_query($connection, $query_history);

            $ctivate_table_query = "UPDATE users SET session_tb = '1' WHERE user_id = '$table'";
            $result_activate_table = mysqli_query($connection, $ctivate_table_query);

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['check_tableNo']) && isset($_POST['check_userId']))) {
    $check_tableNo = $_POST['check_tableNo'];
    $check_userId = $_POST['check_userId'];

    // Query the database to check if a survey record exists
    $check_query = "SELECT * FROM survey WHERE survey_table_no = $check_tableNo AND survey_user_id = $check_userId";
    $check_result = mysqli_query($connection, $check_query);

    if ($check_result) {
        if (mysqli_num_rows($check_result) > 0) {
            // If a survey record exists, send a response indicating it's "existing"
            echo 'existing';
        } else {
            // If no survey record exists, send a response indicating it's "non-existing"
            echo 'non-existing';
        }
    } else {
        // If there's an error in the query, you can log the error for debugging
        echo 'error: ' . mysqli_error($connection);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['submit_tableNo']) && isset($_POST['submit_userId']) && isset($_POST['rating']))) {
    $submit_tableNo = $_POST['submit_tableNo'];
    $submit_userId = $_POST['submit_userId'];
    $rating = $_POST['rating'];
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    // Insert the user's rating into the database
    $submit_query = "INSERT INTO survey (survey_table_no, survey_user_id, date, survey_answer) VALUES ($submit_tableNo, $submit_userId, '$currentDate', $rating)";
    $submit_result = mysqli_query($connection, $submit_query);

    if ($submit_result) {
        // If the insertion is successful, send a success response
        echo 'success';
    } else {
        // If there's an error, send an error response
        echo 'error';
    }
}
?>