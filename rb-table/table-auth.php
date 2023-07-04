<?php 
session_start();
if($_SESSION['user_id']==''){
	header('location:../index.php');
	}
    $id = $_SESSION['user_id'];
	$result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id'");
	$row = mysqli_fetch_array($result);
?>