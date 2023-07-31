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
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="/style.css">
    <!-- JQuery -->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
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
                if ($row['session_tb'] == "3"){
                    echo '<a href="menus/activated-table.php" class="btn btn-lg btn-primary fw-bold border-white">Tap to start</a>';
                }
                else if($row['session_tb'] == "2") {
                    echo '<a href="deactivated-table.php" class="btn btn-lg btn-primary fw-bold border-white">Tap to start</a>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>