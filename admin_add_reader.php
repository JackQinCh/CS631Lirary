<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/25/16
 * Time: 18:58
 */

// Get Request Data
$readerID = '';
$readerType = '';
$readerName = '';
$readerAddress = '';
if (isset($_GET['id']) and $_GET['id'] != '')
    $readerID = $_GET['id'];
if (isset($_GET['type']) and $_GET['type'] != '')
    $readerType = $_GET['type'];
if (isset($_GET['name']) and $_GET['name'] != '')
    $readerName = $_GET['name'];
if (isset($_GET['address']) and $_GET['address'] != '')
    $readerAddress = $_GET['address'];

$page = 'AddReader';
include('layout/admin_header.php');
include('layout/admin_sidebar.php');
?>

<!-- // Content -->
<div class="container layout-content">
    <!--    // Add Bar -->
    <div class="card m-t-2 m-b-2">
        <div class="card-block">
            <form class="form-inline" action="admin_add_reader.php" method="get">
                <div class="form-group">
                    <label class="form-control-label text-muted" for="type">Type</label>
                    <select name="type" class="c-select" style="min-width: 200px;max-width: 200px" >
                        <option value="student">Student</option>
                        <option value="senior citizen">Senior Citizen</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <div class="form-group">
                    <input name="id" type="text" class="form-control c-input" placeholder="Card ID" style="max-width: 150px">
                </div>
                <div class="form-group">
                    <input name="name" type="text" class="form-control c-input" placeholder="Name" style="max-width: 150px">
                </div>
                <div class="form-group">
                    <input name="address" type="text" class="form-control c-input" placeholder="Address" style="max-width: 150px">
                </div>
                <input class="form-control btn btn-warning-outline" type="submit" value="Add">
            </form>
        </div>
    </div>
    <!--    // End Add Bar-->
    <!--    // New Copy Information -->
    <?php
    if ($readerID == '' || $readerName == '' || $readerAddress == '' || $readerType == '')
        return;
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //
    $sql = "INSERT INTO READER (READERID, RTYPE, RNAME, ADDRESS) VALUES 
        ('$readerID', '$readerName', '$readerName', '$readerAddress')";

    if ($conn->query($sql) === TRUE) {
        echo    "<div class='alert alert-success' role='alert'>
                <strong>Great!</strong> You have successfully add a reader!
            </div>
            <div class='card'>
                <table class='table'>
                    <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Type</th>
                          <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>$readerID</td>
                            <td>$readerName</td>
                            <td>$readerType</td>
                            <td>$readerAddress</td>
                        </tr>
                    </tbody>
                </table>
            </div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>
            <strong>Oops!</strong> You can't add this reader! Change information and try again.
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