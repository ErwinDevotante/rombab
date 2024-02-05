<?php
session_start();
include 'conn.php';
if(!empty($_SESSION["user_id"])){
  header("Location: index.php");
}
if (isset($_POST["submit"])) {
  $username = sanitizeInput($_POST["username"]);
  $password = sanitizeInput($_POST["password"]);
  $result = mysqli_query($connection, "SELECT * FROM users WHERE username = '$username'");
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
      if ($password == $row['password'] && $row['user_id'] !== null) {
          $_SESSION["login"] = true;
          $_SESSION["user_id"] = $row["user_id"];
          date_default_timezone_set('Asia/Manila');
          $user_log = $_SESSION["user_id"];
          $action = "Log-In";
          $dateTime = date('Y-m-d H:i:s');
          if ($row['user_role'] != 4) {
          mysqli_query($connection, "INSERT INTO activity_log (log_user_id, action, date_time) VALUES ('$user_log', '$action', '$dateTime')");
          }
          $_SESSION['success'] = true;
      } else {
          $_SESSION['unsuccess'] = true;
      }
  } else {
      $_SESSION['unsuccess'] = true;
  }
}

// Delete section for archives
date_default_timezone_set('Asia/Manila');
$deleteThreshold = date('Y-m-d H:i:s', strtotime('-30 days'));

// Deleting data from summary_orders
//mysqli_query($connection, "DELETE FROM summary_orders WHERE inserted_at <= '$deleteThreshold'");

// Deleting data from billing_notif
mysqli_query($connection, "DELETE FROM billing_notif WHERE time_date <= '$deleteThreshold'");

// Deleting data from inventory_archive
mysqli_query($connection, "DELETE FROM inventory_archive WHERE archived_at <= '$deleteThreshold'");

// Deleting data from log_reports
mysqli_query($connection, "DELETE FROM log_reports WHERE archived_at <= '$deleteThreshold' AND as_archived = '1'");

// Deleting data from daily_reports
mysqli_query($connection, "DELETE FROM daily_reports WHERE archived_at <= '$deleteThreshold' AND as_archived = '1'");

// Deleting data from menu_archive
mysqli_query($connection, "DELETE FROM menus_archive WHERE archived_at <= '$deleteThreshold'");

// Deleting data from appointment, appointment_history, and billing_history
mysqli_query($connection, "DELETE appointment, appointment_history, billing_history
                            FROM appointment_history
                            INNER JOIN users ON users.user_id = appointment_history.table_history_id
                            INNER JOIN appointment ON appointment.appointment_id = appointment_history.appointment_user_id
                            INNER JOIN billing_history ON billing_history.user_id = appointment_history.appointment_user_id
                            WHERE appointment_history.archived_at <= '$deleteThreshold' AND appointment_history.as_archived = '1'
                            AND appointment.appointment_session = '2'");

// Delete data from orders
mysqli_query($connection, "DELETE FROM orders WHERE time_date <= '$deleteThreshold'");

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
            <img src="assets/rombab-logo.png" alt="Image" class="img-fluid mb-4"  width="200" height="200">
            </div>
        </div>
        
        <form class="needs-validation" method="POST">
          <!-- <div class="form-group  was-validated">
            <label class="form-label text-white" for="roles">Select Log-in Options</label>
              <select name="roles" class="form-control" id="roles" required>
                <option value="" hidden>--Select Options Here--</option>
                <option value="1">Super Admin</option>
                <option value="2">Admin</option>
                <option value="3">Kitchen</option>
                <option value="4">Table</option>
                <option value="5">Appointment Staff</option>
              </select>
          </div> -->
  
          <div class="form-group was-validated">
            <label class="form-label text-white" for="username">USERNAME</label>
            <input class="form-control bg-black text-white" type="text" id="username" name="username" placeholder="Enter username" pattern=".{4,}" required>
              <div class="invalid-feedback">
                <small style="font-size: 12px;"> Username consist of at least 4 characters long.</small>
              </div> 
          </div>
          

          <div class="form-group was-validated">
            <label class="form-label text-white" for="password">PASSWORD</label>
            <input class="form-control bg-black text-white" type="password" id="password" name="password" placeholder="Enter password" pattern=".{8,}" required>
              <div class="invalid-feedback">
                <small style="font-size: 12px;"> Password consist of at least 8 characters long.</small>
              </div>
          </div>
        
          <button class="btn btn-primary w-100 mt-2 mb-2" name="submit" type="submit"><b>LOG IN <i class="bi bi-arrow-right"></i></b></button>
        </form>

        <div class="text-center">
          <!-- <a href="online-appointment.php" class="text-white" style="font-size: 12px;">Create an online appointment</a>-->
          <a href="create-online-appointment.php" class="text-white" style="font-size: 12px;">Online appointment</a>
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
            <p>Log-in successfully!</p>
          </div>
          <div class="modal-footer">
            <?php $roles = $row["user_role"];
              if ($roles == '1') { //superadmin?>
              <a href="rb-admin/admin-index.php" class="btn btn-primary">Proceed</a>
            <?php } elseif ($roles == '2') { //admin?>
              <a href="rb-admin/admin-index.php" class="btn btn-primary">Proceed</a>
            <?php } elseif ($roles == '3') { //kitchen?>
              <a href="rb-kitchen/kitchen-index.php" class="btn btn-primary">Proceed</a>
            <?php } elseif ($roles == '4') {?>
              <a href="rb-table/table-index.php" class="btn btn-primary">Proceed</a>
            <?php } elseif ($roles == '5') {?>
              <a href="rb-admin/admin-index.php" class="btn btn-primary">Proceed</a>
            <?php } ?>
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
            <p>Not Registered Account!</p>
          </div>
          <div class="modal-footer">
            <a href="index.php" class="btn btn-primary">Close</a>
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

    //passwordInput.addEventListener('input', function() {
        //validateAccountInput(passwordInput);
    //});
</script>