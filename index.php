<?php
include 'conn.php';
session_start();
if(!empty($_SESSION["user_id"])){
  header("Location: index.php");
}
if(isset($_POST["submit"])){
  $username = $_POST["username"];
  $password = $_POST["password"];
  $roles = $_POST["roles"];
  $result = mysqli_query($connection, "SELECT * FROM users WHERE username = '$username' and user_role = '$roles'");
  $row = mysqli_fetch_assoc($result);
  if(mysqli_num_rows($result) > 0){
    if($password == $row['password']){
      $_SESSION["login"] = true;
      $_SESSION["user_id"] = $row["user_id"];
      if($roles == 1){ //super_admin
        echo '<script type="text/javascript">'; 
        echo 'alert("Log-in Sucessfully!");'; echo 'window.location.href = "rb-admin/admin-index.php";';
        echo '</script>';
      }
      else if($roles == 2){ //admin
        echo '<script type="text/javascript">'; 
        echo 'alert("Log-in Sucessfully!");'; echo 'window.location.href = "rb-admin/admin-index.php";';
        echo '</script>';
      }
      else if($roles == 3){ //kitchen
        echo '<script type="text/javascript">'; 
        echo 'alert("Log-in Sucessfully!");'; echo 'window.location.href = "rb-kitchen/kitchen-index.php";';
        echo '</script>';
      }
      else if($roles == 4){ //table
        echo '<script type="text/javascript">'; 
        echo 'alert("Log-in Sucessfully!");'; echo 'window.location.href = "rb-table/table-index.php";';
        echo '</script>';
      } else if($roles == 5){ //appointment
        echo '<script type="text/javascript">'; 
        echo 'alert("Log-in Sucessfully!");'; echo 'window.location.href = "rb-admin/admin-index.php";';
        echo '</script>';
      }
    }
  }
  else{
    echo
    "<script> alert('Not Registered Account!'); </script>";
  }
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
    <link rel="icon" type="image/x-icon" href="../assets/rombab-logo.png">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="d-flex align-items-center justify-content-center bg-grill">
    <div class="login">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
            <img src="assets/rombab-logo.png" alt="Image" class="img-fluid mb-4"  width="200" height="200">
            </div>
        </div>
        
        <form class="needs-validation" method="POST">
        <div class="form-group  was-validated">
            <label class="form-label text-white" for="roles">Select Log-in Options</label>
                    <select name="roles" class="form-control" id="roles" required>
                        <option value="" hidden>--Select Options Here--</option>
                        <option value="1">Super Admin</option>
                        <option value="2">Admin</option>
                        <option value="3">Kitchen</option>
                        <option value="4">Table</option>
                        <option value="5">Appointment Staff</option>
                    </select>
            </div>

            <div class="form-group was-validated">
                <label class="form-label text-white" for="username">Email address</label>
                <input class="form-control" type="text" id="username" name="username" required>
              
            </div>

            <div class="form-group was-validated">
                <label class="form-label text-white" for="password">Password</label>
                <input class="form-control" type="password" id="password" name="password" required>
            </div>
        
            <input class="btn btn-primary w-100" name="submit" type="submit" value="SIGN IN">
        </form>
    </div>
</body>
</html>