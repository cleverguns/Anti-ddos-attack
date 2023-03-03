<?php

// Set the request limit and time interval
$requestLimit = 100; // Maximum number of requests per minute
$timeInterval = 60; // Time interval in seconds

// Get the client's IP address
$clientIP = $_SERVER['REMOTE_ADDR'];

// Set the log file path
$logFile = 'C:\xampp\htdocs\vehicle_service\file.txt';

// Get the current timestamp
$timestamp = date('Y-m-d H:i:s');

// Read the log file
$logData = file_get_contents($logFile);

// Split the log file into lines
$logLines = explode("\n", $logData);

// Initialize the request count
$requestCount = 0;

// Count the number of requests from the client within the time interval
foreach ($logLines as $logLine) {
    // Skip empty lines
    if (empty($logLine)) {
        continue;
    }

    // Parse the log line into a timestamp and IP address
    list($logTimestamp, $logIP) = explode(' ', $logLine);

    // Check if the log entry is within the time interval
    if ((strtotime($timestamp) - strtotime($logTimestamp)) <= $timeInterval) {
        // Check if the IP address matches the client's IP
        if ($logIP == $clientIP) {
            $requestCount++;
        }
    }
}

// Check if the request count exceeds the limit
if ($requestCount >= $requestLimit) {
    // Log the request limit exceeded event
    $logMessage = sprintf("[%s] IP address %s exceeded request limit.\n", $timestamp, $clientIP);
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    http_response_code(429);
    exit('Too many requests.');
}

// Log the request event
$logMessage = sprintf("[%s] IP address %s made a request.\n", $timestamp, $clientIP);
file_put_contents($logFile, $logMessage, FILE_APPEND);

// Output the response
echo 'Hello, world!';
