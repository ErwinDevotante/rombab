<?php 
if($_SESSION['online_user_id'] == ''){
	header('location:../online-appointment.php');
}
?>
<style>
    .large-icon {
        font-size: 20px; /* Adjust the size as needed */
    }
</style>
<nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top" style="background: #8b0000; overflow-x:auto;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu"><i class="ion ion-navicon-round text-white"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link text-white text-nowrap">Welcome <?php echo $row['first_name'] ?>!</a>
        </li>
    </ul>
</nav>