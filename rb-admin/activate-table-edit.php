<?php 
include '../conn.php';

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

    $query_reset= "UPDATE `appointment` SET table_id = '0', appointment_session = $reset WHERE appointment_id = $id_reset";
    $result_reset = mysqli_query($connection, $query_reset);

    $query_history = "INSERT INTO `appointment_history` VALUES('', '$id_reset', '$time', '$table_reset')";
    $result_query_history = mysqli_query($connection, $query_history);

    $ctivate_table_query = "UPDATE users SET session_tb = '1' WHERE user_id = '$table_reset'";
    $result_activate_table = mysqli_query($connection, $ctivate_table_query);
}

    unset($_GET);
    header("Location: manage-appointment.php");
?>