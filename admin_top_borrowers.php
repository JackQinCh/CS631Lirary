<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/25/16
 * Time: 19:50
 */

$libid = 0;
if (isset($_GET['libid']))
    $libid = $_GET['libid'];

$page = 'TopBorrowers';
include('layout/admin_header.php');
include('layout/admin_sidebar.php');

?>


<!-- // Content -->
<div class="container layout-content">
    <!--    // Add Bar -->
    <div class="card m-t-2 m-b-2">
        <div class="card-block">
            <form class="form-inline" action="admin_top_borrowers.php" method="get">
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
                <input class="form-control btn btn-warning-outline" type="submit" value="Search">
            </form>
        </div>
    </div>
    <!--    // End Add Bar-->
    <?php
    if ($libid == 0)
        return;
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //
    $sql = "SELECT LNAME
            FROM BRANCH
            WHERE LIBID = $libid";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "<div class='card m-t-2' id='div_print'>
            <div class='card-header'>
                <h4>".$row['LNAME']." Top 10 Borrowers
                    <button onclick='printdiv(\"div_print\");' class='btn btn-primary btn-sm pull-md-right'>
                        <i class='material-icons'>print</i>
                        Print</button>
                </h4>
            </div>";
    //
    $sql = "SELECT COUNT(B.BORNUMBER), R.READERID, R.RNAME
            FROM BORROWS B, READER R
            WHERE B.LIBID = $libid AND B.READERID = R.READERID
            GROUP BY B.READERID ORDER BY COUNT(B.BORNUMBER) DESC
            LIMIT 10";
    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        echo "<table class='table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reader ID</th>
                    <th>Reader Name</th>
                    <th>Number of Borrows</th>
                </tr>
            </thead>
            <tbody>";
        $i = 1;
        while ($row = $result->fetch_assoc()){
            echo "<tr>
                  <td>$i</td>
                  <td>".$row['READERID']."</td>
                  <td>".$row['RNAME']."</td>
                  <td>".$row['COUNT(B.BORNUMBER)']."</td>
              <tr>";
            $i ++;
        }
        echo "</tbody></table>";
    }else{
        echo "<div class='card-footer alert-danger' role='alert'>
        <strong>Oops!</strong> Nothing!
    </div>";
    }
    echo "</div>";
    $conn->close();
    ?>
</div>
<!-- // End Content -->

<?php include('layout/footer.php'); ?>