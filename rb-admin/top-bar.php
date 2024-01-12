<?php 
if($_SESSION['user_id'] == ''){
	header('location:../index.php');
}
?>
<style>
    .large-icon {
        font-size: 20px; /* Adjust the size as needed */
    }
</style>
<nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top" style="background: #8b0000; overflow-x:auto;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu"><i class="ion ion-navicon-round text-white"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <?php if ($row['user_role'] == 1) { ?>
                <a class="nav-link text-white text-nowrap">Welcome Super Admin <?php echo $row['name'] ?>!</a>
            <?php } else if ($row['user_role'] == 2 || $row['user_role'] == 5) { ?>
                <a class="nav-link text-white text-nowrap">Welcome Admin <?php echo $row['name'] ?>!</a>
            <?php } else {
                header('location:../index.php');
            } ?>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link active text-white" id="openModalButton">
                <i class="bi bi-bell-fill large-icon"></i>
                <span class="position-absolute translate-middle badge rounded-pill bg-red" id="notif_num">0</span>
            </a>
        </li>
        <li class="nav-item">
            <!-- Add the onclick event to trigger the password prompt -->
            <a class="nav-link text-white" href="#" onclick="confirmLogout()">
                Logout
            </a>
        </li>
    </ul>
</nav>

<!-- Your modal code -->
    <div id="statusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="statusModalLabel"><?php echo $row["name"]; ?> Notification</h5>
            </div>
            <div class="modal-body" id="notification_desc">
                    
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>
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
                    <input type="password" id="passwordInput" class="form-control" placeholder="Enter Password">
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
                // Log the logout action in the activity_log table
                var userId = "<?php echo $row['user_id'];?>";
                var action = "Log-Out";

                $.ajax({
                    type: "POST",
                    url: "log-activity.php", // Adjust the URL to the appropriate script
                    data: { userId: userId, action: action },
                    success: function(response) {
                        // Redirect to the logout page if the password is correct
                        window.location.href = "../../../log-out.php";
                    },
                    error: function() {
                        alert("Failed to log the logout action.");
                    }
                });
            } else {
                // Show an alert if the password is incorrect
                alert("Incorrect password. Logout action canceled.");
            }

            // Hide the password input dialog
            $('#passwordDialog').modal('hide');
        }

        $(document).ready(function() {
            // Define a click event handler for the button
            $('#openModalButton').click(function() {
            // Use jQuery to show the modal
            $('#statusModal').modal('show');
            });
        });

        $(document).ready(function() {
        // Function to fetch count from the server
        function fetchCount() {
            // Fetch notif_num
            $.ajax({
            url: 'get-billing-notif.php',
            method: 'POST',
            data: {
                action: 'getCount'
            },
            success: function(response) {
                $('#notif_num').html(response);

            },
            error: function(error) {
                console.error('Error:', error);
            }
            });

            // Fetch and update modal content
            $.ajax({
                url: 'get-billing-content.php', // Replace with the actual PHP file to fetch updated modal content
                method: 'POST',
                data: {
                action: 'billing_notif'
                },
                success: function(response) {
                    // Update modal content
                    $('#notification_desc').html(response);
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        // Call the fetchCount function initially
        fetchCount();

        // Set up an interval to call fetchCount every second (1000 milliseconds)
        // setInterval(fetchCount, 5000);

        });
    </script>



