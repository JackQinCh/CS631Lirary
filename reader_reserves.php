<?php
$page = 'Reserved';
include('layout/reader_header.php');
include('layout/reader_sidebar.php');

?>

<!-- // Content -->
<div class="container layout-content">
    <!-- Reserves List -->
    <?php
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT COUNT(BORNUMBER)
            FROM BORROWS
            WHERE READERID = '$readerId' AND RDTIME IS NULL
            GROUP BY READERID";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $borrow_num = $row['COUNT(BORNUMBER)'];
    if ($borrow_num >= 10){
        $checkout_class = 'disabled';
    }else{
        $checkout_class = '';
    }
    //
    $sql = "SELECT D.DOCID, D.TITLE, R.DTIME, C.POSITION, B.LNAME, B.LLOCATION, R.RESUMBER
            FROM DOCUMENT D, RESERVES R, COPY C, BRANCH B
            WHERE D.DOCID = C.DOCID AND C.DOCID = R.DOCID AND R.LIBID = C.LIBID AND C.LIBID = B.LIBID 
              AND R.READERID = '$readerId' AND R.COPYNO = C.COPYNO";

    $result = $conn->query($sql);
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            echo "<div class='card card-block search-list-item'>
                    <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE'].
                "</h4><p class='card-text text-muted'>reserved at ".$row['DTIME']."</p>
                <p class='card-text'>Position: ".$row['LNAME']." (".$row['LLOCATION'].") &bull; ".$row['POSITION']."</p>
                <a class='btn btn-success btn-sm $checkout_class' href='checkout_reserve.php?resid=".$row['RESUMBER']."'>Check Out</a>
                <a class='btn btn-danger btn-sm' href='cancel_reserve.php?resid=".$row['RESUMBER']."'>Cancel</a>
                </div>";
        }
    }else{
        echo "<p>0 reserved</p>";
    }
    $conn->close();
    ?>
    <!-- End Reserves List -->
</div>
<!-- // End Content -->
<?php
include('layout/footer.php');
?>