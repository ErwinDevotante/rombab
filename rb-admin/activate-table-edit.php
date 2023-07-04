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

    $query_save = "UPDATE appointment SET table_id = $table_save, appointment_session = $save WHERE appointment_id = $id_save";
    $result_save = mysqli_query($connection, $query_save);
}

$id_reset = $_GET['id_reset'];
$reset = $_GET['reset'];
$table_reset = $_GET['table_reset'];
//reset
if ($id_reset !==null && $reset !==null && $table_reset !== null) {

    $query_reset= "UPDATE appointment SET table_id = $table_reset, appointment_session = $reset WHERE appointment_id = $id_reset";
    $result_reset = mysqli_query($connection, $query_reset);
}

    unset($_GET);
    header("Location: manage-appointment.php");
?>