<?php 
$a = 6;
session_start();
date_default_timezone_set('Asia/Manila');
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

    function updateSessionTb($connection) {
        $query_check_available_tables = "SELECT * FROM appointment WHERE appointment_desc = 'Walk-In' AND table_id IS NULL LIMIT 1";
        $result_check_available_tables = mysqli_query($connection, $query_check_available_tables);
    
        if (mysqli_num_rows($result_check_available_tables) > 0) {
            // There is an available table, update session_tb to 3
            $update_session_tb_query = "UPDATE users SET session_tb = '3' WHERE user_id = '{$_SESSION['user_id']}'";
            mysqli_query($connection, $update_session_tb_query);
        } else {
            // No available table, keep session_tb as it is (no changes needed)
        }
    }

    if (isset($_POST["delete_appointment"])) {
        $appointment_id = $_POST["appointment_id"];

        $delete_appointment = "DELETE FROM appointment WHERE appointment_id = $appointment_id";
        $delete_result = mysqli_query($connection, $delete_appointment);
    }

    if (isset($_POST["submit"])) {
        $name = $_POST["customer"];
        $pax = $_POST["pax"];
        $note = $_POST["note"];
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $description = "Walk-In";

        $senior = isset($_POST["senior"]) ? $_POST["senior"] : 0;
        $pwd = isset($_POST["pwd"]) ? $_POST["pwd"] : 0;
        $bday_no = isset($_POST["bday"]) ? $_POST["bday"] : 0;

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
            $query = "INSERT INTO appointment (appointment_id, appointment_name, appointment_desc, table_id, count, date, time, senior_no, pwd_no, bday_no, note, appointment_session) VALUES('', '$name', '$description', '$table_id', '$pax', '$date', '$time', '$senior', '$pwd', '$bday_no', '$note', '1')";
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
            $query_add = "INSERT INTO appointment(appointment_id, appointment_name, appointment_desc, table_id, count, date, time, senior_no, pwd_no, bday_no, note, appointment_session) VALUES('', '$name', '$description', NULL , '$pax', '$date', '$time', '$senior', '$pwd', '$bday_no', '$note', '1')";
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
    <!-- Bootstrap Icons CSS -->
    <link href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed text-white" style="background: #191919;">
<div class="wrapper" >

    <?php 
    include "top-bar.php";
    include "side-bar.php";
    ?>

    <div class="content-wrapper mt-5" style="background: #191919;">
    <?php 
    $query_search = "SELECT user_id FROM users WHERE session_tb = '1'";
    $result_search = mysqli_query($connection, $query_search);

    $query_search_tblNULL = mysqli_query($connection, "SELECT * FROM appointment WHERE appointment_desc = 'Walk-In' AND table_id is NULL");

    if (mysqli_num_rows($result_search) > 0 && mysqli_num_rows($query_search_tblNULL) > 0) {
        // An available table is found, fetch the first row and return its user_id
        $row = mysqli_fetch_array($result_search);
        $available_table_id = $row['user_id'];

        // Update the appointment table with the assigned table_id
        $new_appointment_query = "UPDATE appointment SET table_id = '$available_table_id', appointment_session = '1' WHERE appointment_desc = 'Walk-In' AND table_id IS NULL LIMIT 1";
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
        <h1 class="highlight header-colorize text-white">Walk-In Appointment</h1>
    </div>

    <section class="home-section">
        <form action="" method="post">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-6">
                        <label>Customer's Name</label>
                        <input type="text" class="form-control text-uppercase" id="customer" name="customer" placeholder="Enter name" required>
                    </div>
                    <div class="col-6">
                    <label>No of people on the table</label>
                            <input type="number" class="form-control" id="pax" name="pax" min="1" placeholder="ENTER NO OF PEOPLE" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4">
                        <label>No of Senior</label>
                        <input type="number" class="form-control" id="senior" name="senior" min="0" value="0" placeholder="Enter no of senior" required>
                    </div>
                    <div class="col-4">
                        <label>No of PWD</label>
                        <input type="number" class="form-control" id="pwd" name="pwd" min="0" value="0" placeholder="Enter no of pwd" required>
                    </div>
                    <div class="col-4">
                        <label>Bday Promo</label>
                        <input type="number" class="form-control" id="bday" name="bday" min="0" value="0" placeholder="Enter no of bday promo"  required>
                    </div>
                </div>
                <div id="reminder"></div>
                <div class="form-group">
                    <label>Note</label>
                    <textarea type="text" class="form-control" id="note" name="note" placeholder="ENTER NOTE" rows="2"></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-danger">Submit <i class="bi bi-arrow-right"></i></button>
            </div>
        </form>
        <div style="overflow-x:auto;">
            <table class="table mt-5">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">Name</th>
                        <th class="text-center" scope="col">Table No</th>
                        <th class="text-center" scope="col">Count</th>
                        <th class="text-center" scope="col">Date</th>
                        <th class="text-center" scope="col">Time</th>
                        <th class="text-center" scope="col">Note</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                    <tbody class="bg-dark">
                    <?php 
                        $result_tb = mysqli_query($connection, "SELECT * FROM appointment
                        WHERE table_id is NULL 
                        AND appointment_session = '1' AND appointment_desc = 'Walk-In'");
                        if(mysqli_num_rows($result_tb) > 0) {
                        while ($row = mysqli_fetch_array($result_tb)) { ?> 
                            <tr>
                                <td class="text-center"><?php echo $row["appointment_name"]; ?></td>
                                <td class="text-center" style="display: none;" id="table_id"><?php echo $row["table_id"]; ?></td>
                                <td class="text-center">Waiting for available table...</td>
                                <td class="text-center"><?php echo $row["count"]; ?></td>
                                <td class="text-center"><?php echo date('F j, Y', strtotime($row["date"])); ?></td>
                                <td class="text-center"><?php echo date('g:i A', strtotime($row["time"])); ?></td>
                                <td><?php echo $row["note"]; ?></td>
                                <form action="create-walkin-appointment.php" method="post">
                                    <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
                                    <td><button type="submit" name="delete_appointment" class="btn btn-danger btn-xs">DELETE <i class="bi bi-trash"></i></button></td>
                                </form>
                            </tr>
                            <?php 
                        } 
                        } else { ?>
                            <tr>
                                <td class="text-center" colspan="8">No record found!</td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>  
            </table>
        </div>
    </section>
    </div>
    </div>
</div>
</body>
<!-- Footer -->
<footer class="main-footer text-center" style="background: #191919;">
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
        let inputValue = customerInput.value;
        // Keep only letters, spaces, and "-"
        const sanitizedValue = inputValue.replace(/[^a-zA-Z\s-]/g, '');
        const uppercasedValue = sanitizedValue.toUpperCase();
        customerInput.value = uppercasedValue;
    });


    const noteInput = document.getElementById('note');
    noteInput.addEventListener('input', function() {
        const inputValue = noteInput.value;
        // Keep only letters, spaces, and "-"
        const sanitizedValue = inputValue.replace(/[^a-zA-Z\s-]/g, '');
        noteInput.value = sanitizedValue;
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

    // Function for input validation
    function validateInput(inputElement, minValue, maxValue) {
        const inputValue = inputElement.value;
        
        // Remove any non-digit characters (including decimal points)
        const sanitizedValue = inputValue.replace(/[^0-9]/g, '');
        
        // Ensure the value is not empty
        if (sanitizedValue === '') {
            inputElement.value = minValue.toString(); // Set a default value if the input is empty
        } else {
            const value = parseInt(sanitizedValue, 10);
            
            // Ensure the value is within the specified range
            if (value < minValue) {
                inputElement.value = minValue.toString(); // Set the minimum value
            } else if (value > maxValue) {
                inputElement.value = maxValue.toString(); // Set the maximum value
            } else {
                inputElement.value = value; // Update the input value with the sanitized integer value
            }
        }
    }

    // Add event listeners for senior, pwd, and bday inputs
    const seniorInput = document.getElementById('senior');
    seniorInput.addEventListener('input', function() {
        validateInput(seniorInput, 0, 10);
    });

    const pwdInput = document.getElementById('pwd');
    pwdInput.addEventListener('input', function() {
        validateInput(pwdInput, 0, 10);
    });

    const bdayInput = document.getElementById('bday');
    bdayInput.addEventListener('input', function() {
        validateInput(bdayInput, 0, 3);
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
    
    const customerName = document.getElementById('customer');
    customerName.addEventListener('input', function() {
        const inputValue = customerName.value;

        // Truncate the input value to 100 characters
        if (inputValue.length > 100) {
            customerName.value = inputValue.slice(0, 100);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        var seniorInput = document.getElementById('senior');
        var pwdInput = document.getElementById('pwd');
        var bdayInput = document.getElementById('bday');
        var paxInput = document.getElementById('pax');
        var reminderDiv = document.getElementById('reminder');

        seniorInput.addEventListener('input', checkCombination);
        pwdInput.addEventListener('input', checkCombination);
        paxInput.addEventListener('input', checkCombination);
        bdayInput.addEventListener('input', checkBdayPromo);

        function checkCombination() {
            var seniorValue = parseInt(seniorInput.value);
            var pwdValue = parseInt(pwdInput.value);
            var paxValue = parseInt(paxInput.value);

            if (seniorValue + pwdValue > paxValue) {
                reminderDiv.innerHTML = '<p class="text-red">Reminder: The combination of Senior and PWD should not exceed the total number of people on the table.</p>';
                // Reset the values to 0
                seniorInput.value = 0;
                pwdInput.value = 0;
            } else {
                reminderDiv.innerHTML = '';
            }

            //checkBdayPromo(); // Check Bday Promo when any of the input fields change
        }

        function checkBdayPromo() {
            var paxValue = parseInt(paxInput.value);

            if (paxValue >= 15) {
                bdayInput.max = 3;
            } else if (paxValue >= 10) {
                bdayInput.max = 2;
            } else if (paxValue >= 5) {
                bdayInput.max = 1;
            } else {
                bdayInput.max = 0;
                bdayInput.value = 0;
            }

            if (parseInt(bdayInput.value) > bdayInput.max) {
                reminderDiv.innerHTML = 'Reminder: The number of birthday promos should not exceed the allowed limit based on the number of people on the table.';
                bdayInput.value = 0;
            } else {
                reminderDiv.innerHTML = '';
            }
        }

        seniorInput.addEventListener('input', function () {
            if (parseInt(seniorInput.value) > 0 || parseInt(pwdInput.value) > 0) {
                bdayInput.value = 0;
                bdayInput.disabled = true;
            } else {
                bdayInput.disabled = false;
            }
        });

        pwdInput.addEventListener('input', function () {
            if (parseInt(seniorInput.value) > 0 || parseInt(pwdInput.value) > 0) {
                bdayInput.value = 0;
                bdayInput.disabled = true;
            } else {
                bdayInput.disabled = false;
            }
        });

        bdayInput.addEventListener('input', function () {
            if (parseInt(bdayInput.value) > 0) {
                seniorInput.value = 0;
                pwdInput.value = 0;
                seniorInput.disabled = true;
                pwdInput.disabled = true;
            } else {
                seniorInput.disabled = false;
                pwdInput.disabled = false;
            }
        });
    });
</script>