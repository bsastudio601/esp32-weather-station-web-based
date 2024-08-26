<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = connectDB(); // Connect to the database

    $api_key = escape_data($_POST["api_key"]);
    if ($api_key == PROJECT_API_KEY) {
        $temperature = escape_data($_POST["temperature"]);
        $humidity = escape_data($_POST["humidity"]);
        $pressure = escape_data($_POST["pressure"]);

        $sql = "INSERT INTO tbl_temperature (temperature, humidity, pressure, created_date) VALUES ('$temperature', '$humidity', '$pressure', '" . date("Y-m-d H:i:s") . "')";
        if ($db->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $db->error;
        } else {
            echo "OK. INSERT ID: " . $db->insert_id;
        }
    } else {
        echo "Wrong API Key";
    }

    $db->close(); // Close the database connection
} else {
    echo "No HTTP POST request found";
}

function escape_data($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
