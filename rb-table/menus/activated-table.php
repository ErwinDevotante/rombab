<?php 
include '../../conn.php';
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
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../../style.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../../node_modules/ionicons/css/ionicons.min.css">
    <!-- JQuery -->
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-black">

    <!-- Image and text -->
	<?php include 'navbar.php';?>
    
	<div class="container">
        <div class="row">
            <div class="col-md-6 d-flex align-items-center">
            <!-- Content for the first column -->
            <img src="/assets/meat_photo.png" width="600" height="1000" class="img-fluid">
            </div>
            
            <div class="col-md-6 d-flex align-items-center">
            <!-- Content for the second column -->
                <div class="row text-center">
                    <div class="col-lg-12">
                    <a href="samgyupsal.php" class="btn btn-primary w-100 h-75 mb-5"><h1 class="mt-3">SAMGYUPSAL</h1></a>
                    </div>
             
                    <div class="col-md-12 ">
                    <a href="side-dishes.php" class="btn btn-primary w-100 h-75 mb-5"><h1 class="mt-3">SIDE DISHES</h1></a>
                    </div>
             
                    <div class="col-md-12 ">
                    <a href="others.php" class="btn btn-primary w-100 h-75 mb-5"><h1 class="mt-3">OTHERS</h1></a>
                    </div>
              
                    <div class="col-md-12 ">
                    <a href="new-offers.php" class="btn btn-primary w-100 h-75 mb-5"><h1 class="mt-3">NEW OFFERS</h1></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container fixed-bottom p-3">
        <div class="row mt-5 justify-content-between">
            <div class="col-auto">
            <a href="#" class="text-dark" onclick="confirmLogout()"><i class="ion-android-exit"></i></a>
            </div>
            <div class="col-auto">
                <a href="check-bill.php" class="btn btn-primary">Check Bill <i class="ion-arrow-right-c"></i></a>
            </div>
        </div>
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
            if (enteredPassword === "<?php echo $row['password'];?>") {
                // Redirect to the logout page if the password is correct
                window.location.href = "../../../log-out.php";
            } else {
                // Show an alert if the password is incorrect
                alert("Incorrect password. Logout action canceled.");
            }

            // Hide the password input dialog
            $('#passwordDialog').modal('hide');
        }

        // Add an event listener to the link
        document.getElementById('disabled_click').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the link from being followed
        });
    </script>
</body>
</html>