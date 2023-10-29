<?php 
include '../conn.php';
include 'table-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="/assets/rombab-logo.png">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/node_modules/ionicons/css/ionicons.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="/style.css">
    <!-- JQuery -->
    <script src="/node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-black">
    <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh;">
        <main class="px-3">
            <div class="text-center">
                <img src="/assets/rombab-logo.png" class="img-logo img-fluid" alt="Your Photo">
            </div>
            <div class="text-center">
                <img src="/assets/unlimited_korean_grill.png" class="img-sub-logo img-fluid" alt="Your Photo">
            </div>
            <p class="lead text-white text-center">Welcome, <?php echo $row['name']; ?>!</p>
            <div class="lead text-center">
                <?php 
                if ($row['session_tb'] == '3'){
                    echo '<a href="menus/activated-table.php" class="btn btn-lg btn-primary fw-bold border-white">Tap to start</a>';
                }
                else if($row['session_tb'] == '2') {
                    echo '<a href="deactivated-table.php" class="btn btn-lg btn-primary fw-bold border-white">Tap to start</a>';
                }
                ?>
            </div>
        </main>
    </div>
    <!-- Button on the left bottom of the screen -->
    <div class="fixed-bottom p-3">
        <a href="#" class="text-dark" onclick="confirmLogout()"><i class="ion ion-android-exit"></i></a>
    </div>

    <!-- Password input dialog (hidden by default) -->
    <div id="passwordDialog" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="password" id="passwordInput" class="form-control" placeholder="Password">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="checkPassword()">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript function to handle logout
        function confirmLogout() {
            // Show the password input dialog
            $('#passwordDialog').modal('show');
        }

        // JavaScript function to check the password
        function checkPassword() {
            // Get the entered password
            var enteredPassword = document.getElementById('passwordInput').value;

            // Check if the entered password is correct
            if (enteredPassword === "123456789") {
                // Redirect to the logout page if the password is correct
                window.location.href = "../../../log-out.php";
            } else {
                // Show an alert if the password is incorrect
                alert("Incorrect password. Logout action canceled.");
            }

            // Hide the password input dialog
            $('#passwordDialog').modal('hide');
        }
    </script>
<!-- Footer -->
<footer class="main-footer bg-black text-center">
    <div class="float-right d-none d-sm-block">
        <!-- Additional footer content or links can go here -->
    </div>
    Romantic Baboy – SM City Sta. Rosa Branch
 &copy; <?php echo date("Y"); ?>
</footer>
</body>
</html>