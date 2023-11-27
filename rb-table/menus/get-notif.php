<?php

        include '../../conn.php';
        include '../table-auth.php';
    
        $table = $row["user_id"];
        $customer_result = mysqli_query($connection, "SELECT * FROM appointment WHERE table_id = '$table' AND appointment_session = '1'");
	$customer = mysqli_fetch_array($customer_result);

        $user_id = $customer["appointment_id"];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getCount') {
        
        
        $select_notif = mysqli_query($connection, "SELECT * FROM orders WHERE user_table_id = '$user_id' AND read_notif_session = '0' AND user_table = '$table' AND status = '1'");
        $count = mysqli_num_rows($select_notif);
        
        echo $count;
      }

      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getCartCount') {
        // Fetch and return the cart count from the server
        $select_rows = mysqli_query($connection, "SELECT * FROM `cart` WHERE cart_table = '$table'");
        $row_count = mysqli_num_rows($select_rows);
      
        echo $row_count;
      }
?>