<?php
// save_user_agent.php

// Check if the user agent data is received
if (isset($_POST['userAgent'])) {
    $userAgent = $_POST['userAgent'];

    // File to save user agents
    $file = 'useragent.txt';

    // Check if the file exists
    if (file_exists($file)) {
        // Read the file contents
        $fileContents = file_get_contents($file);

        // Check if the user agent already exists in the file
        if (strpos($fileContents, $userAgent) !== false) {
            // User agent already exists, do not write it again
            echo "User agent already exists.";
        } else {
            // User agent does not exist, append it to the file
            file_put_contents($file, $userAgent . PHP_EOL, FILE_APPEND);
            echo "User agent saved.";
        }
    } else {
        // If the file doesn't exist, create it and write the user agent
        file_put_contents($file, $userAgent . PHP_EOL);
        echo "User agent saved.";
    }
} else {
    echo "No user agent data received.";
}
?>