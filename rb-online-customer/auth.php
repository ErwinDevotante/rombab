<?php 
session_start();
if($_SESSION['online_user_id']==''){
	header('location:../online-appointment.php');
	}
    $id = $_SESSION['online_user_id'];
	$result = mysqli_query($connection, "SELECT * FROM users_online where online_user_id = '$id'");
	$row = mysqli_fetch_array($result);
?>