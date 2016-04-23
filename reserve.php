<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/22/16
 * Time: 18:30
 */

include ('session.php');
// Get Request Data
$docID = $_GET['docid'];
$docNum = $_GET['num-copy'];

// Create connection
$conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Query copy
$sql="SELECT C.COPYNO, C.LIBID
      FROM COPY C
      WHERE C.DOCID = '$docID' AND C.COPYNO NOT IN (
        SELECT R.COPYNO
        FROM RESERVES R
        WHERE R.DOCID = '$docID' AND R.LIBID = C.LIBID
      ) AND C.COPYNO NOT IN (
        SELECT B.COPYNO
        FROM BORROWS B
        WHERE B.DOCID = '$docID' AND B.LIBID = C.LIBID AND B.RDTIME IS NULL
      )";
$result = $conn->query($sql);

if ($result->num_rows > 0){
    $i = $docNum;
    while ($row = $result->fetch_assoc() and $i>0){
        $sql1 = "INSERT INTO RESERVES (READERID, DOCID, COPYNO, LIBID, DTIME) VALUES 
                 ('$readerId', '$docID', ".$row["COPYNO"].", ".$row["LIBID"].", NOW())";

        if ($conn->query($sql1) === TRUE) {
        } else {
            echo "Error: " . $conn->error;
        }
        $i--;
    }
}
$conn->close();

header("location: reader_reserves.php");