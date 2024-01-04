<?php
include '../../conn.php';
if($_SESSION['user_id']==''){
    header('location:../../index.php');
    exit();
} 
$outputInventory = '';
$i = 1;

date_default_timezone_set('Asia/Manila');
// Get the current date in the Philippines timezone in the format "Y-m-d"
$currentDate = date('Y-m-d');
$formattedDate = date("F d, Y", strtotime($currentDate));
if(isset($_POST["export_excel"])) {
    $inventory_query = mysqli_query($connection, "SELECT * FROM inventory WHERE item_status = 0 ORDER BY item_desc ASC");
    $log_reports_query = mysqli_query($connection, "SELECT * FROM log_reports
                                                    LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                                    LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                                    WHERE DATE(date_time) = '$currentDate'");


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
        <tr><th colspan="5">Daily Inventory Report</th></tr>
        <tr>
            <th>No</th>
            <th>Item</th>
            <th>Description</th>
            <th>OUM</th>
            <th>Available Stocks</th>
        </tr>
    </thead>
    <tbody>
    ';
    if (mysqli_num_rows($inventory_query) > 0) {
        while ($rowInventory = mysqli_fetch_array($inventory_query)){
            $outputInventory .= '
                <tr>
                    <td>'.$i.'</td>
                    <td>'.$rowInventory['item_name'].'</td>
                    <td>'.$rowInventory['item_desc'].'</td>
                    <td>'.$rowInventory['unit_of_measure'].'</td>
                    <td>'.$rowInventory['stock'].'</td>
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
        <tr><th colspan="6">Daily Inventory Report</th></tr>
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
    if (mysqli_num_rows($log_reports_query) > 0) {
        while ($rowInventoryRep = mysqli_fetch_array($log_reports_query)) {
            $outputInventory .= '
                <tr>
                    <td>'.$i.'</td>
                    <td>'.$rowInventoryRep['item_name'].'</td>
                    <td>'.$rowInventoryRep['name'].'</td>';
    
            // Check user role and adjust the sign accordingly
            if ($rowInventoryRep['user_roles'] == 3) {
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
    </table>';

    $uniqueId = uniqid();
    $fileName = 'daily_inventory_report_' . $currentDate . '_' . $uniqueId . '.xls';
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