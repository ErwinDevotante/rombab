<?php 
session_start();
include 'conn.php';
function updateSessionTb($connection) {

$current_date_time = date('Y-m-d H:i:s');
$query_check_available_tables = "SELECT * FROM appointment 
                        WHERE table_id IS NULL 
                        AND appointment_desc = 'Online' AND
                        TIMESTAMPDIFF(SECOND, '$current_date_time', CONCAT(date,' ',time)) <= 1800
                        LIMIT 1";
$result_check_available_tables = mysqli_query($connection, $query_check_available_tables);

$query_search = "SELECT user_id FROM users WHERE session_tb = '1'";
$result_search = mysqli_query($connection, $query_search);


if (mysqli_num_rows($result_check_available_tables) > 0 && mysqli_num_rows($result_search) > 0) {
    
    $row_available_table = mysqli_fetch_array($result_search);
    $available_table_id = $row_available_table['user_id'];

    // Update session_tb to 3
    $update_session_tb_query = "UPDATE users SET session_tb = '3' WHERE user_id = '{$_SESSION['user_id']}'";
    mysqli_query($connection, $update_session_tb_query);

    // Return the available table_id
    return $available_table_id;
} else {
    return NULL;
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
$description = "Online";
$time = $_POST["timeInput"];
$date = $_POST["dateInput"];

$senior = isset($_POST["senior"]) ? $_POST["senior"] : 0;
$pwd = isset($_POST["pwd"]) ? $_POST["pwd"] : 0;
$bday_no = isset($_POST["bday"]) ? $_POST["bday"] : 0;

if (empty($note)) {
    $note = "No note";
}

$date_time_combined = $date . ' ' . $time;
$current_date_time = date('Y-m-d H:i:s');

// Check if the provided date and time are within 30 minutes of the current date and time
$diff = strtotime($date_time_combined) - strtotime($current_date_time);
$minutes_difference = floor($diff / 60);

if ($minutes_difference <= 30) {
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
        
    } else {
        $query_add = "INSERT INTO appointment(appointment_id, appointment_name, appointment_desc, table_id, count, date, time, senior_no, pwd_no, bday_no, note, appointment_session) VALUES('', '$name', '$description', NULL, '$pax', '$date', '$time', '$senior', '$pwd', '$bday_no', '$note', '1')";
        $result_query_add = mysqli_query($connection, $query_add);
    }
} else {
    $query_add = "INSERT INTO appointment(appointment_id, appointment_name, appointment_desc, table_id, count, date, time, senior_no, pwd_no, bday_no, note, appointment_session) VALUES('', '$name', '$description', NULL, '$pax', '$date', '$time', '$senior', '$pwd', '$bday_no', '$note', '4')";
    $result_query_add = mysqli_query($connection, $query_add);
}

unset($_POST);
header('Location: create-online-appointment.php');
}
updateSessionTb($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Create an appointment</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="assets/rombab-logo.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="node_modules/admin-lte/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="node_modules/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="node_modules/ionicons/css/ionicons.min.css">
    <!-- JQuery -->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons CSS -->
    <link href="node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-black">
<nav class="navbar navbar-expand-lg navbar-black">
    <div class="container-fluid bg-black">
        <a href="index.php"><img class="p-2" src="assets/rombab-logo.png" alt="Romantic Baboy Logo" width="130"> </a>
        <a class="navbar-brand" href="index.php">
            <img src="assets/unlimited_korean_grill.png" alt="Romantic Baboy Logo" width="400">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto order-2 order-lg-1">
            </ul>
            <ul class="navbar-nav ms-auto order-3 order-lg-3 gap-3">
                <li class="nav-item">
                    <a onclick="scrollToCreate()" class="nav-link active text-white">Create Appointment <i class="bi bi-plus-lg"></i></a>
                </li>
                <li class="nav-item">
                    <a onclick="scrollToShow()" class="nav-link active text-white cursor-point">Show Table <i class="bi bi-list-stars"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <div id="create-appointment">

    <div class="bg-black">
    <?php
    $available_table_id = updateSessionTb($connection);

    if ($available_table_id !== NULL) {
        // Update the appointment table with the assigned table_id
        $new_appointment_query = "UPDATE appointment SET table_id = '$available_table_id', appointment_session = '1' WHERE appointment_desc = 'Online' AND table_id IS NULL LIMIT 1";
        $result_new_appointment = mysqli_query($connection, $new_appointment_query);
    
        // Deactivate the assigned table in the users table
        $deactivate_table_query = "UPDATE users SET session_tb = '3' WHERE user_id = '$available_table_id'";
        $result_deactivate_table = mysqli_query($connection, $deactivate_table_query);
    
        if ($result_new_appointment && $result_deactivate_table) {
            // Return the assigned table_id to update the appointment table
            echo $available_table_id;
        } else { ?>
            <span class="text-dark">NULL</span>
        <?php }
    } else { ?>
        <span class="text-black">NULL</span>
    <?php } ?>

    <div class="content p-5">

    <div class="container-fluid text-center text-white">
        <h1>ONLINE APPOINTMENT</h1>
    </div>

    <section class="home-section text-white">
        <form action="" method="post">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-6">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="customer" name="customer" placeholder="Enter name" required>
                    </div>
                    <div class="col-6">
                    <label>Count</label>
                            <input type="number" class="form-control" id="pax" name="pax" min="1" placeholder="Enter no of people" required>
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
                <div class="form-group row mt-5">
                    <div class="col-6">
                        <label for="timeInput">Appointment Time</label>
                        <input type="time" class="form-control" id="timeInput" name="timeInput" value="<?php echo date("H:i"); ?>" min="10:00" max="21:00" required>
                    </div>
                    <div class="col-6">
                        <label for="dateInput">Appointment Date</label>
                        <?php 
                        $dateNow = date("Y-m-d");
                        $futureDate = date("Y-m-d", strtotime($dateNow . " +1 day"));
                        ?>
                        <input type="date" class="form-control" id="dateInput" name="dateInput" value="<?php echo date("Y-m-d"); ?>" max="<?php echo $futureDate; ?>" min="<?php echo date("Y-m-d"); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Note <small><i>(pls include a contact number)</i></small></label>
                    <textarea type="text" class="form-control" id="note" name="note" placeholder="Enter note" rows="2"></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-danger">Submit <i class="bi bi-arrow-right"></i></button>
            </div>
        </form>

        <div style="overflow-x:auto;">
            <table class="table table-hover table-bordered table-dark mt-2">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">Name</th>
                        <th class="text-center" scope="col">Table No</th>
                        <th class="text-center" scope="col">Count</th>
                        <th class="text-center" scope="col">Type</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php 
                        $result_tb = mysqli_query($connection, "SELECT * FROM appointment
                        WHERE table_id is NULL 
                        AND (appointment_session = '4' OR appointment_session = '1')
                        ORDER BY date, time ASC");
                        if(mysqli_num_rows($result_tb) > 0) {
                        while ($row = mysqli_fetch_array($result_tb)) { ?> 
                            <tr>
                                <td class="text-center"><?php echo $row["appointment_name"]; ?></td>
                                <td class="text-center" style="display: none;" id="table_id"><?php echo $row["table_id"]; ?></td>
                                <?php if($row["appointment_session"] == 1) { ?>
                                    <td class="text-center">Waiting for available table</td>
                                <?php } else { ?>
                                    <td class="text-center">For queuing (Online Appointment)</td>
                                <?php }?>
                                <td class="text-center"><?php echo $row["count"];  ?></td>
                                <td class="text-center"><?php echo $row["appointment_desc"]; ?></td>
                            </tr>
                            <?php 
                        } 
                        } else { ?>
                            <tr>
                                <td class="text-center" colspan="4">No record found!</td>
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


    <div id="table-availability">

    <div class="bg-black">
    <div class="content p-5">

    <section class="home-section">

    <form action="manage-appointment.php" method="post" enctype="multipart/form-data" onsubmit="rememberScrollPosition()">
    <div style="overflow-x:auto;">
        <table class="table table-hover table-bordered table-dark">
            <thead>
                <tr>
                    <th class="text-center w-25" scope="col">Table Number</th>
                    <th class="text-center" scope="col">Customer</th>
             
                </tr>
            </thead>
                <tbody>
                    
                    <?php 
                    $result_tb = mysqli_query($connection, "SELECT appointment.table_id, appointment.count, appointment.appointment_name, appointment.appointment_desc, appointment.appointment_session, appointment.pwd_no, appointment.senior_no, appointment.bday_no, appointment.time, appointment.date, appointment_id,
                                                            users.user_id, users.name, users.session_tb, users.user_role 
                                                            FROM appointment RIGHT JOIN users 
                                                            ON users.user_id=appointment.table_id
                                                            WHERE users.user_role = '4'
                                                            ORDER BY users.user_id ASC");
                    if(mysqli_num_rows($result_tb) > 0){
                    while ($row = mysqli_fetch_array($result_tb)) { ?> 
                        <tr>
                            <td class="text-center"> <?php echo $row['name'];
                                
                                if($row['session_tb'] == '0') {  ?>
                                    <p class="text-black font-weight-bold"><a class="badge badge-warning">[NOT AVAILABLE]</a></p>
                                <?php }
                                else if($row['session_tb'] == '1')  { ?>
                                    <p class="font-weight-bold"><a class="badge badge-success">[ACTIVATED]</a></p>
                                <?php }
                                else if ($row['session_tb'] == '2')  {?>
                                    <p class="font-weight-bold"><a class="badge badge-danger">[DEACTIVATED]</a></p>
                                <?php } else { ?>
                                    <p class="font-weight-bold"><a class="badge badge-primary">[OCCUPIED]</a></p>
                                <?php } ?>
                            </td>
                            <td>
                                    <?php if ($row['session_tb'] == '3' && ($row['appointment_session'] == '1' && $row['table_id'] !== null)){ ?>
                                            <select class="form-control" name="appointment" id="appointment" data-toggle="tooltip" data-placement="top" data-bs-html="true" title="<?php echo $row["appointment_desc"]; ?>, Count: <?php echo $row["count"]; ?>" disabled>
                                                    <option> [OCCUPIED] <?php echo $row['appointment_name']; ?> </option>
                                            </select>
                                    <?php }
                                    else {?>
                                        <select class="form-control" name="appointment" id="appointment" disabled>
                                            <option>Not available</option>
                                        </select>
                                    <?php }?>                   
                            </td>
                            
                        </tr>
                    <?php 
                    } }
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
<footer class="bg-black text-center">
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
        url: 'create-online-appointment.php',
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
        let sanitizedValue = inputValue.replace(/[^a-zA-Z\s-]/g, '');
        sanitizedValue = sanitizedValue.toUpperCase(); // Convert to uppercase
        customerInput.value = sanitizedValue;
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

    function scrollToCreate() {
        var div = document.getElementById('create-appointment');
        div.scrollIntoView({ behavior: 'smooth' });
    }

    function scrollToShow() {
        var div = document.getElementById('table-availability');
        div.scrollIntoView({ behavior: 'smooth' });
    }
</script>