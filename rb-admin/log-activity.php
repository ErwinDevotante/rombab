<?php
include '../conn.php';

if (isset($_POST['userId']) && isset($_POST['action'])) {
    $userId = $_POST['userId'];
    $action = $_POST['action'];
    date_default_timezone_set('Asia/Manila');
    $dateTime = date('Y-m-d H:i:s');

    $insertQuery = "INSERT INTO activity_log (log_user_id, action, date_time) VALUES ('$userId', '$action', '$dateTime')";
    
    if (mysqli_query($connection, $insertQuery)) {
        echo "Log entry added successfully";
    } else {
        echo "Error: " . $insertQuery . "<br>" . mysqli_error($connection);
    }
} else {
    echo "Invalid parameters";
}

mysqli_close($connection);
?>
