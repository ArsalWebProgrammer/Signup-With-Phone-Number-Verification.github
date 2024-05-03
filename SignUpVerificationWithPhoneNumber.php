<?php
// Include Twilio PHP SDK
require_once 'vendor/autoload.php';

use Twilio\Rest\Client;

// Your Twilio credentials
$account_sid = 'YOUR_TWILIO_ACCOUNT_SID';
$auth_token = 'YOUR_TWILIO_AUTH_TOKEN';
$twilio_number = 'YOUR_TWILIO_PHONE_NUMBER';

// Initialize Twilio client
$client = new Client($account_sid, $auth_token);

// Function to generate random verification code
function generateVerificationCode() {
    return rand(1000, 9999); // Generate a 4-digit random code
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data (e.g., check for empty fields, validate phone number format, etc.)
    $phone_number = $_POST['phone_number'];
    
    // Generate a random verification code
    $verification_code = generateVerificationCode();
    
    // Send verification code via SMS
    try {
        $client->messages->create(
            $phone_number,
            array(
                'from' => $twilio_number,
                'body' => 'Your verification code is: ' . $verification_code
            )
        );
        // Save verification code in session or database for later verification
        $_SESSION['verification_code'] = $verification_code;
        $_SESSION['phone_number'] = $phone_number;
        
        // Redirect to verification page
        header('Location: verify.php');
        exit;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup with Phone Number Verification</title>
</head>
<body>
    <h2>Signup with Phone Number Verification</h2>
    <form method="post">
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required>
        <button type="submit">Send Verification Code</button>
    </form>
</body>
</html>
