<?php
include '../../conn.php';

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

        $menu_query = "SELECT summary_products, summary_qty, summary_price
                    FROM `summary_orders` 
                    WHERE DATE(inserted_at) = '$choosenDate' AND summary_status = '1' 
                    ORDER BY summary_products ASC";

        $log_reports_query = "SELECT inventory.item_name, users.name, log_reports.action, log_reports.report_qty, log_reports.date_time FROM log_reports
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

        $menu_query = "SELECT summary_products, summary_qty, summary_price
                    FROM `summary_orders` 
                    WHERE DATE(inserted_at) BETWEEN '$startOfWeek' AND '$endOfWeek' AND summary_status = '1' 
                    ORDER BY summary_products ASC";
        
        
        $log_reports_query = "SELECT inventory.item_name, users.name, log_reports.action, log_reports.report_qty, log_reports.date_time FROM log_reports
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

        $menu_query = "SELECT summary_products, summary_qty, summary_price
                FROM `summary_orders` 
                WHERE DATE(inserted_at) BETWEEN '$selectedMonthYear-01' AND '$endOfMonth' AND summary_status = '1' 
                ORDER BY summary_products ASC";
                            
        $log_reports_query = "SELECT inventory.item_name, users.name, log_reports.action, log_reports.report_qty, log_reports.date_time FROM log_reports
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

        $menu_query = "SELECT summary_products, summary_qty, summary_price
                    FROM `summary_orders` 
                    WHERE DATE(inserted_at) BETWEEN '$startOfYear' AND '$endOfYear' AND summary_status = '1' 
                    ORDER BY summary_products ASC";

        $log_reports_query = "SELECT inventory.item_name, users.name, log_reports.action, log_reports.report_qty, log_reports.date_time FROM log_reports
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
            $fileName = $title .'_report_' . $NameModified . '_' . $uniqueId . '.csv';
            $filePath = '../daily_reports/' . $fileName; // Replace with the actual path to your folder
            file_put_contents($filePath, '');

            // Insert file information into the database
            $insertQuery = "INSERT INTO daily_reports (report_file, report_time, as_archived) VALUES ('$fileName', NOW(), '0')";
            mysqli_query($connection, $insertQuery);
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $fileName);
            $output = fopen("php://output", "w");

            fputcsv($output, array('Generated on: ' . date('F j, Y | g:i A')));
            fputcsv($output, array('Romantic Baboy '.$title.' Report'));
            if ($duration == 'annually') { 
                fputcsv($output, array(date('F j, Y', strtotime($startOfYear)).' - '. date('F j, Y', strtotime($endOfYear))));
            } else if ($duration == 'monthly') { 
                fputcsv($output, array(date('F j, Y', strtotime($selectedMonthYear)).' - '. date('F j, Y', strtotime($endOfMonth))));
            } else if ($duration == 'weekly') { 
                fputcsv($output, array(date('F j, Y', strtotime($startOfWeek)).' - '. date('F j, Y', strtotime($endOfWeek))));
            } else if ($duration == 'daily') { 
                fputcsv($output, array('Date: '.date('F j, Y', strtotime($choosenDate))));
            }
            fputcsv($output, array());

            fputcsv($output, array( $title.' Inventory Report'));
            fputcsv($output, array('Item', 'Kitchen User', 'Type', 'Quantity', 'Date and Time'));

            // Check if there are reports
            if (mysqli_num_rows($log_reports_result) > 0) {
                // Write rows for reports
                while ($row_reports = mysqli_fetch_assoc($log_reports_result)) {
                    // Check action and set the character accordingly
                    $userRolesCharacter = ($row_reports['action'] == 0) ? '-' : '+';

                    // Modify the row before writing to CSV
                    $row_reports['action'] = $userRolesCharacter;

                    fputcsv($output, $row_reports);
                }
            } else {
                // Write message if there are no reports
                fputcsv($output, array('No reports found for the specified date'));
            }

            fputcsv($output, array());

            // Output CSV for Daily Menu Report
            fputcsv($output, array($title.' Menu Reports'));
            fputcsv($output, array('Item', 'Qty', 'Price'));

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

                // Output CSV headers
                fputcsv($output, array('No', 'Item', 'Qty', 'Total Price'));

                $i = 1;

                // Loop through the array and output CSV rows
                foreach ($productQuantity as $product => $data) {
                    $quantity = $data['quantity'];
                    $totalPrice = $data['totalPrice'];
                    $totalothers = $totalPrice * $quantity;

                    // Output CSV row
                    fputcsv($output, array($i, $product, $quantity, number_format($totalothers, 2)));

                    // Add the product's total price to the overall bill
                    $othersBill = $othersBill + $totalothers;

                    $i++;
                }

                // Output CSV row for Menu Bill
                fputcsv($output, array('Menu Bill', '', '', ''. number_format($othersBill, 2)));
            } else {
                // Output CSV row if no records found
                fputcsv($output, array('No record found!'));
            }

            fputcsv($output, array());

            $totalBill = 0;
            $seniorDisc = 0;
            $pwdDisc = 0;
            $bdayDisc = 0;
            $customerCount = 0;
            $averageSurvey = 0.00;

            if (mysqli_num_rows($appointment_result) > 0) {
                while ($row_appointment = mysqli_fetch_array($appointment_result)) {
                    $customerCount += $row_appointment['count']; // Accumulate total bill
                }
                fputcsv($output, array($title. ' Customer Count', '', '', '' . $customerCount));
            } else {
                // Output CSV row if no records found
                fputcsv($output, array('No record found!'));
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
                fputcsv($output, array($title. ' Customer Rating:', '', '', '' . $averageSurvey.'%'));
            } else {
                // Output CSV row if no records found
                fputcsv($output, array('No record found!'));
            }


            if (mysqli_num_rows($billing_result) > 0) {
                while ($row_billing = mysqli_fetch_array($billing_result)) {
                    $totalBill += $row_billing['total_bill']; // Accumulate total bill
                    $seniorDisc += $row_billing['seniordisc'];
                    $pwdDisc += $row_billing['pwddisc'];
                    $bdayDisc += $row_billing['bdaydisc'];
                }

                // Output CSV rows for discount information
                fputcsv($output, array('Total Senior Discount', '', '', '' . number_format($seniorDisc, 2)));
                fputcsv($output, array('Total PWD Discount', '', '', '' . number_format($pwdDisc, 2)));
                fputcsv($output, array('Total Bday Discount', '', '', '' . number_format($bdayDisc, 2)));
                fputcsv($output, array($title. ' Total Revenue', '', '', '' . number_format($totalBill, 2)));
            } else {
                // Output CSV row if no records found
                fputcsv($output, array('No record found!'));
            }

            fclose($output);

        } 
}
    
?>
