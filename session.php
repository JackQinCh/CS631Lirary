<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/14/16
 * Time: 13:33
 */

include('config.php');
session_start();

if(!isset($_SESSION['readerId'])){
    header("location: reader_index.php");
}

$readerId = $_SESSION['readerId'];

// Create connection
$conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//Query
$sql = "SELECT RNAME FROM READER WHERE READERID = '$readerId'";
$result = $conn->query($sql);
$conn->close();
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $readerName = $row['RNAME'];
}else{
    header("location: reader_index.php");
}
