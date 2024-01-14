<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "romantic_baboy_dbase";

    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    date_default_timezone_set('Asia/Manila');
    // Detect inactivity and trigger logout
    $inactivityTimeout = 5 * 60; // 5 minutes
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactivityTimeout)) {
        // Log out the user due to inactivity
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
        header("Location: index.php");
        exit;
    }

    $_SESSION['last_activity'] = time();

?>