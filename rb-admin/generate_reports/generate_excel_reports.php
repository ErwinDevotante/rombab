<?php
include '../../conn.php';

$outputInventory = '';
$i = 1;

date_default_timezone_set('Asia/Manila');
// Get the current date in the Philippines timezone in the format "Y-m-d"
$currentDate = date('Y-m-d');
$formattedDate = date("F d, Y", strtotime($currentDate));

$duration = isset($_GET["duration"]) ? $_GET["duration"] : '';
$startDate = isset($_GET["startDate"]) ? $_GET["startDate"] : '';

// Process the form submission
if (!empty($duration)) {
    $selectedOption = $duration;

    // Get the selected date based on the chosen duration
    if ($selectedOption == 'daily') {
        $title = 'Daily';
        $choosenDate = $startDate;

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

    
    } elseif ($selectedOption == 'weekly') {
        // For weekly, use the start date and consider the entire week
        $title = 'Weekly';
        $startOfWeek = $startDate;
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

    
    } elseif ($selectedOption == 'monthly') {
        // For monthly, use the selected month and consider the entire month
        $title = 'Monthly';
        $selectedMonthYear = $startDate;
        
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

    
    } elseif ($selectedOption == 'annually') {
        // For annually, use the selected year and consider the entire year
        $title = 'Annually';
        $selectedYear = $startDate;
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
                    WHERE date '$startOfYear' AND '$endOfYear'";
        
    }

    $menu_result = mysqli_query($connection, $menu_query);
    $log_reports_result = mysqli_query($connection, $log_reports_query);
    $billing_result = mysqli_query($connection, $billing_query);
    $appointment_result = mysqli_query($connection, $appointment_query);
    $survey_result = mysqli_query($connection, $survey_query);


        if (!$menu_result || !$log_reports_result || !$billing_result || !$appointment_result || !$survey_result) {
            // Display an error message if the query fails
            $outputInventory .= '<tr>
                <td colspan="3" class="text-center">Error executing the query: ' . mysqli_error($connection) . '</td>
            </tr>';
        } else {

            $outputInventory .= '
            <style>
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                border: 1px solid #4444;
                padding: 8px;
                text-align: left;
                width: 100px; /* Set your desired width here */
            }
            th {
                background-color: #8b0000;
                color: white;
            }
            </style>
            <table>
            <thead>
                <tr><th colspan="5">Generated on: ' . date('F j, Y | g:i A') . '</tr>
                <tr><th colspan="5"></tr>
                <tr><th colspan="5">Romantic Baboy '.$title.' Report</th></tr>';

                if ($duration == 'annually') { 
                    $outputInventory .='<tr><th colspan="5">'. date('F j, Y', strtotime($startOfYear)).' -
                    '. date('F j, Y', strtotime($endOfYear)).'</th></tr>';
                } else if ($duration == 'monthly') { 
                    $outputInventory .='<tr><th colspan="5">'. date('F j, Y', strtotime($selectedMonthYear)).' - 
                    '. date('F j, Y', strtotime($endOfMonth)).'</th></tr>';
                } else if ($duration == 'weekly') { 
                    $outputInventory .='<tr><th colspan="5"> Start of Week: '. date('F j, Y', strtotime($startOfWeek)).' - 
                    End of Week: '. date('F j, Y', strtotime($endOfWeek)).'</th></tr>';
                } else if ($duration == 'daily') { 
                    $outputInventory .='<tr><th colspan="5"> Date: '. date('F j, Y', strtotime($choosenDate)).'</th></tr>';
                } 
                
            $outputInventory .= '</thead>
            </table>

            <table>
            <thead>
                <tr><th colspan="6"></tr>
                <tr><th colspan="6">'.$title.' Inventory Report</th></tr>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            ';
            $i=1;
            if (mysqli_num_rows($log_reports_result) > 0) {
                while ($rowInventoryRep = mysqli_fetch_array($log_reports_result)) {
                    $outputInventory .= '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$rowInventoryRep['item_name'].'</td>
                            <td>'.$rowInventoryRep['name'].'</td>';
            
                    // Check user role and adjust the sign accordingly
                    if ($rowInventoryRep['action'] == 0) {
                        $outputInventory .= '<td>-</td>';
                    } else {
                        $outputInventory .= '<td>+</td>';
                    }
                    $outputInventory .= '<td>'.$rowInventoryRep['report_qty'].'</td>';
                    $outputInventory .= '<td>'.$rowInventoryRep['date_time'].'</td>
                        </tr>
                    ';
                    $i++;
                }
            } else {
                $outputInventory .= '
                    <tr>
                        <td colspan="5">No record found!</td>
                    </tr>
                ';
            }
            $outputInventory .= '
            </tbody>
            </table>

            <table>
            <thead>
                <tr><th colspan="6"></tr>
                <tr><th colspan="4">'.$title.' Menu Report</th></tr>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
            ';
            $i=1;
            $productQuantity = array(); // An associative array to store product quantities
            $othersBill = 0;
            if (mysqli_num_rows($menu_result) > 0) {
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

                    $outputInventory .= '<tr>
                        <td>'.$i.'</td>
                        <td>'.$product.'</td>
                        <td>'.$quantity.'</td>
                        <td>'.number_format($totalothers, 2).'</td>
                    </tr>';
                    // Add the product's total price to the overall bill
                    $othersBill = $othersBill + $totalothers;
                    $i++;
                }

                $outputInventory .= '<tr>
                    <td colspan="3" class="text-right">Menu Bill:</td>
                    <td><strong>'.number_format($othersBill, 2).'</strong></td>
                    </tr>';
            } else {
                $outputInventory .= '
                    <tr>
                        <td colspan="4">No record found!</td>
                    </tr>
                ';
            }
            $outputInventory .= '
            </tbody>
            </table>

            <table>';
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

                    $outputInventory .= '<tr>
                    <td colspan="3" class="text-right">'.$title.' Customer Count:</td>
                    <td><small><strong>'.$customerCount.'</small></strong></td>
                    </tr>';

                    $outputInventory .= '<tr>
                    <td colspan="3" class="text-right">'.$title.' Customer Rating:</td>
                    <td><small><strong>'.$averageSurvey.' %</small></strong></td>
                    </tr>';

                    $outputInventory .= '<tr>
                    <td colspan="3" class="text-right">Total Senior Discount:</td>
                    <td><small><strong>'.number_format($seniorDisc, 2).'</small></strong></td>
                    </tr>';

                    $outputInventory .= '<tr>
                    <td colspan="3" class="text-right">Total PWD Discount:</td>
                    <td><small><strong>'.number_format($pwdDisc, 2).'</small></strong></td>
                    </tr>';

                    $outputInventory .= '<tr> 
                    <td colspan="3" class="text-right">Total Bday Discount:</td>
                    <td><small><strong>'.number_format($bdayDisc, 2).'</small></strong></td>
                    </tr> ';

                    $outputInventory .= '<tr>
                    <td colspan="3" class="text-right"><strong>Daily Total Revenue:</strong></td>
                    <td><strong>'.number_format($totalBill, 2).'</strong></td>
                    </tr>
            </table>';
        } 


    $uniqueId = uniqid();
    if ($duration == 'annually') {
        $NameModified = strtolower(str_replace(' ', '', $startOfYear .'-to-'. $endOfYear));
    } elseif ($duration == 'monthly') {
        $NameModified = strtolower(str_replace(' ', '', $selectedMonth.'-to-'. $endOfMonth));
    } elseif ($duration == 'weekly') {
        $NameModified = strtolower(str_replace(' ', '', $startOfWeek.'-to-'. $endOfWeek));
    } elseif ($duration == 'daily') {
        $NameModified = strtolower(str_replace(' ', '', $choosenDate));
    }
// Generate the file name with the current time, unique identifier, and equipment name
    $fileName = $title .'_report_' . $NameModified . '_' . $uniqueId . '.xls';
    $filePath = '../daily_reports/' . $fileName; // Replace with the actual path to your folder
    file_put_contents($filePath, $outputInventory);

    // Insert file information into the database
    $insertQuery = "INSERT INTO daily_reports (report_file, report_time, as_archived) VALUES ('$fileName', NOW(), '0')";
    mysqli_query($connection, $insertQuery);

    // Provide the file as a download
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename=' . $fileName);
    echo $outputInventory;

}
    
?>
