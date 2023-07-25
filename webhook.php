<?php
// Your Facebook Page Access Token
$access_token = 'YOUR_PAGE_ACCESS_TOKEN';
// Your SQL database credentials
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "romantic_baboy_dbase";

// Handle incoming messages from Facebook Messenger
$input = json_decode(file_get_contents('php://input'), true);
if (!empty($input['entry'][0]['messaging'])) {
    foreach ($input['entry'][0]['messaging'] as $message) {
        if (!empty($message['message']['text'])) {
            // Extract sender ID and message text
            $sender_id = $message['sender']['id'];
            $message_text = $message['message']['text'];

            // Check if the message is an appointment request
            if (strpos($message_text, 'appointment') !== false) {
                // Extract the appointment date and time
                $appointment_date = ''; // You need to extract the date from the message, parse it, and store it here
                $appointment_time = ''; // You need to extract the time from the message, parse it, and store it here

                // Store the appointment in the SQL database
                $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
                if ($conn->connect_error) {
                    die('Connection failed: ' . $conn->connect_error);
                }

                // Escape the appointment details to prevent SQL injection
                $escaped_sender_id = $conn->real_escape_string($sender_id);
                $escaped_appointment_date = $conn->real_escape_string($appointment_date);
                $escaped_appointment_time = $conn->real_escape_string($appointment_time);

                // Insert appointment into the database
                $query = "INSERT INTO appointments (sender_id, appointment_date, appointment_time) VALUES ('$escaped_sender_id', '$escaped_appointment_date', '$escaped_appointment_time')";
                $conn->query($query);

                // Close the database connection
                $conn->close();

                // Send a confirmation message back to the user
                $response_message = "Your appointment has been scheduled for $appointment_date at $appointment_time.";
                sendResponse($sender_id, $response_message);
            } else {
                // Send a default response if the message is not an appointment request
                $response_message = "I'm sorry, I can only schedule appointments. Please use the word 'appointment' to schedule one.";
                sendResponse($sender_id, $response_message);
            }
        }
    }
}

// Function to send a response back to the user using Facebook's Send API
function sendResponse($recipient_id, $message) {
    global $access_token;
    $data = array(
        'recipient' => array('id' => $recipient_id),
        'message' => array('text' => $message)
    );
    $options = array(
        'http' => array(
            'method' => 'POST',
            'content' => json_encode($data),
            'header' => "Content-Type: application/json\n"
        )
    );
    $context = stream_context_create($options);
    file_get_contents("https://graph.facebook.com/v3.3/me/messages?access_token=$access_token", false, $context);
}
?>
