<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/23/16
 * Time: 16:07
 */
// Get Request Data
$readID = $_GET['readID'];
$search = $_GET['search'];

$page = 'Reservations';
include('layout/reader_header.php');
include('layout/reader_sidebar.php');

// Create connection
$conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//Query
$sql = "SELECT RNAME
        FROM READER
        WHERE READERID = '$readID'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$readName = $row['RNAME'];
$conn->close();
//
?>

<!-- // Content -->
<div class="container layout-content">
    <!-- // Breadcrumb -->
    <ol class="breadcrumb m-t-1">
        <li><a href="reader_search_reader.php?search=<?php echo $search?>">Search</a></li>
        <li class="active">Detail</li>
    </ol>
    <!-- // End Breadcrumb -->

    <div class="m-t-1" id="div_print">
        <h4 class="text-muted"><?php echo $readName ?>'s Reservations
            <button onclick="printdiv('div_print')" class="btn btn-primary btn-sm pull-md-right">
                <i class="material-icons">print</i>
            </button>
        </h4>
        <?php
        // Create connection
        $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //Query
        $sql = "SELECT D.DOCID, D.TITLE, COUNT(COPYNO), R.DTIME
                FROM RESERVES R, DOCUMENT D
                WHERE R.READERID = '$readID' AND R.DOCID = D.DOCID
                GROUP BY R.DOCID";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            while ($row = $result->fetch_assoc()){
                echo "<div class='card card-block search-list-item'>
                        <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE']."
                        <span class='label label-default searchlist-label'>".$row['COUNT(COPYNO)']." copies</span></h4>
                        <p class='card-text text-muted'>at ".$row['DTIME']."</p>
                      </div>";
            }
        }else{
            echo "<p>0 reservation</p>";
        }
        $conn->close();
        ?>
    </div>
</div>
<!-- // End Content -->

<script language="javascript">
    function printdiv(printpage){
        var headstr="<html><head><title></title></head><body>";
        var footstr="</body>";
        var newstr=document.all.item(printpage).innerHTML;
        var oldstr=document.body.innerHTML;
        document.body.innerHTML=headstr+newstr+footstr;
        window.print();
        document.body.innerHTML=oldstr;
        return false;
    }
</script>


<?php include('layout/footer.php'); ?>

