<?php 
if($_SESSION['user_id'] == ''){
	header('location:../index.php');
}
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light bg-red">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu"><i class="ion ion-navicon-round text-white"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <?php if ($row['user_role'] == 1) { ?>
                <a class="nav-link text-white">Welcome Super Admin <?php echo $row['name'] ?>!</a>
            <?php } else if ($row['user_role'] == 2 || $row['user_role'] == 5) { ?>
                <a class="nav-link text-white">Welcome Admin <?php echo $row['name'] ?>!</a>
            <?php } ?>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <!-- Add the onclick event to trigger the password prompt -->
            <a class="nav-link text-white" href="#" onclick="confirmLogout()">
                Logout
            </a>
        </li>
    </ul>
</nav>

<script>
    // JavaScript function to prompt for password and handle logout
    function confirmLogout() {
        // Prompt for password input
        var passwordInput = prompt("Please enter your password:");

        // Replace "123456789" with the actual password to check
        var correctPassword = "123456789";

        // Check if the entered password is correct
        if (passwordInput === correctPassword) {
            // Redirect to the logout page if password is correct
            window.location.href = "../../../log-out.php";
        } else {
            // Show an alert if the password is incorrect
            alert("Incorrect password. Logout action canceled.");
        }
    }
</script>
