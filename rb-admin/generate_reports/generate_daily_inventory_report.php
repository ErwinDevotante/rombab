<?php
require '../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include '../../conn.php';

date_default_timezone_set('Asia/Manila');
// Get the current date in the Philippines timezone in the format "Y-m-d"
$currentDate = date('Y-m-d');
$formattedDate = date("F d, Y", strtotime($currentDate));


    $title = 'Daily';
    $inventory_query = "SELECT * FROM inventory WHERE item_status = 0 ORDER BY item_desc ASC";
    $log_reports_query = "SELECT * FROM log_reports
                        LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                        LEFT JOIN users ON users.user_id = log_reports.report_user_id
                        WHERE DATE(date_time) = '$currentDate'";
    $menu_query = "SELECT summary_products, summary_qty, summary_price, inserted_at, summary_status FROM `summary_orders` 
                WHERE DATE(inserted_at) = '$currentDate' AND summary_status = '1' ORDER BY summary_products ASC";
    $billing_query = "SELECT total_bill, date_time, pwddisc, seniordisc, bdaydisc FROM billing_history WHERE DATE(date_time) = '$currentDate'";
    $appointment_query ="SELECT count, date, appointment_session FROM appointment
                        WHERE appointment_session = '2' AND date = '$currentDate'";
    $survey_query = "SELECT date, survey_answer FROM survey
                    WHERE date = '$currentDate'";

    $i = 1;
    $inventory_result = mysqli_query($connection, $inventory_query);
    $menu_result = mysqli_query($connection, $menu_query);
    $log_reports_result = mysqli_query($connection, $log_reports_query);
    $billing_result = mysqli_query($connection, $billing_query);
    $appointment_result = mysqli_query($connection, $appointment_query);
    $survey_result = mysqli_query($connection, $survey_query);

$html = '

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Generate Reports</title>
    <p>Generated on: ' . date('F j, Y | g:i A'). '</p>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="../../assets/rombab-logo.png">
    <style>
    body {
            font-family: Poppins, sans-serif;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        p {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #4444;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #8b0000;
            color: white;
        }
</style>
<body>
    <h2>Romantic Baboy '.$title.' Report</h2>
    
    <table>
        <thead>
            <tr><th colspan="5">'.$title.' Inventory Report</th></tr>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Description</th>
                <th>OUM</th>
                <th>Available Stocks</th>
            </tr>
        </thead>
        <tbody>';
        if(mysqli_num_rows($inventory_result) > 0) {
            while ($row_inventory = mysqli_fetch_array($inventory_result)) {
            $html .= '<tr>
                    <td>'.$i.'</td>
                    <td>'.$row_inventory['item_name'].'</td>
                    <td>'.$row_inventory['item_desc'].'</td>
                    <td>'.$row_inventory['unit_of_measure'].'</td>
                    <td>'.$row_inventory['stock'].'</td>
                </tr>';
                $i++;
            }
        }
        else {
            $html .='<tr>
                <td class="text-center" colspan="5">No record found!</td>
            </tr>';
            }
        $html .= '</tbody>
    </table>
    
    <table>
        <thead>
            <tr><th colspan="5">'.$title.' Inventory Report</th></tr>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>User</th>
                <th>Qty</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>';
        $i = 1;
        if (mysqli_num_rows($log_reports_result) > 0) {
            while ($row_logs = mysqli_fetch_array($log_reports_result)) {
                $html .= '<tr>
                    <td>'.$i.'</td>
                    <td>'.$row_logs['item_name'].'</td>
                    <td>'.$row_logs['name'].'</td>';
        
                // Check user role and adjust the sign accordingly
                if ($row_logs['user_roles'] == 3) {
                    $html .= '<td>- '.$row_logs['report_qty'].''.$row_logs['unit_of_measure'].'</td>';
                } else {
                    $html .= '<td>+ '.$row_logs['report_qty'].''.$row_logs['unit_of_measure'].'</td>';
                }
        
                $html .= '<td>'.$row_logs['date_time'].'</td>
                </tr>';
                $i++;
            }
        }        
        else {
        $html .='<tr>
            <td class="text-center" colspan="5">No record found!</td>
        </tr>';
        }
        $html .= '</tbody>
    </table>

    <table>
        <thead>
            <tr><th colspan="4">'.$title.' Menu Report</th></tr>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>';
        $i = 1;
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

                $html .= '<tr>
                    <td>'.$i.'</td>
                    <td>'.$product.'</td>
                    <td>'.$quantity.'</td>
                    <td>'.number_format($totalothers, 2).'</td>
                </tr>';
                // Add the product's total price to the overall bill
                $othersBill = $othersBill + $totalothers;
                $i++;
            }

            $html .= '<tr>
            <td colspan="3" class="text-right">Menu Bill:</td>
            <td><strong> ₱ '.number_format($othersBill, 2).'</strong></td>
            </tr>';
        }        
        else {
        $html .='<tr>
            <td class="text-center" colspan="4">No record found!</td>
        </tr>';
        }
        $html .= '</tbody>
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

        $html .= '<tr>
        <td colspan="3" class="text-right">'.$title.' Customer Count:</td>
        <td><small><strong>'.$customerCount.'</small></strong></td>
        </tr>';

        $html .= '<tr>
        <td colspan="3" class="text-right">'.$title.' Customer Rating:</td>
        <td><small><strong>'.$averageSurvey.' %</small></strong></td>
        </tr>';

        $html .= '<tr>
        <td colspan="3" class="text-right">Total Senior Discount:</td>
        <td><small><strong>₱ '.number_format($seniorDisc, 2).'</small></strong></td>
        </tr>';

        $html .= '<tr>
        <td colspan="3" class="text-right">Total PWD Discount:</td>
        <td><small><strong>₱ '.number_format($pwdDisc, 2).'</small></strong></td>
        </tr>';

        $html .= '<tr> 
        <td colspan="3" class="text-right">Total Bday Discount:</td>
        <td><small><strong>₱ '.number_format($bdayDisc, 2).'</small></strong></td>
        </tr> ';

        $html .= '<tr>
        <td colspan="3" class="text-right"><strong>'.$title.' Total Revenue:</strong></td>
        <td><strong>₱ '.number_format($totalBill, 2).'</strong></td>
        </tr>
    </table>
</body>
</html>';

$options = new Options();
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
// Set the paper size to A4 and orientation to portrait
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$uniqueId = uniqid();
$NameModified = strtolower(str_replace(' ', '', $formattedDate));
// Generate the file name with the current time, unique identifier, and equipment name
$fileName = 'daily_inventory_report_' . $NameModified . '_' . $uniqueId . '.pdf';

// Save the PDF to a directory in your file system
$directoryPath = '../daily_reports/';
$filePath = $directoryPath . $fileName;
file_put_contents($filePath, $dompdf->output());

// Output the PDF to the browser
$dompdf->stream($fileName, ["Attachment" => false]);

// Insert the file information into the daily_reports table
$insertQuery = "INSERT INTO daily_reports (report_file, report_time, as_archived) VALUES ('$fileName', NOW(), '0')";
    if (mysqli_query($connection, $insertQuery)) {
        echo "File information saved to the database.";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
?>
