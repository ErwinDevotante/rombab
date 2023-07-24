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
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../../style.css">
    <!-- JQuery -->
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://kit.fontawesome.com/fe96d845ef.js" crossorigin="anonymous"></script>
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
                    <a href="#" class="btn btn-primary w-100 h-75 mb-5"><h1 class="mt-3">OTHERS</h1></a>
                    </div>
               
                    <div class="col-md-12 ">
                    <a href="#" class="btn btn-primary w-100 h-75 mb-5"><h1 class="mt-3">NEW OFFERS</h1></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>