<?php 
include '../../conn.php';
date_default_timezone_set('Asia/Manila');
// Get the current date in the Philippines timezone in the format "Y-m-d"
$currentDate = date('Y-m-d');
$formattedDate = date("F d, Y", strtotime($currentDate));

if (isset($_POST["export_csv"])) {
    $uniqueId = uniqid();
    $fileName = 'daily_report_' . $currentDate . '_' . $uniqueId . '.csv';
    $filePath = '../daily_reports/' . $fileName; // Replace with the actual path to your folder
    file_put_contents($filePath, '');

    // Insert file information into the database
    $insertQuery = "INSERT INTO daily_reports (report_file, report_time, as_archived) VALUES ('$fileName', NOW(), '0')";
    mysqli_query($connection, $insertQuery);
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $fileName);
    $output = fopen("php://output", "w");

    fputcsv($output, array('Generated on: ' . date('F j, Y | g:i A')));
    fputcsv($output, array('Romantic Baboy Daily Report'));
    fputcsv($output, array());

    fputcsv($output, array('Daily Inventory Report'));
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
fputcsv($output, array('Daily Log Report'));
fputcsv($output, array('Item', 'Kitchen User', 'Type', 'Quantity', 'Date and Time'));
$query_reports = "SELECT inventory.item_name, users.name, log_reports.user_roles, log_reports.report_qty, log_reports.date_time FROM log_reports
LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
LEFT JOIN users ON users.user_id = log_reports.report_user_id
WHERE DATE(date_time) = '$currentDate'";
$result_reports = mysqli_query($connection, $query_reports);

// Check if there are reports
if (mysqli_num_rows($result_reports) > 0) {
    // Write rows for reports
    while ($row_reports = mysqli_fetch_assoc($result_reports)) {
        // Check user_roles and set the character accordingly
        $userRolesCharacter = ($row_reports['user_roles'] == 3) ? '-' : '+';

        // Modify the row before writing to CSV
        $row_reports['user_roles'] = $userRolesCharacter;

        fputcsv($output, $row_reports);
    }
} else {
    // Write message if there are no reports
    fputcsv($output, array('No reports found for the specified date'));
}

fputcsv($output, array());

// Output CSV for Daily Menu Report
fputcsv($output, array('Daily Menu Report'));
fputcsv($output, array('Item', 'Qty', 'Price'));

$productQuantity = array(); // An associative array to store product quantities
$othersBill = 0;

$menu_query = mysqli_query($connection, "SELECT summary_products, summary_qty, summary_price FROM `summary_orders` 
                                        WHERE DATE(inserted_at) = '$currentDate' AND summary_status = '1' ORDER BY summary_products ASC");

if (mysqli_num_rows($menu_query) > 0) {
    while ($row_menu = mysqli_fetch_array($menu_query)) {
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

$billing_query = mysqli_query($connection, "SELECT total_bill, date_time, pwddisc, seniordisc, bdaydisc FROM billing_history 
                                            WHERE DATE(date_time) = '$currentDate'");

if (mysqli_num_rows($billing_query) > 0) {
    while ($row_billing = mysqli_fetch_array($billing_query)) {
        $totalBill += $row_billing['total_bill']; // Accumulate total bill
        $seniorDisc += $row_billing['seniordisc'];
        $pwdDisc += $row_billing['pwddisc'];
        $bdayDisc += $row_billing['bdaydisc'];
    }

    // Output CSV rows for discount information
    fputcsv($output, array('Total Senior Discount', '', '', '' . number_format($seniorDisc, 2)));
    fputcsv($output, array('Total PWD Discount', '', '', '' . number_format($pwdDisc, 2)));
    fputcsv($output, array('Total Bday Discount', '', '', '' . number_format($bdayDisc, 2)));
    fputcsv($output, array('Daily Total Revenue', '', '', '' . number_format($totalBill, 2)));
} else {
    // Output CSV row if no records found
    fputcsv($output, array('No record found!'));
}

fclose($output);
}

?>