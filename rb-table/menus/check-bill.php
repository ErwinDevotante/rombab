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
    <link rel="stylesheet" href="../../node_modules/ionicons/css/ionicons.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../../style.css">
    <!-- JQuery -->
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-black">

    <!-- Image and text -->
	<?php 
    include '../../conn.php';
    include 'navbar.php';
    ?>

    <div>
    <div class="container-fluid text-center p-1 text-white">
        <h1>Bill Summary</h1>
        <h6><em><strong>*Note: This is not an official receipt; it only displays the total bill.</strong></em></h6>
    </div>

    <div class="container mt-4">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header bg-dark text-white text-center">
                            <h4><?=$row['name']; ?></h4>
                            <h5>Pax: <?=$customer['count']; ?></h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered text-white">
                                <thead>
                                    <tr>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <?php $promo =  mysqli_query($connection, "SELECT * FROM `promo_prices`");
                                        $row_promo = mysqli_fetch_array($promo); 
                                        $promo_bill = $row_promo['promo_price'] * $customer['count'];?>
                                        <td class="text-center"><?=$customer['count']; ?></td>
                                        <td><?=$row_promo['promos']; ?> <?=$row_promo['promo_price']; ?></td>
                                        <td>₱ <?= number_format($promo_bill, 2) ?></td>
                                    </tr>
                                    <?php
                                    $select_cart = mysqli_query($connection, "SELECT summary_products, summary_qty, summary_price FROM `summary_orders` WHERE summary_table_no = '$table' AND summary_status = '0' ORDER BY summary_products ASC");
                                    $othersBill = 0;
                                    $productQuantity = array(); // An associative array to store product quantities
                                    $totalothers = 0;
                                    $totalBill = 0;
                                    $totalPrice = 0;
                                    $product =  0;
                                    $quantity = 0;
                                    $price = 0;

                                    if (mysqli_num_rows($select_cart) > 0) {
                                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                            
                                            $product = $fetch_cart['summary_products'];
                                            $quantity = $fetch_cart['summary_qty'];
                                            $price = $fetch_cart['summary_price'];

                                            // Check if the product already exists in the array
                                            if (isset($productQuantity[$product])) {
                                                // If it does, add the quantity and total price to the existing entry
                                                $productQuantity[$product]['quantity'] += $quantity;
                                                //$productQuantity[$product]['totalPrice'] += $price;
                                            } else {
                                                // If it doesn't, create a new entry in the array
                                                $productQuantity[$product] = array('quantity' => $quantity, 'totalPrice' => $price);
                                            }
                                        }

                                        // Loop through the array and display product quantities and prices
                                        foreach ($productQuantity as $product => $data) {
                                            $quantity = $data['quantity'];
                                            $totalPrice = $data['totalPrice'];
                                            $totalothers = 0;

                                            echo '<tr>';
                                            echo '<td class="text-center">' . $quantity . '</td>';
                                            echo '<td>' .$product . '</td>';
                                            if ($totalPrice != '0'){
                                                $totalothers = $totalPrice * $quantity;
                                                echo '<td>₱ ' . number_format($totalothers, 2). '</td>';
                                            } else {
                                                echo '<td>-</td>';
                                            }
                                            echo '</tr>';

                                            // Add the product's total price to the overall bill
                                            $othersBill = $othersBill + $totalothers;
                                        }
                                    } else {
                                        echo "<div class='display-order text-center'><span>You don't have any orders yet.</span></div>";
                                    }
                                    
                                    $totalBill = $othersBill + $promo_bill;
                                    // Display the total bill
                                    echo '<tr>';
                                    echo '<td class="text-center"><h5><strong>TOTAL</strong></h5></td>';
                                    echo '<td></td>';
                                    echo '<td><h5><strong>₱ ' . number_format($totalBill, 2) . '</strong></h5></td>';
                                    echo '</tr>';
                                    ?>
                                </tbody>
                                
                            </table>
                            
                            <?php
                            $to_pay = $totalBill;
                            $seniorCount = isset($_POST['senior_no']) ? $_POST['senior_no'] : 0;
                            $pwdCount = isset($_POST['pwd_no']) ? $_POST['pwd_no'] : 0;
                            $bdayCount = isset($_POST['bday_no']) ? $_POST['bday_no'] : 0;
                            $afterDiscount = ($row_promo['promo_price'] * 0.20);
                            $bdayPromoDiscount = 0;
                            $totalDiscount = 0;

                            if(isset($_POST['to_pay'])) {
                                $totalDiscount = ($seniorCount + $pwdCount) * $afterDiscount;
                                
                                $to_pay = $totalBill - $totalDiscount;
                            
                                // Calculate discounts for birthday celebrants
                                
                                $bdayPromoDiscount = 0;
                            
                                if ($bdayCount >= 1 && $bdayCount <= 4 && $customer['count'] > $bdayCount) {
                                    $bdayPromoDiscount = ($row_promo['promo_price']); // Divide by 5 paying companions
                                    $bdayPromoDiscount *= $bdayCount; // Apply discount for the number of companions with birthdays
                                }

                                $to_pay = $totalBill - ($totalDiscount + $bdayPromoDiscount);
            
                            }
                            
                            ?>
                            <h6><em>*Note: Present a senior/PWD card or birthday proof to the crew. This will compute the total bill if your companion is eligible for a discount. The birthday promo is applicable with four (4) paying companions.</em></h6>
                            <form action="" method="post" enctype="multipart/form-data">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                            <label>No. of senior</label>
                                            <input type="number" name="senior_no" min="0" max="<?php echo $customer['count'];?>" class="form-control" value="<?php echo $seniorCount?>" oninput="validateInput(this)">
                                            <label>No. of PWD</label>
                                            <input type="number" name="pwd_no" min="0" max="<?php echo $customer['count'];?>" class="form-control" value="<?php echo $pwdCount?>" oninput="validateInput(this)">
                                            <?php if($customer['count'] >= 5) {?>
                                            <label>No. of birthday celebrant</label>
                                            <input type="number" name="bday_no" min="0" max="<?php echo $customer['count'];?>" class="form-control" value="<?php echo $bdayCount?>" oninput="validateInput(this)">
                                            <?php } ?>
                                            <button type="submit" name="to_pay" class="btn btn-primary mt-2 mb-3">Compute Total Bill</button>
                                        </div>
                                        <div class="col-md-6 mt-auto d-flex flex-column">
                                            <div class="ml-auto">
                                                <table class="table text-white">
                                                <tr>
                                                    <td><p class="mb-0"><small>Total</small></td>
                                                    <td><p class="mb-0 text-left"><small>₱<?php echo number_format($totalBill, 2)?></small></p></td>
                                                </tr>
                                                <tr>
                                                    <td><p class="mb-0"><small>Senior Disc. (x<?php echo $seniorCount;?>)</small></td>
                                                    <td><p class="mb-0 text-right"><small>₱<?php echo number_format($seniorCount * $afterDiscount, 2)?></small></p></td>
                                                </tr>
                                                <tr>
                                                    <td><p class="mb-0"><small>PWD Disc. (x<?php echo $pwdCount;?>)</small></td>
                                                    <td><p class="mb-0 text-right"><small>₱<?php echo number_format($pwdCount * $afterDiscount, 2)?></small></p></td>
                                                </tr>
                                                <tr>
                                                    <td><p class="mb-0"><small>Bday Promo (x<?php echo $bdayCount;?>)</small></td>
                                                    <td><p class="mb-0 text-right">₱<small><?php echo number_format($bdayPromoDiscount, 2)?></small></p></td>
                                                </tr>
                                                <tr>
                                                    <td><p class="mb-0"><small>Total Discount</small></td>
                                                    <td><p class="mb-0 text-right">₱<small><?php echo number_format($totalDiscount + $bdayPromoDiscount, 2)?></small></p></td>
                                                </tr>
                                                </table>
                                                <h5 name="to_pay"><strong>TO PAY ➜ ₱<?php echo number_format($to_pay, 2 );?></strong></h5>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                    <?php if (mysqli_num_rows($select_cart) > 0) { ?>
                        <div class="done-btn text-center">
                        <h6 class="text-white"><em>*Note: Please look for the crew to settle the bill and log-out the table.</em></h6>
                         <a href="#" onclick="showSurveyModal()" class="btn btn-primary">Bill-out <i class="ion-arrow-right-c"></i></a>
                        <!--<button id="surveyButton" class="btn btn-primary" onclick="showSurveyModal()">Bill-out <i class="ion-arrow-right-c"></i></button> -->
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php
        //$check_tableNo = $row['user_id'];
        //$check_userId = $customer['appointment_id'];
        //$check_query = mysqli_query($connection, "SELECT * FROM survey WHERE survey_table_no = $check_tableNo AND survey_user_id = $check_userId");
        //if (mysqli_num_rows($check_query) > 0) {
         // If there are results in the query, call confirmLogout()
            //echo '<div class="done-btn text-center">';
            //echo '<h6 class="text-white"><em>*Note: Please look for the crew to settle the bill and log-out the table.</em></h6>';
            //echo '<a href="#" onclick="confirmLogout()" class="btn btn-primary">Bill-out <i class="ion-arrow-right-c"></i></a>';
            //echo '</div>';
        //} else {
        // If there are no results, call submitsurvey()
            //echo '<div class="done-btn text-center">';
            //echo '<h6 class="text-white"><em>*Note: Please look for the crew to settle the bill and log-out the table.</em></h6>';
            //echo '<a href="#" onclick="submitSurvey()" class="btn btn-primary">Bill-out <i class="ion-arrow-right-c"></i></a>';
            //echo '</div>';
            //}
        ?>

    
    <footer class="main-footer bg-black text-center">
    <div class="float-right d-none d-sm-block">
        <!-- Additional footer content or links can go here -->
    </div>
    Romantic Baboy – SM City Sta. Rosa Branch
    &copy; <?php echo date("Y"); ?>
    </footer>
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

    <!-- Survey Modal (hidden by default) -->
    <div id="surveyModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rate Your Experience</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Please rate your dining experience:</p>
                    <div>
                        <?php for ($i = 1; $i <= 10; $i++) { ?>
                            <button type="button" class="btn btn-primary" onclick="submitSurvey(<?php echo $i; ?>)"><?php echo $i; ?></button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to show the survey modal
        function showSurveyModal() {
            // Check if a survey record exists in the database
            var check_tableNo = <?php echo $row['user_id']; ?>;
            var check_userId = <?php echo $customer['appointment_id']; ?>;

            $.ajax({
                type: "POST",
                url: "cart-update.php", // Use the correct URL for checking the survey
                data: {
                    tableNo: check_tableNo,
                    userId: check_userId
                },
                success: function(response) {
                    if (response === 'existing') {
                        // If a survey record exists, proceed to logout
                        confirmLogout();
                    } else {
                        // If a survey record doesn't exist, show the survey modal
                        $('#surveyModal').modal('show');
                    }
                }
            });
        }

        // Function to submit the user's rating to the database
        function submitSurvey(rating) {
            var submit_tableNo = <?php echo $row['user_id']; ?>;
            var submit_userId = <?php echo $customer['appointment_id']; ?>;

            $.ajax({
                type: "POST",
                url: "cart-update.php", // Create this PHP file to insert the rating into the database
                data: {
                    submit_tableNo: submit_tableNo,
                    submit_userId: submit_userId,
                    rating: rating
                },
                success: function(response) {
                    // Close the survey modal
                    $('#surveyModal').modal('hide');
                    //window.location = "check-bill.php";
                    // Proceed to logout
                    confirmLogout();
                }
            });
        }

        // JavaScript function to handle logout
        function confirmLogout() {
            // Show the password input dialog
            $('#passwordDialog').modal('show');
        }

        function checkPassword() {
        // Get the entered password
        var enteredPassword = document.getElementById('passwordInput').value;

        // Check if the entered password is correct
        if (enteredPassword === "<?php echo $row['password'];?>") {
            // Send an AJAX request to insert data into billing_history
                var to_pay = <?php echo $to_pay; ?>;
                var tableNo = <?php echo $row['user_id']; ?>;
                var userId = <?php echo $customer['appointment_id']; ?>;
            $.ajax({
                type: "POST",
                url: "cart-update.php", // Create this PHP file to handle the database operation
                data: {
                    to_pay: to_pay,
                    userId: userId,
                    tableNo: tableNo
                },
                success: function(response) {
                    window.location.href = "../../../log-out.php";
                }
            });
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

        function validateInput(inputElement) {
            // Get the entered value and the maximum allowed value
            const enteredValue = parseInt(inputElement.value);
            const maxValue = parseInt(inputElement.getAttribute('max'));

            if (isNaN(enteredValue) || enteredValue < 0) {
                // Display an error message and reset the input to the maximum value
                alert('Please enter a non-negative number.');
                inputElement.value = maxValue;
            } else if (enteredValue > maxValue) {
                // Display an error message and reset the input to the maximum value
                alert('Value cannot exceed ' + maxValue);
                inputElement.value = maxValue;
            }
        }
    </script>
</body>
</html>