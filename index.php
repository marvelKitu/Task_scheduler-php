<?php
// Include functions.php to access the required functions
require_once 'functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new task
    if (isset($_POST['task-name'])) {
        addTask($_POST['task-name']);
    }

    // Subscribe to email notifications
    if (isset($_POST['email'])) {
        subscribeEmail($_POST['email']);
    }

    // Handle task deletion
    if (isset($_POST['delete-task']) && isset($_POST['task-id'])) {
        deleteTask($_POST['task-id']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Scheduler</title>
</head>
<body>
    <h1>Task Scheduler</h1>

    <!-- Task Management -->
    <h2>Add a New Task</h2>
    <form action="index.php" method="POST">
        <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required>
        <button type="submit" id="add-task">Add Task</button>
    </form>

    <h3>Task List</h3>
    <ul class="task-list">
        <?php
        // Get all tasks from the file
        $tasks = getAllTasks();
        foreach ($tasks as $index => $task) {
            echo "<li class='task-item'>";
            echo "<input type='checkbox' class='task-status' " . (strpos($task, '(completed)') !== false ? 'checked' : '') . ">";
            echo htmlspecialchars($task);

            // Add delete button form
            echo "<form action='index.php' method='POST' style='display:inline; margin-left:10px;'>";
            echo "<input type='hidden' name='task-id' value='$index'>";
            echo "<button type='submit' name='delete-task'>Delete</button>";
            echo "</form>";

            echo "</li>";
        }
        ?>
    </ul>

    <!-- Email Subscription -->
    <h2>Subscribe for Task Reminders</h2>
    <form action="index.php" method="POST">
        <input type="email" name="email" required placeholder="Enter your email">
        <button type="submit" id="submit-email">Subscribe</button>
    </form>

</body>
</html>
