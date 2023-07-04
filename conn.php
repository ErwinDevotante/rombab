<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "romantic_baboy_dbase";

    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
?>