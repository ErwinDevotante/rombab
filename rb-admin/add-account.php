<?php 
$a = 8;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

    if(isset($_POST["submit"])){
        $role = $_POST["roles"];
        $name = $_POST["name"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm-password"];
        $duplicate = mysqli_query($connection, "SELECT * FROM users WHERE username = '$username'");
        if(mysqli_num_rows($duplicate) > 0){
            echo
            "<script> alert('Username Has Already Taken'); </script>";
        }
        else{
            if($password == $confirm_password){
            $query = "INSERT INTO users VALUES('','$name','$username','$password','$role', '0')";
            mysqli_query($connection, $query);
            echo
            "<script> alert('Registration Successful'); </script>";
            }
            else{
            echo
            "<script> alert('Password Does Not Match'); </script>";
            }
        }
        unset($_POST);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Add Account</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="../../assets/rombab-logo.png">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../../node_modules/admin-lte/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../../node_modules/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- JQuery -->
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../../node_modules/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../node_modules/admin-lte/js/adminlte.js"></script>
    
    <script src="https://kit.fontawesome.com/fe96d845ef.js" crossorigin="anonymous"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" >

        <?php 
        include "top-bar.php";
        include "side-bar.php";
        ?>

        <div class="content-wrapper bg-black">
            <div class="content p-4">

            <div class="container-fluid text-center">
                <h1>Add Account</h1>
            </div>

            <section class="h-100 h-custom">
                <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-8 col-xl-6">
                    <div class="card rounded-3">
                    <img src="../assets/background-pic.jpg"
                        class="w-100" style="border-top-left-radius: .3rem; border-top-right-radius: .3rem; height: 250px;"
                        alt="Photo">
                    <div class="card-body p-4 p-md-5">

                        <form class="px-md-2" class="needs-validation" method="POST">

                        <div class="form-group was-validated">
                        <label class="form-label text-black" for="roles">Select Account Options</label>
                                <select name="roles" class="form-control" id="roles" required>
                                    <option value="" hidden>--Select options here--</option>
                                    <option value="2">Admin</option>
                                    <option value="3">Kitchen</option>
                                    <option value="4">Table</option>
                                </select>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-group was-validated flex-fill mb-0">
                            <label class="form-label text-black">Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-group was-validated flex-fill mb-0">
                            <label class="form-label text-black">Username</label>
                            <input type="text" id="username" name="username" class="form-control" pattern=".{4,}" required>
                            <div class="invalid-feedback">
                                Username must be at least 4 characters long.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-group was-validated flex-fill mb-0">
                            <label class="form-label text-black">Password</label>
                            <input type="password" id="password" name="password" class="form-control" pattern=".{8,}" required>
                                <div class="invalid-feedback">
                                Password must be at least 8 characters long.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-group was-validated flex-fill mb-0">
                            <label class="form-label text-black">Confirm password</label>
                            <input type="password" id="confirm-password" name="confirm-password" class="form-control" pattern=".{8,}" required>
                                <div class="valid-feedback">
                                Must be the same as password.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                            <button type="submit" class="btn btn-danger btn-lg" name="submit">Register</button>
                        </div>

                        </form>

                    </div>
                    </div>
                </div>
                </div>
            </section>

            </div>
        </div>
    </div>
</body>
</html>

<script>

</script>