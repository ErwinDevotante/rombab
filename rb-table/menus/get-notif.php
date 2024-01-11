<?php

  include '../../conn.php';
  include '../table-auth.php';
    
  $table = $row["user_id"];
  $customer_result = mysqli_query($connection, "SELECT * FROM appointment WHERE table_id = '$table' AND appointment_session = '1'");
	$customer = mysqli_fetch_array($customer_result);
  date_default_timezone_set('Asia/Manila');
  $currentDate = date('Y-m-d');

  $user_id = $customer["appointment_id"];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getCount') { 
      $select_notif = mysqli_query($connection, "SELECT * FROM orders WHERE user_table_id = '$user_id' AND read_notif_session = '0' AND user_table = '$table' AND status = '1'");
      $notif_count = mysqli_num_rows($select_notif);

      $deactivated_menu = mysqli_query($connection, "SELECT * FROM deact_menu_notif
                                      INNER JOIN menus ON menus.menu_id = deact_menu_notif.menu_id
                                      INNER JOIN appointment ON appointment.appointment_id = deact_menu_notif.user_id
                                      INNER JOIN menu_notif ON menu_notif.menu_notif_id = deact_menu_notif.notif_id
                                      WHERE menus.menu_availability = '1' AND DATE(menu_notif.date_time) = '$currentDate' 
                                      AND deact_menu_notif.marked_as_read='0' AND deact_menu_notif.user_id = '$user_id'");
      
      $menu_count = mysqli_num_rows($deactivated_menu);
      $count = $notif_count + $menu_count;
        
      echo $count;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getCartCount') {
      // Fetch and return the cart count from the server
      $select_rows = mysqli_query($connection, "SELECT * FROM `cart` WHERE cart_table = '$table'");
      $row_count = mysqli_num_rows($select_rows);
      
      echo $row_count;
    }
?>