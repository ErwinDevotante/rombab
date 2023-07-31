<?php
    include '../table-auth.php';
    if($_SESSION['user_id']==''){
        header('location:../index.php');
    }
    if($row["session_tb"] != '3'){
        header('location:../table-index.php');
        exit();
    }
    $table = $row["user_id"];
    $select_rows =  mysqli_query($connection, "SELECT  * FROM `cart` WHERE cart_table = '$table'");
    $row_count = mysqli_num_rows($select_rows);

?>

<nav class="navbar navbar-expand-lg navbar-dark p-3">
    <div class="container-fluid">
        <img class="p-2" src="/assets/rombab-logo.png" alt="Romantic Baboy Logo" width="130">
        <a class="navbar-brand" href="activated-table.php">
            <img src="/assets/unlimited_korean_grill.png" alt="Romantic Baboy Logo" width="400">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto order-2 order-lg-1">
            </ul>
            <ul class="navbar-nav ms-auto order-3 order-lg-3 gap-3">
                <li class="nav-item">
                    <a class="nav-link active text-white" href="cart.php">
                        <i class="ion ion-android-restaurant fa-2x"></i>
                        <span id="cartCount" class="position-absolute translate-middle badge rounded-pill bg-red"><?php echo $row_count; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" href="activated-table.php"><i class="ion ion-grid fa-2x"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>