<?php 
include '../conn.php';
date_default_timezone_set('Asia/Manila');
// Get the current date in the Philippines timezone in the format "Y-m-d"
$currentDate = date('Y-m-d');
$formattedDate = date("F d, Y", strtotime($currentDate));

if (isset($_POST["export_csv"])) {
    $uniqueId = uniqid();
    $fileName = 'daily_report_' . $currentDate . '_' . $uniqueId . '.csv';
    $filePath = 'daily_reports/' . $fileName; // Replace with the actual path to your folder
    file_put_contents($filePath, '');

    // Insert file information into the database
    $insertQuery = "INSERT INTO daily_reports (report_file, report_time) VALUES ('$fileName', NOW())";
    mysqli_query($connection, $insertQuery);
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $fileName);
    $output = fopen("php://output", "w");

    fputcsv($output, array('Generated on: ' . date('F j, Y | g:i A')));
    fputcsv($output, array('Daily Inventory Report'));
    fputcsv($output, array());

    fputcsv($output, array('ID', 'Item', 'Description', 'OUM', 'Stocks'));
    $query_stocks = "SELECT item_id, item_name, item_desc, unit_of_measure, stock FROM inventory WHERE item_status = 0 ORDER BY item_id ASC";
    $result_stocks = mysqli_query($connection, $query_stocks);

    // Check if there are stocks
    if (mysqli_num_rows($result_stocks) > 0) {
        // Write rows for stocks
        while ($row_stocks = mysqli_fetch_assoc($result_stocks)) {
            fputcsv($output, $row_stocks);
        }
    } else {
        // Write message if there are no stocks
        fputcsv($output, array('No stocks found'));
    }

    // Add a separator between stocks and reports
    fputcsv($output, array());

    fputcsv($output, array('Item', 'Kitchen User', 'Quantity', 'Date and Time'));
    $query_reports = "SELECT inventory.item_name, users.name, log_reports.report_qty, log_reports.date_time FROM log_reports
    LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
    LEFT JOIN users ON users.user_id = log_reports.report_user_id
    WHERE DATE(date_time) = '$currentDate'";
    $result_reports = mysqli_query($connection, $query_reports);

    // Check if there are reports
    if (mysqli_num_rows($result_reports) > 0) {
        // Write rows for reports
        while ($row_reports = mysqli_fetch_assoc($result_reports)) {
            fputcsv($output, $row_reports);
        }
    } else {
        // Write message if there are no reports
        fputcsv($output, array('No reports found for the specified date'));
    }

    fclose($output);
}

?>