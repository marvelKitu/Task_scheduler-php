<?php
// Include the functions file to access the necessary functions
require_once 'functions.php';

// Check if the email and verification code are present in the URL parameters
if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = urldecode($_GET['email']); // Decode the email address
    $code = $_GET['code']; // Get the verification code from the URL

    // Attempt to verify the subscription
    $verification_success = verifySubscription($email, $code);

    if ($verification_success) {
        // If verification is successful, show a success message
        echo "<h2>Subscription Verified Successfully!</h2>";
        echo "<p>Thank you for subscribing. You will now receive task reminders.</p>";
    } else {
        // If verification fails, show an error message
        echo "<h2>Invalid Verification Code</h2>";
        echo "<p>The verification code or email is invalid. Please try again or contact support.</p>";
    }
} else {
    // If email or code is missing from the URL, show an error message
    echo "<h2>Error: Missing email or verification code</h2>";
    echo "<p>Please ensure the verification link is correct.</p>";
}
?>
