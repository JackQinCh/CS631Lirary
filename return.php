<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/23/16
 * Time: 10:48
 */

include ('session.php');
// Get Request Data
$borID = $_GET['borid'];

// Create connection
$conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//
$sql = "UPDATE BORROWS SET RDTIME = NOW()
        WHERE BORNUMBER = $borID";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();

header("location: reader_borrowed.php");

