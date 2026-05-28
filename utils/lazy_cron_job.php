<?php 
    declare (strict_types=1);

    require_once(__DIR__ . '/lazy_cron_job.class.php');

    $cooldown = 900; // 15 minutes in seconds (every 15 minutes the cron job will be executed at most)

    $lastExecutionFile = __DIR__ . '/.last_cron_exec.txt'; // File to store the timestamp of the last execution

    // Initialize the file if it doesn't exist
    if(!file_exists($lastExecutionFile)) {
        file_put_contents($lastExecutionFile, '0');
    }

    $fp = fopen($lastExecutionFile, 'r+'); // Open file with permission for reading and writing

    if (flock($fp, LOCK_EX)) { // Acquire an exclusive lock
        $lastExecution = (int)fread($fp, filesize($lastExecutionFile)); // Read the last execution timestamp
        $currentTime = time(); // Current timestamp

        if ($currentTime - $lastExecution >= $cooldown) {
            // Update the last execution timestamp
            ftruncate($fp, 0); // Clear the file content
            rewind($fp); // Move the pointer back to the beginning of the file
            fwrite($fp, (string)$currentTime); // Write the current timestamp


            LazyCronJob::execute();
        }

        flock($fp, LOCK_UN); // Release the lock
    }
?>