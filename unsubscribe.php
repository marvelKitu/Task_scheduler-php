<?php
// Include the functions file to access necessary functions
require_once 'functions.php';

// Check if the email is present in the URL
if (isset($_GET['email'])) {
    $email = urldecode($_GET['email']); // Decode the email address

    // Attempt to unsubscribe the email
    $unsubscribe_success = unsubscribeEmail($email);

    if ($unsubscribe_success) {
        // If unsubscribe is successful, show a success message
        echo "<h2>You have been unsubscribed successfully!</h2>";
        echo "<p>You will no longer receive task reminders.</p>";
    } else {
        // If unsubscribe fails (email not found or some error), show an error message
        echo "<h2>Unsubscribe Failed</h2>";
        echo "<p>There was an issue unsubscribing the email. Please try again or contact support.</p>";
    }
} else {
    // If the email is missing from the URL, show an error message
    echo "<h2>Error: Missing email</h2>";
    echo "<p>Please ensure the unsubscribe link is correct.</p>";
}
?>
