<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/25/16
 * Time: 15:19
 */
// Get Request Data
$docID = '';
$copyNo = 0;
$libID = 0;
$position = '';
if (isset($_GET['docid']) and $_GET['docid'] != '')
    $docID = $_GET['docid'];
if (isset($_GET['copyid']) and $_GET['copyid'] != '')
    $copyNo = $_GET['copyid'];
if (isset($_GET['libid']) and $_GET['libid'] != '')
    $libID = $_GET['libid'];
if (isset($_GET['position']) and $_GET['position'] != '')
    $position = $_GET['position'];

$page = 'AddCopy';
include('layout/admin_header.php');
include('layout/admin_sidebar.php');
?>

<!-- // Content -->
<div class="container layout-content">
<!--    // Add Bar -->
    <div class="card m-t-2 m-b-2">
        <div class="card-block">
            <form class="form-inline" action="admin_add_copy.php" method="get">
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
                    <input name="docid" type="text" class="form-control c-input" placeholder="Document ID" style="max-width: 150px">
                </div>
                <div class="form-group">
                    <input name="copyid" type="text" class="form-control c-input" placeholder="Copy ID" style="max-width: 150px">
                </div>
                <div class="form-group">
                    <input name="position" type="text" class="form-control c-input" placeholder="Position" style="max-width: 150px">
                </div>
                <input class="form-control btn btn-warning-outline" type="submit" value="Add">
            </form>
        </div>
    </div>
<!--    // End Add Bar-->
<!--    // New Copy Information -->
    <?php
    if ($docID == '' || $position == '')
        return;
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //
    $sql = "INSERT INTO COPY (DOCID, COPYNO, LIBID, POSITION) VALUES 
            ('$docID', $copyNo, $libID, '$position')";

    if ($conn->query($sql) === TRUE) {
        $sql = "SELECT D.TITLE, B.LNAME, B.LLOCATION
                FROM DOCUMENT D, BRANCH B
                WHERE D.DOCID = '$docID' AND B.LIBID = $libID";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo    "<div class='alert alert-success' role='alert'>
                    <strong>Great!</strong> You have successfully add a copy!
                </div>
                <div class='card'>
                    <div class='card-header'>
                        <h4 class='card-title'>$docID: ".$row['TITLE']."<span class='text-muted small pull-md-right'>Copy ID: $copyNo</span></h4>
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
                                <td>$position</td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>
                <strong>Oops!</strong> Can not create this copy. Change information and try again.
            </div>";
    }

    $conn->close();
    ?>
<!--    // End Copy Information -->
</div>
<!-- // End Content -->

<?php
include('layout/footer.php');
?>