<?php
session_start();

unset($_SESSION["user_id"]);
session_destroy();

echo '<script type="text/javascript">'; 
        echo 'alert("Logged-out Sucessfully!");'; echo 'window.location.href = "index.php";';
        echo '</script>';
?>