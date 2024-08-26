<?php
require 'config.php';

$db = connectDB(); // Connect to the database

$sql = "SELECT * FROM tbl_temperature WHERE 1 ORDER BY id DESC";
$result = $db->query($sql);

if (!$result) {
    echo "Error: " . $sql . "<br>" . $db->error;
} else {
    $row = $result->fetch_assoc();
    echo json_encode($row);
}

$db->close(); // Close the database connection
