<?php
include '../../conn.php';
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
    $menu_query = mysqli_query($connection, "SELECT summary_products, summary_qty, summary_price, inserted_at, summary_status FROM `summary_orders` 
    WHERE DATE(inserted_at) = '$currentDate' AND summary_status = '1' ORDER BY summary_products ASC");
    $billing_query = mysqli_query($connection, "SELECT total_bill, date_time, pwddisc, seniordisc, bdaydisc FROM billing_history WHERE DATE(date_time) = '$currentDate'");

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
        <tr><th colspan="6">Daily Inventory Log Report</th></tr>
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
    </table>

    <table>
    <thead>
        <tr><th colspan="6"></tr>
        <tr><th colspan="4">Daily Menu Reports</th></tr>
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
            if (mysqli_num_rows($billing_query) > 0) {
                while ($row_billing = mysqli_fetch_array($billing_query)) {
                    $totalBill += $row_billing['total_bill']; // Accumulate total bill
                    $seniorDisc += $row_billing['seniordisc'];
                    $pwdDisc += $row_billing['pwddisc'];
                    $bdayDisc += $row_billing['bdaydisc'];
                }
            }

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

    $uniqueId = uniqid();
    $fileName = 'daily_report_' . $currentDate . '_' . $uniqueId . '.xls';
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