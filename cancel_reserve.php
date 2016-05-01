<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/23/16
 * Time: 07:58
 */

include('./api/session_reader.php');

$resID = $_GET['resid'];

// Create connection
$conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//
$sql = "DELETE FROM RESERVES 
        WHERE RESUMBER = $resID";

if ($conn->query($sql) === TRUE) {
    echo "Delete successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

header("location: reader_reserves.php");
