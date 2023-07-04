<?php 
include '../../conn.php';
include '../table-auth.php';
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
</head>
<body class="bg-black">

    <!-- Image and text -->
	<?php include 'navbar.php';?>

    <div class="container-fluid text-center p-1 text-white">
        <h1>SAMGYUPSAL MENU</h1>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="card-columns">
            <?php 
                $result_tb = mysqli_query($connection, "SELECT * FROM menus
                                        WHERE menu_category = 'Samgyupsal' ");
                    while ($row = mysqli_fetch_array($result_tb)) { ?> 
                        <div class="card card-body card-red text-center mb-3">
                            <div class="img"><img src ='../../rb-admin/menu-images/<?php echo $row["menu_image"]; ?>' width="175" height="150" class="img-fluid rounded-top"></div>
                            <div class="productname bg-white text-black rounded-bottom"><h5 class="text-truncate"><?php echo $row["menu_name"]; ?></h5></div>
                            <div class="add-btn"><button class="btn btn-md btn-outline-danger text-white w-100 mt-1">ADD TO CART</button></div>
                        </div>
                    <?php }  ?>
            </div>
        </div>
    </div>

</body>
</html>