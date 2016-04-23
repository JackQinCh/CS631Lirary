<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/23/16
 * Time: 08:05
 */

include ('session.php');

$resID = $_GET['resid'];

// Create connection
$conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//
$sql = "SELECT READERID, DOCID, COPYNO, LIBID
        FROM RESERVES
        WHERE RESUMBER = $resID";
$result = $conn->query($sql);
if ($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $sql = "INSERT INTO BORROWS (READERID, DOCID, COPYNO, LIBID, BDTIME) VALUES 
            ('".$row['READERID']."', '".$row['DOCID']."', ".$row['COPYNO'].", ".$row['LIBID'].", NOW())";
    if ($conn->query($sql) === TRUE) {
        echo "Insert successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $sql = "DELETE FROM RESERVES 
        WHERE RESUMBER = $resID";

    if ($conn->query($sql) === TRUE) {
        echo "Delete successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
header("location: reader_reserves.php");
