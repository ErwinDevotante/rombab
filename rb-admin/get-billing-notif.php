<?php 
include '../conn.php';
date_default_timezone_set('Asia/Manila');
$currentDate = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getCount') { 
    $select_notif = mysqli_query($connection, "SELECT * FROM billing_notif WHERE marked_as_read = '0' AND DATE(time_date) = '$currentDate'");
    $count = mysqli_num_rows($select_notif);
      
    echo $count;
}

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];

    // Update the read_notif_session in the orders table
    $updateQuery = "UPDATE billing_notif SET marked_as_read = '1'";
    if (mysqli_query($connection, $updateQuery)) {
        // Return a success response
        echo json_encode(['success' => true]);
    } else {
        // Handle the error if the update fails
        echo json_encode(['error' => true]);
    }
}

?>