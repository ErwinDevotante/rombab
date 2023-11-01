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
</head>
<body class="bg-black">

    <!-- Image and text -->
	<?php 
    include '../../conn.php';
    include 'navbar.php';
    ?>

    <div class="container-fluid text-center p-1 text-white">
        <h1>Bill Summary</h1>
        <h6><em>*Note: This is not an official receipt; it only displays the total bill.</em></h6>
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
                                    $totalBill = 0;
                                    $productQuantity = array(); // An associative array to store product quantities

                                    if (mysqli_num_rows($select_cart) > 0) {
                                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                            $product = $fetch_cart['summary_products'];
                                            $quantity = $fetch_cart['summary_qty'];
                                            $price = $fetch_cart['summary_price'];

                                            // Check if the product already exists in the array
                                            if (isset($productQuantity[$product])) {
                                                // If it does, add the quantity and total price to the existing entry
                                                $productQuantity[$product]['quantity'] += $quantity;
                                                $productQuantity[$product]['totalPrice'] += $price;
                                            } else {
                                                // If it doesn't, create a new entry in the array
                                                $productQuantity[$product] = array('quantity' => $quantity, 'totalPrice' => $price);
                                            }
                                        }

                                        // Loop through the array and display product quantities and prices
                                        foreach ($productQuantity as $product => $data) {
                                            $quantity = $data['quantity'];
                                            $totalPrice = $data['totalPrice'];

                                            echo '<tr>';
                                            echo '<td class="text-center">' . $quantity . '</td>';
                                            echo '<td>' . $product . '</td>';
                                            if ($totalPrice != 0){
                                                echo '<td>₱ ' . number_format($totalPrice * $quantity, 2) . '</td>';
                                            } else {
                                                echo '<td>-</td>';
                                            }
                                            echo '</tr>';

                                            // Add the product's total price to the overall bill
                                            $totalBill += $totalPrice;
                                        }
                                    } else {
                                        echo "<div class='display-order text-center'><span>You don't have any orders yet.</span></div>";
                                    }

                                    // Display the total bill
                                    echo '<tr>';
                                    echo '<td class="text-center"><h5><strong>TOTAL</strong></h5></td>';
                                    echo '<td></td>';
                                    echo '<td><h5><strong>₱ ' . number_format($totalBill + $promo_bill, 2) . '</strong></h5></td>';
                                    echo '</tr>';
                                    ?>
                                </tbody>
                                
                            </table>
                            
                        </div>
                        
                    </div>
                    <form action="" method="post">
                        <div class="done-btn text-center">
                        <h6><em>*Note: Please look for the crew to settle the bill.</em></h6>
                        <a href="#" onclick="confirmLogout()" class="btn btn-primary">Bill-out <i class="ion-arrow-right-c"></i></a>
                        </div>
                    </form>
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
            if (enteredPassword === "123456789") {
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