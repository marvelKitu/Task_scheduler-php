#!/bin/bash

# Path to the PHP executable (you may need to adjust this based on your server configuration)
PHP_PATH="C:\php\php.exe"

# Path to the cron.php script (adjust the path as needed)
CRON_SCRIPT_PATH="C:\Users\USER\OneDrive\Desktop\Task Scheduler\src\cron.php"

# Add the cron job to run every hour
(crontab -l 2>/dev/null; echo "0 * * * * $PHP_PATH $CRON_SCRIPT_PATH") | crontab -

# Confirm the cron job has been added
echo "Cron job added successfully. It will run every hour."
