<?php 
$a = 6;
session_start();
date_default_timezone_set('Asia/Manila');
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

    function updateSessionTb($connection) {
        $query_check_available_tables = "SELECT * FROM appointment WHERE table_id IS NULL LIMIT 1";
        $result_check_available_tables = mysqli_query($connection, $query_check_available_tables);
    
        if (mysqli_num_rows($result_check_available_tables) > 0) {
            // There is an available table, update session_tb to 3
            $update_session_tb_query = "UPDATE users SET session_tb = '3' WHERE user_id = '{$_SESSION['user_id']}'";
            mysqli_query($connection, $update_session_tb_query);
        } else {
            // No available table, keep session_tb as it is (no changes needed)
        }
    }

    if (isset($_POST["submit"])) {
        $name = $_POST["customer"];
        $pax = $_POST["pax"];
        $note = $_POST["note"];
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $description = "Walk-In";

        if (empty($note)) {
            $note = "No note";
        }
    
        // Check if there is an activated table in the users table
        $activated_table_query = "SELECT * FROM users WHERE session_tb = '1'";
        $activated_table_result = mysqli_query($connection, $activated_table_query);
        $activated_table_row = mysqli_fetch_array($activated_table_result);
    
        if (mysqli_num_rows($activated_table_result) > 0) {
            // An activated table is available, assign the table to the appointment
            $table_id = $activated_table_row['user_id'];
    
            // Update the appointment table with the assigned table_id
            $query = "INSERT INTO appointment VALUES('', '$name', '$description', '$table_id', '$pax', '$date', '$time', '$note', '1')";
            $result_add = mysqli_query($connection, $query);
    
            // Deactivate the assigned table in the users table
            $update_table_query = "UPDATE users SET session_tb = '3' WHERE user_id = '$table_id'";
            $result_update_table = mysqli_query($connection, $update_table_query);
            
            if ($result_add && $result_update_table) {
                echo "<script> alert('Registration Successful'); </script>";
            } else {
                echo "<script> alert('Failed to assign table.'); </script>";
            }
        } else {
            $query_add = "INSERT INTO appointment VALUES('', '$name', '$description', NULL , '$pax', '$date', '$time', '$note', '1')";
            $result_query_add = mysqli_query($connection, $query_add);
            // No activated table is available, you can add further logic to handle this case, e.g., wait and display a message
            echo "<script> alert('No available activated table. Please wait for a table to become available.'); </script>";
        }
        
        unset($_POST);
        header('Location: create-walkin-appointment.php');
    }
    updateSessionTb($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Appointment</title>
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
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" >

    <?php 
    include "top-bar.php";
    include "side-bar.php";
    ?>

    <div class="content-wrapper bg-black">
    <?php 
    $query_search = "SELECT user_id FROM users WHERE session_tb = '1'";
    $result_search = mysqli_query($connection, $query_search);

    $query_search_tblNULL = mysqli_query($connection, "SELECT * FROM appointment WHERE table_id is NULL");

    if (mysqli_num_rows($result_search) > 0 && mysqli_num_rows($query_search_tblNULL) > 0) {
        // An available table is found, fetch the first row and return its user_id
        $row = mysqli_fetch_array($result_search);
        $available_table_id = $row['user_id'];

        // Update the appointment table with the assigned table_id
        $new_appointment_query = "UPDATE appointment SET table_id = '$available_table_id', appointment_session = '1' WHERE table_id IS NULL LIMIT 1";
        $result_new_appointment = mysqli_query($connection, $new_appointment_query);

        // Deactivate the assigned table in the users table
        $deactivate_table_query = "UPDATE users SET session_tb = '3' WHERE user_id = '$available_table_id'";
        $result_deactivate_table = mysqli_query($connection, $deactivate_table_query);

        if ($result_new_appointment && $result_deactivate_table) {
            // Return the assigned table_id to update the appointment table
            echo $available_table_id;
        } else { ?>
            <span class="null-text text-black">NULL</span>
    <?php }
    } else { ?>
        <span class="null-text text-black">NULL</span>
    <?php }

    ?>
    <div class="content p-4">

    <div class="container-fluid text-center p-4">
        <h1>Walk-in Appointment</h1>
    </div>

    <section class="home-section">

    <form action="" method="post">
    <div class="form-group">
        <label>Customer's Name</label>
        <input type="text" class="form-control" id="customer" name="customer" placeholder="Enter name" required>
    </div>
    <div class="form-group">
        <label>No of people on the table</label>
        <input type="number" class="form-control" id="pax" name="pax" min="1" placeholder="Enter no of people" required>
    </div>
    <div class="form-group">
        <label>Note</label>
        <textarea type="text" class="form-control" id="note" name="note" placeholder="Enter note" rows="2"></textarea>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Submit</button>

    <div style="overflow-x:auto;">
        <table class="table table-hover table-bordered table-dark mt-5">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Name</th>
                    <th class="text-center" scope="col">Table No</th>
                    <th class="text-center" scope="col"># of People</th>
                    <th class="text-center" scope="col">Date</th>
                    <th class="text-center" scope="col">Time</th>
                    <th class="text-center" scope="col">Note</th>
                </tr>
            </thead>
                <tbody>
                <?php 
                    $result_tb = mysqli_query($connection, "SELECT * FROM appointment
                    LEFT JOIN users ON users.user_id=appointment.table_id
                    WHERE table_id is NULL 
                    AND appointment_session = '1' AND appointment_desc = 'Walk-In'");
                    if(mysqli_num_rows($result_tb) > 0) {
                    while ($row = mysqli_fetch_array($result_tb)) { ?> 
                        <tr>
                            <td class="text-center"><?php echo $row["appointment_name"]; ?></td>
                            <td class="text-center" style="display: none;" id="table_id"><?php echo $row["table_id"]; ?></td>
                            <td class="text-center">Waiting for available table...</td>
                            <td class="text-center"><?php echo $row["count"]; ?></td>
                            <td class="text-center"><?php echo $row["date"]; ?></td>
                            <td class="text-center"><?php echo $row["time"]; ?></td>
                            <td><?php echo $row["note"]; ?></td>
                        </tr>
                        <?php 
                    } 
                    } else { ?>
                        <tr>
                            <td class="text-center" colspan="7">No record found!</td>
                        </tr>
                    <?php }
                    ?>
                </tbody>  
        </table>
    </div>
    
    </form>
    </section>
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
function checkForAvailableTable() {
    $.ajax({
        url: 'create-walkin-appointment.php',
        method: 'GET',
        success: function(data) {
            // Update the appointment table with the result from the AJAX request
            if (data === 'NULL') {
                // No available table
                // You can add further logic here, such as displaying a message
            } else {
                // An available table is found, update the appointment table
                var availableTableId = data;
                $("#table_id").text(availableTableId); // Assuming the table_id cell has the ID "table_id"
            }
        }
    });
}

    // Call the checkForAvailableTable function and updateSessionTb function every 5 seconds (adjust the interval as needed)
    setInterval(function() {
        checkForAvailableTable();
        updateSessionTb();
    }, 5000); // 5000 milliseconds = 5 seconds

    // Add event listener for Customer's Name input
    const customerInput = document.getElementById('customer');
    customerInput.addEventListener('input', function() {
        const inputValue = customerInput.value;
        // Keep only letters, spaces, and "-"
        const sanitizedValue = inputValue.replace(/[^a-zA-Z\s-]/g, '');
        customerInput.value = sanitizedValue;
    });

    // Add event listener for No of people input
    const paxInput = document.getElementById('pax');
    paxInput.addEventListener('input', function() {
        const inputValue = paxInput.value;
        
        // Remove any non-digit characters (including decimal points)
        const sanitizedValue = inputValue.replace(/[^0-9]/g, '');
        
        // Ensure the value is not empty
        if (sanitizedValue === '') {
            paxInput.value = '1'; // Set a default value if the input is empty
        } else {
            const pax = parseInt(sanitizedValue, 10);
            
            // Ensure the value is within the range of 1 to 10
            if (pax < 1) {
                paxInput.value = '1'; // Set the minimum value to 1
            } else if (pax > 10) {
                paxInput.value = '10'; // Set the maximum value to 10
            } else {
                paxInput.value = pax; // Update the input value with the sanitized integer value
            }
        }
    });

    // Add event listener for Note textarea
    const noteTextarea = document.getElementById('note');
    noteTextarea.addEventListener('input', function() {
        const inputValue = noteTextarea.value;

        // Truncate the input value to 100 characters
        if (inputValue.length > 100) {
            noteTextarea.value = inputValue.slice(0, 100);
        }
    });
</script>