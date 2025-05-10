<?php
// Include the necessary functions
require_once 'functions.php';

// Get all verified subscribers
$subscribers = file(__DIR__ . '/subscribers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Check if there are any subscribers
if (!empty($subscribers)) {
    // Loop through all subscribers and send reminders
    foreach ($subscribers as $email) {
        // Get all pending tasks
        $tasks = getAllTasks();
        $pending_tasks = [];

        // Filter pending tasks (tasks that are not marked as completed)
        foreach ($tasks as $task) {
            if (strpos($task, '(completed)') === false) {
                $pending_tasks[] = $task;
            }
        }

        // Only send reminders if there are pending tasks
        if (!empty($pending_tasks)) {
            sendTaskEmail($email, $pending_tasks);
        }
    }
}
?>
