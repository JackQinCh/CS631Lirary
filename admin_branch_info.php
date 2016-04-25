<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/25/16
 * Time: 19:18
 */
$page = 'PrtBranchInfo';
include('layout/admin_header.php');
include('layout/admin_sidebar.php');
?>


<!-- // Content -->
<div class="container layout-content">
    <div class="card m-t-2" id="div_print">
        <div class="card-header">
            <h4>Branches Information
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
        $sql = "SELECT *
            FROM BRANCH";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            echo "<table class='table'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = $result->fetch_assoc()){
                echo "<tr>
                          <td>".$row['LIBID']."</td>
                          <td>".$row['LNAME']."</td>
                          <td>".$row['LLOCATION']."</td>
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