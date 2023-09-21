<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include '../conn.php';

date_default_timezone_set('Asia/Manila');
// Get the current date in the Philippines timezone in the format "Y-m-d"
$currentDate = date('Y-m-d');
$formattedDate = date("F d, Y", strtotime($currentDate));

$inventory_query = mysqli_query($connection, "SELECT * FROM inventory WHERE item_status = 0 ORDER BY item_desc ASC");
$log_reports_query = mysqli_query($connection, "SELECT * FROM log_reports
                                                LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                                LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                                WHERE DATE(date_time) = '$currentDate'");

$i = 1;

$html = '

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Generate Reports</title>
    <p>Generated on: ' . date('F j, Y | g:i A') . '</p>
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
    <h2>Romantic Baboy Daily Report</h2>
    <table>
        <thead>
            <tr><th colspan="5">Daily Inventory Report</th></tr>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Description</th>
                <th>OUM</th>
                <th>Available Stocks</th>
            </tr>
        </thead>
        <tbody>';
        if(mysqli_num_rows($inventory_query) > 0) {
            while ($row_inventory = mysqli_fetch_array($inventory_query)) {
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
            <tr><th colspan="5">Daily Log Report</th></tr>
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
        if(mysqli_num_rows($log_reports_query) > 0) {
            while ($row_logs = mysqli_fetch_array($log_reports_query)) {
                $html .= '<tr>
                    <td>'.$i.'</td>
                    <td>'.$row_logs['item_name'].'</td>
                    <td>'.$row_logs['name'].'</td>
                    <td>'.$row_logs['report_qty'].''.$row_logs['unit_of_measure'].'</td>
                    <td>'.$row_logs['date_time'].'</td>
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
$fileName = 'daily_report_' . $NameModified . '_' . $uniqueId . '.pdf';

// Save the PDF to a directory in your file system
$directoryPath = 'daily_reports/';
$filePath = $directoryPath . $fileName;
file_put_contents($filePath, $dompdf->output());

// Output the PDF to the browser
$dompdf->stream($fileName, ["Attachment" => false]);

// Insert the file information into the daily_reports table
$insertQuery = "INSERT INTO daily_reports (report_file, report_time) VALUES ('$fileName', NOW())";
    if (mysqli_query($connection, $insertQuery)) {
        echo "File information saved to the database.";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
?>
