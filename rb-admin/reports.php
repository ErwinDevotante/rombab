<?php 
$a = 1;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);
    date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Generate Report</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="../assets/rombab-logo.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../node_modules/admin-lte/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../node_modules/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../node_modules/ionicons/css/ionicons.min.css">
    <!-- JQuery -->
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../node_modules/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../node_modules/admin-lte/js/adminlte.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css"/>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed bg-black">
    <div class="wrapper" >

        <?php 
        include "top-bar.php";
        include "side-bar.php";
        ?>

        <div class="content-wrapper bg-black mt-5">
            <div class="content p-4">
                <div class="container-fluid text-center p-4">
                    <h1>Generate Reports</h1>
                    <p><small>Generating daily, weekly, monthly, and annual reports is essential for tracking, analyzing, and communicating a company's performance.</small></p>
                </div>

                <form method="post" action="">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="duration">Select Duration:</label>
                            <select class="form-control" id="duration" name="duration">
                                <option hidden value="">--Select Here--</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="annually">Annually</option>
                            </select>
                        </div>

                        <div class="form-group col-md-8">
                            <div id="daily" class="date-input form-group col-md-6">
                                <label for="daily_date">Select Date:</label>
                                <input type="date" class="form-control" id="daily_date" name="daily_date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" min="2022-11-09">
                            </div>

                            <div id="weekly" class="date-input form-group col-md-6">
                                <label for="weekly_start_date">Select Start Date:</label>
                                <input type="date" class="form-control" id="weekly_start_date" name="weekly_start_date" value="<?php echo date("Y-m-d");?>" max="<?php echo date("Y-m-d"); ?>" min="2022-11-09">
                            </div>

                            <div id="monthly" class="date-input form-group col-md-6">
                                <label for="monthly_month">Select Month:</label>
                                <input type="month" class="form-control" id="monthly_month" name="monthly_month" value="<?php echo date("Y-m"); ?>" max="<?php echo date("Y-m"); ?>" min="2022-11">
                            </div>

                            <div id="annually" class="date-input form-group col-md-6">
                                <label for="annually_year">Select Year:</label>
                                <input type="number" class="form-control" id="annually_year" name="annually_year" maxlength="4" required value="<?php echo date("Y"); ?>" oninput="validateInput(this)">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="selected_duration" id="selected_duration" value="<?php echo isset($_POST["duration"]) ? $_POST["duration"] : ''; ?>">
                    <input type="hidden" name="selected_annually_year" id="selected_annually_year" value="<?php echo isset($_POST["annually_year"]) ? $_POST["annually_year"] : ''; ?>">
                    <input type="hidden" name="selected_monthly_month" id="selected_monthly_month" value="<?php echo isset($_POST["monthly_month"]) ? $_POST["monthly_month"] : ''; ?>">
                    <input type="hidden" name="selected_weekly_start_date" id="selected_weekly_start_date" value="<?php echo isset($_POST["weekly_start_date"]) ? $_POST["weekly_start_date"] : ''; ?>">
                    <input type="hidden" name="selected_daily_date" id="selected_daily_date" value="<?php echo isset($_POST["daily_date"]) ? $_POST["daily_date"] : ''; ?>">

                    <button type="submit" name="show_table" class="btn bg-red">SHOW</button>
                </form>

                <?php
                if (isset($_POST["show_table"])) {

                    // Process the form submission
                    if (!empty($_POST["duration"])) {
                        $selectedOption = $_POST["duration"];

                        // Get the selected date based on the chosen duration
                        if ($selectedOption == 'daily') {
                            $title = 'Daily';
                            $choosenDate = $_POST['daily_date'];

                            $menu_query = "SELECT summary_products, summary_qty, summary_price, inserted_at, summary_status 
                                        FROM `summary_orders` 
                                        WHERE DATE(inserted_at) = '$choosenDate' AND summary_status = '1' 
                                        ORDER BY summary_products ASC";

                            $log_reports_query = "SELECT * FROM log_reports
                                        LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                        LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                        WHERE DATE(date_time) = '$choosenDate'";

                            $billing_query = "SELECT total_bill, date_time, pwddisc, seniordisc, bdaydisc FROM billing_history 
                                        WHERE DATE(date_time) = '$choosenDate'";

                            $appointment_query = "SELECT count, date, appointment_session FROM appointment
                                        WHERE appointment_session = '2' AND date = '$choosenDate'";

                            $survey_query = "SELECT date, survey_answer FROM survey
                                        WHERE date = '$choosenDate'";

                            echo "<h2 class='mt-3'>Romantic Baboy $title Report</h2>";
                            echo "Date: ". date('F j, Y', strtotime($choosenDate))."<br>";
                        
                        } elseif ($selectedOption == 'weekly') {
                            // For weekly, use the start date and consider the entire week
                            $title = 'Weekly';
                            $startOfWeek = $_POST['weekly_start_date'];
                            $endOfWeek = date('Y-m-d', strtotime($startOfWeek . ' +6 days'));

                            $menu_query = "SELECT summary_products, summary_qty, summary_price, inserted_at, summary_status 
                                        FROM `summary_orders` 
                                        WHERE DATE(inserted_at) BETWEEN '$startOfWeek' AND '$endOfWeek' AND summary_status = '1' 
                                        ORDER BY summary_products ASC";
                            
                            $log_reports_query = "SELECT * FROM log_reports
                                        LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                        LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                        WHERE DATE(date_time) BETWEEN '$startOfWeek' AND '$endOfWeek'";

                            $billing_query = "SELECT total_bill, date_time, pwddisc, seniordisc, bdaydisc FROM billing_history 
                                        WHERE DATE(date_time) BETWEEN '$startOfWeek' AND '$endOfWeek'";

                            $appointment_query = "SELECT count, date, appointment_session FROM appointment
                                        WHERE appointment_session = '2' AND date BETWEEN '$startOfWeek' AND '$endOfWeek'";

                            $survey_query = "SELECT date, survey_answer FROM survey
                                        WHERE date BETWEEN '$startOfWeek' AND '$endOfWeek'";
                            
                            echo "<h2 class='mt-3'>Romantic Baboy $title Report</h2>";
                            echo "Start of Week: ". date('F j, Y', strtotime($startOfWeek))."<br>";
                            echo "End of Week: ". date('F j, Y', strtotime($endOfWeek))."<br>";

                        
                        } elseif ($selectedOption == 'monthly') {
                            // For monthly, use the selected month and consider the entire month
                            $title = 'Monthly';
                            $selectedMonthYear = $_POST['monthly_month'];
                            
                            $selectedYear = date('Y', strtotime($selectedMonthYear));
                            $selectedMonth = date('m', strtotime($selectedMonthYear));

                            // Calculate the last day of the month
                            $lastDayOfMonth = date('t', strtotime($selectedMonthYear));

                            // Adjust date condition for the entire month
                            $endOfMonth = $selectedYear . '-' . $selectedMonth . '-' . $lastDayOfMonth;

                            $menu_query = "SELECT summary_products, summary_qty, summary_price, inserted_at, summary_status 
                                            FROM `summary_orders` 
                                            WHERE DATE(inserted_at) BETWEEN '$selectedMonthYear-01' AND '$endOfMonth' AND summary_status = '1' 
                                            ORDER BY summary_products ASC";
                                                        
                            $log_reports_query = "SELECT * FROM log_reports
                                                LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                                LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                                WHERE DATE(date_time) BETWEEN '$selectedMonthYear-01' AND '$endOfMonth'";

                            $billing_query = "SELECT total_bill, date_time, pwddisc, seniordisc, bdaydisc FROM billing_history 
                                            WHERE DATE(date_time) BETWEEN '$selectedMonthYear-01' AND '$endOfMonth'";

                            $appointment_query = "SELECT count, date, appointment_session FROM appointment
                                                WHERE appointment_session = '2' AND date BETWEEN '$selectedMonthYear-01' AND '$endOfMonth'";

                            $survey_query = "SELECT date, survey_answer FROM survey
                                            WHERE date BETWEEN '$selectedMonthYear-01' AND '$endOfMonth'";


                            echo "<h2 class='mt-3'>Romantic Baboy $title Report</h2>";
                            echo "Start of Month: ". date('F j, Y', strtotime($selectedMonthYear))."<br>";
                            echo "End of Month: ". date('F j, Y', strtotime($endOfMonth))."<br>";
                        
                        } elseif ($selectedOption == 'annually') {
                            // For annually, use the selected year and consider the entire year
                            $title = 'Annually';
                            $selectedYear = $_POST['annually_year'];
                            $startOfYear = $selectedYear . '-01-01';
                            $endOfYear = $selectedYear . '-12-31';

                            $menu_query = "SELECT summary_products, summary_qty, summary_price, inserted_at, summary_status 
                                        FROM `summary_orders` 
                                        WHERE DATE(inserted_at) BETWEEN '$startOfYear' AND '$endOfYear' AND summary_status = '1' 
                                        ORDER BY summary_products ASC";

                            $log_reports_query = "SELECT * FROM log_reports
                                        LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                        LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                        WHERE DATE(date_time) BETWEEN '$startOfYear' AND '$endOfYear'";

                            $billing_query = "SELECT total_bill, date_time, pwddisc, seniordisc, bdaydisc FROM billing_history 
                                        WHERE DATE(date_time) BETWEEN '$startOfYear' AND '$endOfYear'";

                            $appointment_query = "SELECT count, date, appointment_session FROM appointment
                                        WHERE appointment_session = '2' AND date BETWEEN '$startOfYear' AND '$endOfYear'";

                            $survey_query = "SELECT date, survey_answer FROM survey
                                        WHERE date BETWEEN '$startOfYear' AND '$endOfYear'";

                            echo "<h2 class='mt-3'>Romantic Baboy $title Report</h2>";
                            echo "Start of Year: ". date('F j, Y', strtotime($startOfYear))."<br>";
                            echo "End of Year: ". date('F j, Y', strtotime($endOfYear))."<br>";
                            
                        }

                        $menu_result = mysqli_query($connection, $menu_query);
                        $log_reports_result = mysqli_query($connection, $log_reports_query);
                        $billing_result = mysqli_query($connection, $billing_query);
                        $appointment_result = mysqli_query($connection, $appointment_query);
                        $survey_result = mysqli_query($connection, $survey_query);

                            if (!$menu_result || !$log_reports_result || !$billing_result || !$appointment_result || !$survey_result) {
                                // Display an error message if the query fails
                                echo '<p class="text-danger">Error executing the query: ' . mysqli_error($connection) . '</p>';
                            } else {
                                // Display the table
                                ?>
                                
                                <div>
                                    <div class="text-right">
                                        <a class="btn btn-danger mb-1" onclick="printPDFReport()"><i class="bi bi-file-earmark-pdf"></i></a>
                                        <a class="btn btn-success mb-1" onclick="printEXCELReport()"><i class="bi bi-file-earmark-excel"></i></a>
                                        <a class="btn btn-info mb-1" onclick="printCSVReport()"><i class="bi bi-filetype-csv"></i></a>       
                                    </div>
                                    <table class="table table-hover table-bordered table-dark  mb-5">
                                        <thead>
                                            <tr><th colspan="6"><?php echo $title?> Inventory Report</th></tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>User</th>
                                                <th>Qty</th>
                                                <th>Time</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            if (mysqli_num_rows($log_reports_result) > 0) {
                                                while ($row_logs = mysqli_fetch_array($log_reports_result)) {
                                                    echo "<tr>";
                                                        echo "<td>".$i."</td>";
                                                        echo "<td>".$row_logs['item_name']."</td>";
                                                        echo "<td>".$row_logs['name']."</td>";

                                                    // Check user action and adjust the sign accordingly
                                                    if ($row_logs['action'] == 0) {
                                                        echo "<td>- ".$row_logs['report_qty']." ".$row_logs['unit_of_measure']."</td>";
                                                    } else {
                                                        echo "<td>+ ".$row_logs['report_qty']." ".$row_logs['unit_of_measure']."</td>";
                                                    }
                                                    echo "<td>".date(' g:i A', strtotime($row_logs['date_time']))."</td>";
                                                    echo "<td>".date('F j, Y', strtotime($row_logs['date_time']))."</td>";
                                                    echo "<tr>";
                                                    $i++;
                                                }
                                            } else {
                                                echo "<td class='text-center' colspan='6'>No record available!</td>";
                                            }   
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                    
                                    <table class="table table-hover table-bordered table-dark mt-5 mb-5">
                                        <thead>
                                            <tr><th colspan="4"><?php echo $title;?> Menu Report</th></tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $productQuantity = array(); // An associative array to store product quantities
                                            $othersBill = 0;
                                            // Loop through the result set and display each row in the table
                                            if(mysqli_num_rows($menu_result) > 0) {

                                                while ($row_menu = mysqli_fetch_array($menu_result)) {
                                                    $product = $row_menu['summary_products'];
                                                    $quantity = $row_menu['summary_qty'];
                                                    $price = $row_menu['summary_price'];
                                    
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
                                                    $totalothers = $totalPrice * $quantity;

                                                    echo "<tr>";
                                                    echo "<td>" . $i . "</td>";
                                                    echo "<td>" . $product ."</td>";
                                                    echo "<td>" . $quantity . "</td>";
                                                    echo "<td>" . number_format($totalothers, 2) . "</td>";
                                                    echo "</tr>";
                                                    // Add the product's total price to the overall bill
                                                    $othersBill = $othersBill + $totalothers;
                                                    $i++;
                                                }
                                                    echo "<tr>";
                                                    echo "<td colspan='3' class='text-left'>Menu Bill:</td>";
                                                    echo "<td><strong> ₱ ".number_format($othersBill, 2)."</strong></td>";
                                                    echo "</tr>";
                                            } else {
                                                echo "<td class='text-center' colspan='4'>No record available!</td>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <table class="table table-hover table-bordered table-dark mt-5 mb-5">
                                    <?php
                                    $totalBill = 0;
                                    $seniorDisc = 0;
                                    $pwdDisc = 0;
                                    $bdayDisc = 0;
                                    $customerCount = 0;
                                    $averageSurvey = 0.00;
                                        if (mysqli_num_rows($billing_result) > 0) {
                                            while ($row_billing = mysqli_fetch_array($billing_result)) {
                                                $totalBill += $row_billing['total_bill']; // Accumulate total bill
                                                $seniorDisc += $row_billing['seniordisc'];
                                                $pwdDisc += $row_billing['pwddisc'];
                                                $bdayDisc += $row_billing['bdaydisc'];
                                            }
                                        }

                                        if (mysqli_num_rows($appointment_result) > 0) {
                                            while ($row_appointment = mysqli_fetch_array($appointment_result)) {
                                                $customerCount += $row_appointment['count']; // Accumulate total bill
                                            }
                                        }

                                        if (mysqli_num_rows($survey_result) > 0) {
                                            $totalSurveyAnswers = 0;
                                            $numRows = 0;
                                        
                                            while ($row_survey = mysqli_fetch_array($survey_result)) {
                                                $totalSurveyAnswers += $row_survey['survey_answer'];
                                                $numRows++;
                                            }
                                        
                                            if ($numRows > 0) {
                                                $averageSurvey = $totalSurveyAnswers / $numRows;
                                                $averageSurvey = round($averageSurvey, 2);
                                            }
                                          
                                        }
                                
                                        echo "<tr>";
                                        echo "<td colspan='3' class='text-left'>".$title." Customer Count:</td>";
                                        echo "<td><small><strong>".$customerCount."</small></strong></td>";
                                        echo "</tr>";

                                        echo "<tr>";
                                        echo "<td colspan='3' class='text-left'>".$title." Customer Rating:</td>";
                                        echo "<td><small><strong>".$averageSurvey." %</small></strong></td>";
                                        echo "</tr>";

                                        echo "<tr>";
                                        echo "<td colspan='3' class='text-left'>Total Senior Discount:</td>";
                                        echo "<td><small><strong>₱ ".number_format($seniorDisc, 2)."</small></strong></td>";
                                        echo "</tr>";
                                
                                        echo "<tr>";
                                        echo "<td colspan='3' class='text-left'>Total PWD Discount:</td>";
                                        echo "<td><small><strong>₱ ".number_format($pwdDisc, 2)."</small></strong></td>";
                                        echo "</tr>";
                                
                                        echo "<tr>"; 
                                        echo "<td colspan='3' class='text-left'>Total Bday Discount:</td>";
                                        echo "<td><small><strong>₱ ".number_format($bdayDisc, 2)."</small></strong></td>";
                                        echo "</tr>";
                                
                                        echo "<tr>";
                                        echo "<td colspan='3' class='text-left'><strong>".$title." Total Revenue:</strong></td>";
                                        echo "<td><strong>₱ ".number_format($totalBill, 2)."</strong></td>";
                                        echo "</tr>";       
                                    ?>
                                    </table>
                                </div>
                                <?php
                            }
                    } else {
                        // Display a message or take any other action if no duration is selected
                        echo '<p class="text-danger">Please select a duration to generate reports.</p>';
                    }
                }
            ?>
            </div>
        </div>
    </div>
</body>
<!-- Footer -->
<footer class="main-footer bg-black text-center">
    <div class="float-right d-none d-sm-block">
        <!-- Additional footer content or links can go here -->
    </div>
    Romantic Baboy – SM City Sta. Rosa Branch
 &copy; <?php echo date("Y"); ?>
</footer>
</html>

<script>

    document.getElementById('duration').addEventListener('change', function () {
    var duration = this.value;
    var dateInputs = document.getElementsByClassName('date-input');

    for (var i = 0; i < dateInputs.length; i++) {
        dateInputs[i].style.display = 'none';
    }

    if (duration !== '') {
        document.getElementById(duration).style.display = 'block';
    }
    });

    // Function to handle PRINT button click
    function printPDFReport() {
        // Read the duration value using jQuery
        var duration = $("#selected_duration").val();
        var startDate = '';

        // Check if a duration is selected
        if (duration) {
            // Set startDate based on the selected duration
            if (duration === 'annually') {
                startDate = $("#selected_annually_year").val();
            } else if (duration === 'monthly') {
                startDate = $("#selected_monthly_month").val();
            } else if (duration === 'weekly') {
                startDate = $("#selected_weekly_start_date").val();
            } else if (duration === 'daily') {
                startDate = $("#selected_daily_date").val();
            }

            // Redirect to report.php with the duration and startDate as parameters in the URL
            var url = "generate_reports/generate_pdf_reports.php?duration=" + duration + "&startDate=" + startDate;

            // Open the URL in a new tab
            var newTab = window.open(url, '_blank');
            newTab.focus(); // Focus on the new tab
        } else {
            // Display an error message or take appropriate action
            alert("Please select a duration before printing.");
        }
    }

    function printEXCELReport() {
        // Read the duration value using jQuery
        var duration = $("#selected_duration").val();
        var startDate = '';

        // Check if a duration is selected
        if (duration) {
            // Set startDate based on the selected duration
            if (duration === 'annually') {
                startDate = $("#selected_annually_year").val();
            } else if (duration === 'monthly') {
                startDate = $("#selected_monthly_month").val();
            } else if (duration === 'weekly') {
                startDate = $("#selected_weekly_start_date").val();
            } else if (duration === 'daily') {
                startDate = $("#selected_daily_date").val();
            }

            // Redirect to report.php with the duration and startDate as parameters in the URL
            var url = "generate_reports/generate_excel_reports.php?duration=" + duration + "&startDate=" + startDate;

            // Open the URL in a new tab
            var newTab = window.open(url, '_blank');
            newTab.focus(); // Focus on the new tab
        } else {
            // Display an error message or take appropriate action
            alert("Please select a duration before printing.");
        }
    }

    function printCSVReport() {
        // Read the duration value using jQuery
        var duration = $("#selected_duration").val();
        var startDate = '';

        // Check if a duration is selected
        if (duration) {
            // Set startDate based on the selected duration
            if (duration === 'annually') {
                startDate = $("#selected_annually_year").val();
            } else if (duration === 'monthly') {
                startDate = $("#selected_monthly_month").val();
            } else if (duration === 'weekly') {
                startDate = $("#selected_weekly_start_date").val();
            } else if (duration === 'daily') {
                startDate = $("#selected_daily_date").val();
            }

            // Redirect to report.php with the duration and startDate as parameters in the URL
            var url = "generate_reports/generate_csv_reports.php?duration=" + duration + "&startDate=" + startDate;

            // Open the URL in a new tab
            var newTab = window.open(url, '_blank');
            newTab.focus(); // Focus on the new tab
        } else {
            // Display an error message or take appropriate action
            alert("Please select a duration before printing.");
        }
    }

    function validateInput(input) {
    input.value = input.value.replace(/[^0-9eE]/g, ''); // Remove any non-numeric or non-'e' characters
    if (input.value.length > 4) {
        input.value = input.value.slice(0, 4); // Limit to 4 characters
    }

    // Enforce a minimum value of 2022
    var minValue = 2022;
    var inputValue = parseInt(input.value);

    if (isNaN(inputValue) || inputValue < minValue) {
        input.value = minValue.toString();
    } else {
        // Enforce a maximum value of the current year
        var currentYear = new Date().getFullYear();
        if (inputValue > currentYear) {
            input.value = currentYear.toString();
        }
    }
}

    

    // Trigger the change event to display the correct date input on page load
    document.getElementById('duration').dispatchEvent(new Event('change'));

</script>