<?php 
if($_SESSION['user_id']==''){
	header('location:../index.php');
	}
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background: #8b0000;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu"><i class="ion ion-navicon-round text-white"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <?php if ($row['user_role'] == 3) { ?>
          <a class="nav-link text-white">Welcome <?php echo $row['name'] ?>!</a>
       <?php } else {
        header('location:../index.php');
       } ?>
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

<!-- Password input dialog (hidden by default) -->
    <div id="passwordDialog" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black">Logout</h5>
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
    </script>