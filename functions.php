<?php

$base_url = "http://yourdomain.com"; // Change this to your actual domain

// Add a new task to tasks.txt
function addTask($task_name) {
    $tasks = getAllTasks();

    foreach ($tasks as $task) {
        if (trim($task) == trim($task_name)) {
            return false; // Task already exists
        }
    }

    file_put_contents(__DIR__ . '/tasks.txt', trim($task_name) . PHP_EOL, FILE_APPEND);
    return true;
}

// Retrieve all tasks
function getAllTasks() {
    $file = __DIR__ . '/tasks.txt';
    return file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
}

// Mark task as completed or not
function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = getAllTasks();

    if (isset($tasks[$task_id])) {
        $tasks[$task_id] = $is_completed
            ? $tasks[$task_id] . ' (completed)'
            : str_replace(' (completed)', '', $tasks[$task_id]);

        file_put_contents(__DIR__ . '/tasks.txt', implode(PHP_EOL, $tasks) . PHP_EOL);
        return true;
    }

    return false;
}

// Delete a task
function deleteTask($task_id) {
    $tasks = getAllTasks();

    if (isset($tasks[$task_id])) {
        unset($tasks[$task_id]);
        $tasks = array_values($tasks); // Re-index
        file_put_contents(__DIR__ . '/tasks.txt', implode(PHP_EOL, $tasks) . PHP_EOL);
        return true;
    }

    return false;
}

// Generate verification code
function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Simulate sending email
function sendEmail($to, $subject, $body) {
    $log_entry = "TO: $to\nSUBJECT: $subject\nBODY:\n$body\n\n-----------------------\n";
    file_put_contents(__DIR__ . '/email_log.txt', $log_entry, FILE_APPEND);
}

// Subscribe with verification
function subscribeEmail($email) {
    global $base_url;

    $verification_code = generateVerificationCode();
    $pending = $email . ',' . $verification_code . PHP_EOL;
    file_put_contents(__DIR__ . '/pending_subscriptions.txt', $pending, FILE_APPEND);

    $link = $base_url . "/verify.php?email=" . urlencode($email) . "&code=" . $verification_code;
    $subject = "Verify your Task Planner subscription";
    $body = "<p>Click below to verify your subscription:</p><p><a href=\"$link\">Verify Subscription</a></p>";

    sendEmail($email, $subject, $body);
}

// Verify subscription
function verifySubscription($email, $code) {
    $file = __DIR__ . '/pending_subscriptions.txt';
    $pending = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($pending as $line) {
        list($pending_email, $pending_code) = explode(',', $line);
        if ($pending_email == $email && $pending_code == $code) {
            file_put_contents(__DIR__ . '/subscribers.txt', $email . PHP_EOL, FILE_APPEND);

            $pending = array_filter($pending, fn($l) => explode(',', $l)[0] !== $email);
            file_put_contents($file, implode(PHP_EOL, $pending) . PHP_EOL);

            return true;
        }
    }

    return false;
}

// Unsubscribe user
function unsubscribeEmail($email) {
    $file = __DIR__ . '/subscribers.txt';
    if (!file_exists($file)) return false;

    $subscribers = file($file, FILE_IGNORE_NEW_LINES);

    if (!in_array($email, $subscribers)) return false;

    $subscribers = array_filter($subscribers, fn($sub) => $sub !== $email);
    file_put_contents($file, implode(PHP_EOL, $subscribers) . PHP_EOL);
    return true;
}

// Send reminders to all subscribers
function sendTaskReminders() {
    $subscribers = file(__DIR__ . '/subscribers.txt', FILE_IGNORE_NEW_LINES);
    $tasks = getAllTasks();

    foreach ($subscribers as $email) {
        $pending = array_filter($tasks, fn($t) => strpos($t, '(completed)') === false);
        if (!empty($pending)) {
            sendTaskEmail($email, $pending);
        }
    }
}

// Send task email with unsubscribe link
function sendTaskEmail($email, $pending_tasks) {
    global $base_url;

    $subject = "Task Planner - Pending Tasks Reminder";
    $body = "<h2>Pending Tasks</h2><ul>";

    foreach ($pending_tasks as $task) {
        $body .= "<li>" . htmlspecialchars($task) . "</li>";
    }

    $body .= "</ul><p><a href=\"$base_url/unsubscribe.php?email=" . urlencode($email) . "\">Unsubscribe from notifications</a></p>";

    sendEmail($email, $subject, $body);
}
?>
