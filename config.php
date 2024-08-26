<?php
define('DB_HOST', 'fdb1029.awardspace.net');
define('DB_USERNAME', '4495636_espdata');
define('DB_PASSWORD', 'Ty+2te#k8Rb@;{kh');
define('DB_NAME', '4495636_espdata');

define('POST_DATA_URL', 'http://arthiprojects.atwebpages.com/sensordata.php');

// PROJECT_API_KEY is the exact duplicate of PROJECT_API_KEY in the NodeMCU sketch file
// Both values must be the same
define('PROJECT_API_KEY', 'iloveher143');

// Set time zone for your country
date_default_timezone_set('Asia/Dhaka');

// Function to connect to the database
function connectDB() {
    $db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    return $db;
}


// Note: The closing PHP tag is omitted intentionally to avoid unwanted output.
