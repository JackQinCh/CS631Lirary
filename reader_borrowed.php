<?php
$page = 'Borrowed';
include('layout/reader_header.php');
include('layout/reader_sidebar.php');

?>

<!-- // Content -->
<div class="container layout-content">

    <!-- Borrowed List -->
    <h4 class="text-muted">Borrowed List</h4>
    <?php
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //
    $sql = "SELECT D.DOCID, D.TITLE, B.BDTIME, B.BORNUMBER, COMPUTE_REMAIN_DAYS(B.BORNUMBER) AS DAYS, COMPUTE_FINE(B.BORNUMBER) AS FINE
            FROM DOCUMENT D, BORROWS B
            WHERE D.DOCID = B.DOCID AND B.READERID = 'jz01' AND B.RDTIME IS NULL;";

    $result = $conn->query($sql);
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){

            if ($row['DAYS'] == 0){
                $fineLabel = "<span class='label label-danger searchlist-label'>fine $".$row['FINE']."</span>";
            }else{
                $fineLabel = "<span class='label label-success searchlist-label'>".$row['DAYS']." days remained</span>";
            }

            echo "<div class='card card-block search-list-item'>
                    <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE'].$fineLabel.
                "</h4><p class='card-text text-muted'>borrowed at ".$row['BDTIME']."</p>
                <a class='btn btn-success btn-sm' href='return.php?borid=".$row['BORNUMBER']."'>Return</a>
                </div>";
        }
    }else{
        echo "<p>0 borrowed</p>";
    }
    $conn->close();

    ?>
    <hr>
    <h4 class="text-muted">History</h4>
    <?php
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //
    $sql = "SELECT D.DOCID, D.TITLE, B.BDTIME, B.BORNUMBER, B.RDTIME, COMPUTE_FINE(B.BORNUMBER) AS FINE
            FROM DOCUMENT D, BORROWS B
            WHERE D.DOCID = B.DOCID AND B.READERID = 'jz01' AND B.RDTIME IS NOT NULL;";

    $result = $conn->query($sql);
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){

            echo "<div class='card card-block search-list-item'>
                    <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE']. "
                    <span class='label label-default searchlist-label'>fine $".$row['FINE']."</span></h4>
                    <p class='card-text text-muted'>borrowed at ".$row['BDTIME']." &bull; returned at ".$row['RDTIME']."</p>
                </div>";
        }
    }else{
        echo "<p>0 history</p>";
    }
    $conn->close();

    ?>
    <!-- End Reserves List -->
</div>
<!-- // End Content -->
<?php
include('layout/footer.php');
?>