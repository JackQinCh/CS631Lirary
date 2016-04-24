<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/24/16
 * Time: 15:19
 */

// Get Request Data
$docID = $_GET['docid'];
$copyNo = $_GET['copyid'];
$libID = $_GET['libid'];

$page = 'Search';
include('layout/admin_header.php');
include('layout/admin_sidebar.php');
?>

<!-- // Content -->
<div class="container layout-content">
    <!-- // Breadcrumb -->
    <ol class="breadcrumb m-t-1">
        <li><a href="admin.php">Search</a></li>
        <li class="active">Detail</li>
    </ol>
    <!-- // End Breadcrumb -->
    <?php
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //
    $status = '';
    $sql = "SELECT READERID, DTIME
            FROM RESERVES
            WHERE DOCID = '$docID' AND COPYNO = $copyNo AND LIBID = $libID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $status = "Reserved by ".$row['READERID']." at ".$row['DTIME'];
    }else{
        $sql = "SELECT READERID, BDTIME
            FROM BORROWS
            WHERE DOCID = '$docID' AND COPYNO = $copyNo AND LIBID = $libID AND RDTIME IS NOT NULL";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $status = "Borrowed by ".$row['READERID']." at ".$row['BDTIME'];
        }else{
            $status = "In Stock";
        }
    }
    $sql = "SELECT D.DOCID, D.TITLE, C.COPYNO, C.POSITION, B.LNAME, B.LLOCATION
            FROM DOCUMENT D, COPY C, BRANCH B
            WHERE D.DOCID = C.DOCID AND C.LIBID = B.LIBID AND 
              C.DOCID = '$docID' AND C.COPYNO = $copyNo AND C.LIBID = $libID";
    $result = $conn->query($sql);
    if ($result->num_rows == 1){
        $row = $result->fetch_assoc();
        echo    "<div class='card'>
                    <div class='card-block'>
                        <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE']."</h4>
                        <p class='card-text'>Copy ID: ".$row['COPYNO']."</p>
                        <p class='card-text'>Position: ".$row['LNAME']." (".$row['LLOCATION'].") &bull; ".$row['POSITION']."</p>
                        <p class='card-text text-muted'>Status: ".$status."</p>
                    </div>
                </div>";
    }else{
        echo "0 copy";
    }
    $conn->close();
    ?>
</div>

<?php
include ('layout/footer.php');
?>
