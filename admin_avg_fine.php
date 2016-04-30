<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/26/16
 * Time: 16:34
 */

$page = 'AvgFine';
include('layout/admin_header.php');
include('layout/admin_sidebar.php');
?>
<!-- // Content -->
<div class="container layout-content">
    <div class="card m-t-2" id="div_print">
        <div class="card-header">
            <h4>Reader Average Fine
                <button onclick="printdiv('div_print')" class="btn btn-primary btn-sm pull-md-right">
                    <i class="material-icons">print</i>
                    Print</button>
            </h4>
        </div>
        <?php
        // Create connection
        $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //
        $sql = "SELECT READERID, RTYPE, RNAME, ROUND(AVG(FINE),2) AS AVGFINE
                FROM
                  (
                    SELECT R.READERID, R.RTYPE, R.RNAME, COMPUTE_FINE(B.BORNUMBER) AS FINE
                    FROM BORROWS B, READER R
                    WHERE B.READERID = R.READERID
                  ) AS FINE_TABLE
                GROUP BY READERID;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            echo "<table class='table'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Average Fine</th>
                    </tr>
                </thead>
                <tbody>";
            while ($row = $result->fetch_assoc()){
                echo "<tr>
                      <td>".$row['READERID']."</td>
                      <td>".$row['RNAME']."</td>
                      <td>".$row['RTYPE']."</td>
                      <td>$ ".$row['AVGFINE']."</td>
                  <tr>";
            }
            echo "</tbody></table>";
        }else{
            echo "<div class='card-footer alert-danger' role='alert'>
            <strong>Oops!</strong> Nothing!
        </div>";
        }
        $conn->close();
        ?>
    </div>

</div>
<!-- // End Content -->

<?php include('layout/footer.php'); ?>