<?php
// Replace these with your actual values
$VERIFY_TOKEN = "your_verification_token";
$PAGE_ACCESS_TOKEN = "your_page_access_token";
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "romantic_baboy_dbase";

// Verify the Facebook webhook
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $hub_verify_token = $_GET['hub_verify_token'];
    if ($hub_verify_token === $VERIFY_TOKEN) {
        echo $_GET['hub_challenge'];
        exit;
    } else {
        die("Invalid verification token");
    }
}

// Get the incoming JSON data from Facebook
$input = json_decode(file_get_contents('php://input'), true);

// Process incoming messages
if (!empty($input['entry'][0]['messaging'])) {
    foreach ($input['entry'][0]['messaging'] as $message) {
        $sender_id = $message['sender']['id'];
        $message_text = $message['message']['text'];
        $timestamp = $message['timestamp'];

        // Store the user response in the database
        storeUserResponse($sender_id, $message_text, $timestamp);

        // Prepare the response to be sent back to the user
        $response = "Thank you for your message!";

        // Send the response back to the user
        sendResponse($sender_id, $response);
    }
}

function storeUserResponse($sender_id, $message_text, $timestamp) {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;

    // Create a connection to the database
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape user input to prevent SQL injection
    $sender_id = $conn->real_escape_string($sender_id);
    $message_text = $conn->real_escape_string($message_text);
    $timestamp = $conn->real_escape_string($timestamp);

    // Insert the user response into the database
    $sql = "INSERT INTO user_responses (sender_id, message_text, timestamp) 
            VALUES ('$sender_id', '$message_text', '$timestamp')";
    if ($conn->query($sql) === TRUE) {
        // User response saved successfully
    } else {
        // Error saving the user response
    }

    // Close the database connection
    $conn->close();
}

function sendResponse($recipient_id, $message_text) {
    global $PAGE_ACCESS_TOKEN;

    $url = "https://graph.facebook.com/v12.0/me/messages?access_token=" . $PAGE_ACCESS_TOKEN;
    $data = array(
        'recipient' => array('id' => $recipient_id),
        'message' => array('text' => $message_text),
    );
    $options = array(
        'http' => array(
            'method' => 'POST',
            'content' => json_encode($data),
            'header' => "Content-Type: application/json",
        ),
    );
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}
