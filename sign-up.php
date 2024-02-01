<?php
session_start();
include 'conn.php';
if(!empty($_SESSION["user_id"])){
  header("Location: online-appointment.php");
}
date_default_timezone_set('Asia/Manila');
if (isset($_POST["submit"])) {
    $username = sanitizeInput($_POST["username"]);
    $password = sanitizeInput($_POST["password"]);
    $given_name = sanitizeInput($_POST["given-name"]);
    $last_name = sanitizeInput($_POST["last-name"]);
    $phonenumber = sanitizeInput($_POST["phone-number"]);
    $dateTime = date('Y-m-d H:i:s');
    $user_role = '5'; //online-customer

  $duplicate = mysqli_query($connection, "SELECT * FROM users_online WHERE username = '$username'");
  if(mysqli_num_rows($duplicate) > 0){
      $_SESSION['unsuccess'] = true;
  } else {
    $query = "INSERT INTO users_online (first_name, last_name, username, password, phone_number, user_role, date_time_created) VALUES('$given_name', '$last_name', '$username', '$password', '$phonenumber', '$user_role', '$dateTime')";
    mysqli_query($connection, $query);
    $_SESSION['success'] = true;
  }
    unset($_POST);
    //header('Location: sign-up.php');
}

// Delete section for archives
$deleteThreshold = date('Y-m-d H:i:s', strtotime('-30 days'));
// Function to sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="assets/rombab-logo.png">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="node_modules/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="node_modules/ionicons/css/ionicons.min.css">
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons CSS -->
    <link href="node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center bg-grill">
    <div class="login">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
            <h1 class="text-white"><strong>SIGN UP</strong></h1>
            </div>
        </div>
        
        <form class="needs-validation" method="POST">

            <div class="row">
                <div class="col-6">
                    <div class="form-group was-validated">
                        <label class="form-label text-white" for="given-name">Given Name</label>
                        <input class="form-control" type="text" id="given-name" name="given-name" placeholder="Enter given name" min="2" max="50" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group was-validated">
                        <label class="form-label text-white" for="last-name">Last Name</label>
                        <input class="form-control" type="text" id="last-name" name="last-name" placeholder="Enter last name" min="2" max="50" required>
                    </div>
                </div>
            </div>

            <div class="form-group was-validated">
                <label class="form-label text-white" for="phone-number">Phone Number</label>
                <input class="form-control" type="text" id="phone-number" name="phone-number" placeholder="09XX XXX XXXX" pattern="^09\d{2} \d{3} \d{4}$" min="11" max="11" required>
                <div class="invalid-feedback"><small style="font-size: 12px; margin: 0%;">Please enter a valid phone number starting with 09 and following the pattern 0912 345 6783.</small></div>
                
            </div>
  
            <div class="form-group was-validated">
                <label class="form-label text-white" for="username">Username</label>
                <input class="form-control" type="text" id="username" name="username" placeholder="Enter username" pattern=".{4,}" required>
                <div class="invalid-feedback">
                    <small style="font-size: 12px;"> Password consist of at least 4 characters long.</small>
                </div> 
            </div>
          
            <div class="form-group was-validated">
                <label class="form-label text-white" for="password">Password</label>
                <input class="form-control" type="password" id="password" name="password" placeholder="Enter password" pattern=".{8,}" required>
                <div class="invalid-feedback">
                    <small style="font-size: 12px;"> Password consist of at least 8 characters long.</small>
                </div>
            </div>

          <button class="btn btn-primary w-100 mt-2 mb-2" name="submit" type="submit">SIGN UP <i class="bi bi-arrow-right"></i></button>
        </form>

        <div class="text-center">
          <a href="online-appointment.php" class="text-white" style="font-size: 12px;">Go to online appointment</a>
        </div>
    </div>
    

    <!-- Success alert modal -->
    <div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="successModalLabel">Success</h5>
          </div>
          <div class="modal-body">
            <p>Registration Successful</p>
          </div>
          <div class="modal-footer">
            <a href="sign-up.php" class="btn btn-primary">Okay</a>
          </div>
        </div>
      </div>
    </div>
    <!-- End of success alert modal -->
    <!-- Not registered alert modal -->
    <div id="unsuccessModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="unsuccessModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="unsuccessModalLabel">Unsucess</h5>
          </div>
          <div class="modal-body">
            <p>Username Has Already Taken</p>
          </div>
          <div class="modal-footer">
            <a href="sign-up.php" class="btn btn-primary">Close</a>
          </div>
        </div>
      </div>
    </div>
    <!-- End of success Not registered -->
</body>
</html>

<?php if (isset($_SESSION['success'])) { ?>
<script>
  $(document).ready(function() {
  $("#successModal").modal("show");
})
</script>
<?php
  unset($_SESSION['success']);
  exit();
  } else if (isset($_SESSION['unsuccess'])) {
  ?>
<script>
  $(document).ready(function() {
  $("#unsuccessModal").modal("show");
})
</script>
<?php
  unset($_SESSION['unsuccess']);
  exit();
  }
?>

<script>
    // Function to validate input and allow only alphabetic characters and hyphen
    function validateInput(inputElement) {
        let inputValue = inputElement.value;
        let sanitizedValue = inputValue.replace(/[^a-zA-Z\s-]/g, ''); // Keep only letters and hyphen
        sanitizedValue = sanitizedValue.toUpperCase(); // Convert to uppercase
        inputElement.value = sanitizedValue; // Update the input value
    }

    // Add event listeners to both Menu Name input fields
    const givenNameInput = document.getElementById('given-name');
    const lastNameInput = document.getElementById('last-name');

    givenNameInput.addEventListener('input', function() {
        validateInput(givenNameInput);
    });

    lastNameInput.addEventListener('input', function() {
        validateInput(lastNameInput);
    });

    function validateAccountInput(inputElement) {
    let inputValue = inputElement.value;
    let sanitizedValue = inputValue.replace(/[^a-zA-Z0-9\s\-_@]/g, ''); // Allow letters, numbers, spaces, hyphen, underscore, and at symbol
    inputElement.value = sanitizedValue; // Update the input value
    }

    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    usernameInput.addEventListener('input', function() {
        validateAccountInput(usernameInput);
    });

    passwordInput.addEventListener('input', function() {
        validateAccountInput(passwordInput);
    });

</script>