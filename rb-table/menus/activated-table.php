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
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
                <a class="btn btn-primary" id="viewTutorial1">Tutorial <i class="bi bi-info-circle-fill"></i></a>
                <a href="check-bill.php" class="btn btn-primary">Check Bill <i class="bi bi-receipt"></i></a>
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

    <div id="tutorialModal1" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Welcome to Tabletop Ordering Kiosk!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3 text-center">
                            <img src="../../assets/rombab-logo.png" alt="Tabletop Ordering Kiosk" class="img-fluid mx-auto" style="width: 150px; height: 150px;">
                        </div>
                        <div class="col-9">
                            <h5>Thank you for choosing our Romantic Baboy Tabletop Ordering Kiosk! We are delighted to have you here.
                            Our user-friendly interface allows you to browse and order your favorite items with ease.
                            Enjoy a seamless dining experience with our innovative ordering system.</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-primary" disabled><i class="bi bi-caret-left-fill"></i> Previous</button>
                    <span class="mx-auto">1/4</span>
                    <button class="btn btn-primary" onclick="showNextModal('tutorialModal1', 'tutorialModal2')">Proceed <i class="bi bi-caret-right-fill"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div id="tutorialModal2" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menu Tutorial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3 text-center">
                            <img src="../../assets/tutorial-menu.gif" alt="Tabletop Ordering Kiosk" class="img-fluid mx-auto" style="width: 200px; height: 200px;">
                        </div>
                        <div class="col-9">
                            <p>On the left side, explore our menu categories: Samgyupsal, Side Dishes, Others (fees may apply), and New Offers (fees may apply), each offering a delightful array of choices for your dining experience.</p>
                            <ul>
                            <li><a class="btn btn-primary btn-sm">SAMGYUPSAL</a> - Enjoy the savory and flavorful experience of Korean grilled pork belly, commonly known as Samgyupsal, offering a delicious BBQ dining option.</li>
                            <li><a class="btn btn-primary btn-sm">SIDE DISHES</a> -  Our side dishes include a variety of complementary items served alongside your main course, enhancing your overall dining experience with diverse flavors.</li>
                            <li><a class="btn btn-primary btn-sm">OTHERS</a> -  Explore our selection of beverages under the 'Others' category, offering a range of refreshing drinks to complement your meal.</li>
                            <li><a class="btn btn-primary btn-sm">NEW OFFERS</a> - Discover our latest offerings under 'New Offers,' featuring additional menu items with a fee and exclusive limited-time offers for a unique and exciting culinary experience.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-primary" onclick="showPreviousModal('tutorialModal2', 'tutorialModal1')"><i class="bi bi-caret-left-fill"></i> Previous</button>
                    <span class="mx-auto">2/4</span>
                    <button class="btn btn-primary" onclick="showNextModal('tutorialModal2', 'tutorialModal3')">Proceed <i class="bi bi-caret-right-fill"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div id="tutorialModal3" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buttons Tutorial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Effortlessly navigate your dining choices with the intuitive buttons on our Tabletop Ordering Kiosk, providing a user-friendly interface that ensures a smooth and enjoyable ordering experience.</p>
                    <ul>
                        <li>
                            <a><i class="bi bi-bell-fill large-icon"></i>
                            <span class="position-absolute translate-middle badge rounded-pill bg-red">0</span>
                            </a>
                            - Stay informed with real-time updates! Our notification feature keeps you in the loop, providing instant alerts about your order status.
                        </li>
                        <li class="m-1">
                            <a><i class="bi bi-cart-fill large-icon"></i>
                            <span class="position-absolute translate-middle badge rounded-pill bg-red">0</span>
                            </a>
                            - Your personalized dining cart awaits! Easily manage and review your selected items, customize your order, and proceed to a seamless checkout – all at your fingertips.
                        </li>
                        <li>
                            <a>
                            <i class="bi bi-menu-button large-icon"></i>
                            - Explore a world of flavors with our digital menu! From savory main courses to delightful sides, our user-friendly menu on the Tabletop Ordering Kiosk lets you effortlessly browse and discover an array of delicious options tailored to your taste.   
                            </a>
                        </li>
                        <li><a class="btn btn-primary btn-sm">Tutorial <i class="bi bi-info-circle-fill"></i></a>
                            - Navigate through our user-friendly interface with ease using the Tutorial button for step-by-step guidance. 
                        </li>
                        <li><a class="btn btn-primary btn-sm">Check Bill <i class="bi bi-receipt"></i></a>
                            - When you're ready to wrap up your dining experience, the Check Bill button awaits, allowing you to conveniently review your order, see the total bill, and take advantage of any available promotions for a delightful and transparent payment experience.
                        </li>
                    </ul>
                    
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-primary" onclick="showPreviousModal('tutorialModal3', 'tutorialModal2')"><i class="bi bi-caret-left-fill"></i> Previous</button>
                    <span class="mx-auto">3/4</span>
                    <button class="btn btn-primary" onclick="showNextModal('tutorialModal3', 'tutorialModal4')">Proceed <i class="bi bi-caret-right-fill"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div id="tutorialModal4" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enjoy using the Tabletop Ordering Kiosk!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                <h5>Before dining out, it is essential to summon a service staff at Romantic Baboy. The staff will promptly log out your table and provide assistance in settling the bill, ensuring a seamless and enjoyable dinning experience.</h5>
                <p><i class="bi bi-emoji-smile-fill large-icon"></i></p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-primary" onclick="showPreviousModal('tutorialModal4', 'tutorialModal3')"><i class="bi bi-caret-left-fill"></i> Previous</button>
                    <span class="mx-auto">4/4</span>
                    <button class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">Close</i></button>
                </div>
            </div>
        </div>
    </div>
   

    <script>

        $(document).ready(function() {
            $('#viewTutorial1').click(function() {
                $('#tutorialModal1').modal('show');
            });  
        });

        function showPreviousModal(currentModalId, previousModalId) {
            $('#' + currentModalId).modal('hide');
            $('#' + previousModalId).modal('show');
        }

        function showNextModal(currentModalId, nextModalId) {
            $('#' + currentModalId).modal('hide');
            $('#' + nextModalId).modal('show');
        }

        function goBack() {
            window.history.back();
        }

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