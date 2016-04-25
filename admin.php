<?php

// Get Request Data
$docID = '';
$copyNo = 0;
$libID = 0;
if (isset($_GET['docid']) and $_GET['docid'] != '')
    $docID = $_GET['docid'];
if (isset($_GET['copyid']) and $_GET['copyid'] != '')
    $copyNo = $_GET['copyid'];
if (isset($_GET['libid']) and $_GET['libid'] != '')
    $libID = $_GET['libid'];

$page = 'Search';
include('layout/admin_header.php');
include('layout/admin_sidebar.php');
?>

<!-- // Content -->
<div class="container layout-content">

<!--    // Search Bar -->
    <div class="card m-t-2 m-b-2">
        <div class="card-block">
            <form class="form-inline" action="admin.php" method="get">
                <div class="form-group">
                    <label class="form-control-label text-muted" for="libid">Branch</label>
                    <select name="libid" class="c-select" style="min-width: 200px;max-width: 200px" >
                        <?php
                        // Create connection
                        $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        //
                        $sql = "SELECT LIBID, LNAME
                                FROM BRANCH";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()){
                            echo "<option value='".$row['LIBID']."'>".$row['LNAME']."</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input name="docid" type="text" class="form-control c-input" placeholder="Document ID" style="max-width: 200px">
                </div>
                <div class="form-group">
                    <input name="copyid" type="text" class="form-control c-input" placeholder="Copy ID" style="max-width: 200px">
                </div>
                <div class="form-group">
                    <input class="form-control btn btn-warning-outline" type="submit" value="Search">
                </div>

            </form>
        </div>
    </div>
<!--    // End Search Bar -->
<!--    // Copy Detail -->
    <?php
    if ($docID == '')
        return;
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
                    <div class='card-header'>
                        <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE']."<span class='text-muted small pull-md-right'>Copy ID: ".$row['COPYNO']."</span></h4>
                    </div>
                    <table class='table'>
                        <thead>
                            <tr>
                              <th>Branch</th>
                              <th>Address</th>
                              <th>Position</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>".$row['LNAME']."</td>
                                <td>".$row['LLOCATION']."</td>
                                <td>".$row['POSITION']."</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class='card-footer'>
                        <p class='card-text text-muted text-xs-right'>".$status."</p>
                    </div>
                </div>";
    }else{
        echo "<div class='alert alert-danger' role='alert'>
                <strong>Oops!</strong> Change keywords and try again.
            </div>";
    }
    $conn->close();
    ?>
<!--    // End Copy Detail-->
</div>
<!-- // End Content -->

<?php
include('layout/footer.php');
?>