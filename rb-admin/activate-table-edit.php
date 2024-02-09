<?php 
include '../conn.php';
if($_SESSION['user_id']==''){
	header('location:../index.php');
}
date_default_timezone_set('Asia/Manila');
// for activation
$id = $_GET['id'];
$session = $_GET['session'];
if ($id !== null && $session !== null) {

    $query_act = "UPDATE users SET session_tb = $session WHERE user_id = $id";
    $result_act = mysqli_query($connection, $query_act);
}

//saving table for appointment
$id_save = $_GET['id_save'];
$save = $_GET['save'];
$table_save = $_GET['table_save'];

if ($id_save !==null && $save !==null && $table !== null) {

    $query_save = "UPDATE `appointment` SET table_id = $table_save, appointment_session = $save WHERE appointment_id = $id_save";
    $result_save = mysqli_query($connection, $query_save);
}

$id_reset = $_GET['id_reset']; // appointment ID
$reset = $_GET['reset']; // 2
$table_reset = $_GET['table_reset']; //table_no
$time = date('H:i:s');
//reset
if ($id_reset !==null && $reset !==null && $table_reset !== null) {

    // 12 for appointment done
    $query_reset= "UPDATE `appointment` SET table_id = '12', appointment_session = $reset WHERE appointment_id = $id_reset";
    $result_reset = mysqli_query($connection, $query_reset);

    $query_history = "INSERT INTO `appointment_history` VALUES('', '$id_reset', '$time', '$table_reset', '0', NULL)";
    $result_query_history = mysqli_query($connection, $query_history);

    $ctivate_table_query = "UPDATE users SET session_tb = '1' WHERE user_id = '$table_reset'";
    $result_activate_table = mysqli_query($connection, $ctivate_table_query);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['update_id']) && isset($_POST['update_value']))) {
    $update_id = $_POST['update_id'];
    $update_value = $_POST['update_value'];

    // Update the database
    $update_quantity_query = mysqli_query($connection, "UPDATE `summary_orders` SET summary_qty = '$update_value' WHERE summary_order_id = '$update_id'");
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

    unset($_GET);
    header("Location: manage-appointment.php");
    
?>