<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/26/16
 * Time: 08:19
 */

$year = '';
if (isset($_GET['year']))
    $year = $_GET['year'];

$page = 'PopBooks';
include('layout/admin_header.php');
include('layout/admin_sidebar.php');

?>

<!-- // Content -->
<div class="container layout-content">
    <!--    // Add Bar -->
    <div class="card m-t-2 m-b-2">
        <div class="card-block">
            <form class="form-inline" action="admin_pop_books.php" method="get">
                <div class="form-group">
                    <label class="form-control-label text-muted" for="year">Year</label>
                    <select name="year" class="c-select" style="min-width: 200px;max-width: 200px" >
                        <?php
                        $currentYear = date("Y");
                        for ($i=0;$i<=10;$i++){
                            $rowYear = $currentYear - $i;
                            echo "<option value='$rowYear'>$rowYear</option>";
                        }
                        ?>
                    </select>
                </div>
                <input class="form-control btn btn-warning-outline" type="submit" value="Search">
            </form>
        </div>
    </div>
    <!--    // End Add Bar-->
    <?php
    if ($year == '')
        return;
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //Query
    echo "<div class='card m-t-2' id='div_print'>
    <div class='card-header'>
        <h4>$year Top 10 Pop Books
            <button onclick='printdiv(\"div_print\");' class='btn btn-primary btn-sm pull-md-right'>
                <i class='material-icons'>print</i>
                Print</button>
        </h4>
    </div>";
    //
    $sql = "SELECT COUNT(B.BORNUMBER), D.TITLE, D.DOCID
            FROM BORROWS B, DOCUMENT D
            WHERE YEAR(B.BDTIME) = $year AND B.DOCID = D.DOCID AND D.DOCID IN (
              SELECT DOCID
              FROM BOOK
            )
            GROUP BY B.DOCID ORDER BY COUNT(B.BORNUMBER) DESC
            LIMIT 10";
    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        echo "<table class='table'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Doc ID</th>
                        <th>Title</th>
                        <th>Number of Borrowed</th>
                    </tr>
                </thead>
                <tbody>";
                    $i = 1;
                    while ($row = $result->fetch_assoc()){
                        echo "<tr>
                      <td>$i</td>
                      <td>".$row['DOCID']."</td>
                      <td>".$row['TITLE']."</td>
                      <td>".$row['COUNT(B.BORNUMBER)']."</td>
                  <tr>";
            $i ++;
        }
        echo "</tbody></table>";
    }else{
        echo    "<div class='card-footer alert-danger' role='alert'>
                    <strong>Oops!</strong> Nothing!
                </div>";
    }
    echo "</div>";
    $conn->close();
    ?>
</div>
    <!-- // End Content -->

<?php include('layout/footer.php'); ?>