<?php
    include '../table-auth.php';
    $table = $row["user_id"];
    $select_rows =  mysqli_query($connection, "SELECT  * FROM `cart` WHERE cart_table = '$table'");
    $row_count = mysqli_num_rows($select_rows);

?>
<nav class="navbar navbar-expand-lg bg-black fixed">
    <div class="container">
        <a class="navbar-brand" href="activated-table.php" >
            <img src="/assets/rombab-logo.png" width="130" height="130" class="d-inline-block align-middle" alt="Logo">
            <img src="/assets/unlimited_korean_grill.png" width="450" height="35" class="d-inline-block align-middle pa" alt="Logo">
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active text-white" href="cart.php"><i class="ion ion-android-restaurant fa-2x"></i>
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