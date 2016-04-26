<?php
// Get Request Data
$docID = $_GET['docID'];
$search = $_GET['search'];
$searchBy = $_GET['by'];

$page = 'Search';
include('layout/reader_header.php');
include('layout/reader_sidebar.php');

?>

<!-- // Content -->
<div class="container layout-content">
<!-- // Breadcrumb -->
	<ol class="breadcrumb m-t-1">
		<li><a href="<?php echo "reader.php?search=".$search."&by=".$searchBy ?>">Search</a></li>
		<li class="active">Detail</li>
	</ol>
<!-- // End Breadcrumb -->
    <div class="m-t-1">
    <?php
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //Query Number of Remained Copies
    $sql = "SELECT COUNT(*)
    		FROM COPY C
    		WHERE DOCID = '$docID' AND
    		    NOT EXISTS (
    		      SELECT *
    		      FROM RESERVES R
    		      WHERE R.DOCID = C.DOCID AND R.COPYNO = C.COPYNO AND R.LIBID = C.LIBID
    		    )
    		    AND NOT EXISTS (
    		      SELECT * 
    		      FROM BORROWS B
    		      WHERE B.DOCID = C.DOCID AND B.COPYNO = C.COPYNO AND B.LIBID = C.LIBID AND B.RDTIME IS NULL
    		    )";
    $result = $conn->query($sql);
    $num_copy = 0;
    $copy_label = "<span class='label label-default searchlist-label'>0 copies</span>";
    if ($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $num_copy = $row['COUNT(*)'];
        $copy_label = "<span class='label label-default searchlist-label'>$num_copy copies</span>";
    }
    $select_count = '';
    $submit_active = '';
    if ($num_copy == 0) {
        $submit_active = 'disabled';
        $select_count = '<option>0</option>';
    }
    for ($i=1; $i <= $num_copy; $i++) {
        $select_count .= "<option value=$i>$i</option>";
    }
//    Book Detail
    if (strpos($docID, 'B') !== false) {
        //Query Authors
        $authors = '';
        $sql = "SELECT A.ANAME
    		FROM AUTHOR A, WRITES W
    		WHERE W.DOCID = '$docID' AND W.AUTHORID = A.AUTHORID";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()){
                $authors .= $row['ANAME']." / ";
            }
        }
        //Query Book Detail
        $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME, P.ADDRESS, B.ISBN 
    		FROM DOCUMENT D, PUBLISHER P, BOOK B
    		WHERE D.DOCID = B.DOCID AND D.PUBLISHERID = P.PUBLISHERID AND D.DOCID = '$docID'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            echo 	"<div class='card'>
                        <div class='card-header'>
                            <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE'].$copy_label."</h4>
                        </div>
                     <table class='table'>
                        <tr>
                            <th>Publish Year</th>
                            <td>".$row['PDATE']."</td>
                        </tr>
                        <tr>
                            <th>Publisher</th>
                            <td>".$row['PUBNAME']."</td>
                        </tr>
                        <tr>
                            <th>Publisher Location</th>
                            <td>".$row['ADDRESS']."</td>
                        </tr>
                        <tr>
                            <th>Authors</th>
                            <td>".$authors."</td>
                        </tr>
                        <tr>
                            <th>ISBN</th>
                            <td>".$row['ISBN']."</td>
                        </tr>
                     </table>
                    <div class='card-footer text-md-center'>
                        <form class='form-inline' method='get' action='reserve.php'>
							<fieldset ".$submit_active.">
							<input name='docid' value='$docID' hidden/>
                            <button type='submit' class='btn btn-primary '>Reserve</button>
							<div class='form-group'>
								<select name='num-copy' class='c-select' id='copy-list' style='max-width:200px;'>
								".$select_count."
								</select>
							</div>
							</fieldset>
						</form>
                    </div>
    			</div>";
        }
//        Journal Detail
    }elseif (strpos($docID, 'J') !== false) {
        //Query Journal Detail
        $issues = "<table class='table'><thead><tr><th>#</th><th>Issue Scope</th><th>Authors</th></tr></thead>";
        $sql = "SELECT ISSUE_NO, SCOPE
    		FROM JOURNAL_ISSUE
    		WHERE DOCID = '$docID'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $i = 1;
            while($issue = $result->fetch_assoc()){
                $issues .= "<tr><td>$i</td><td>".$issue['SCOPE']."</td>";
                $sql1 = "SELECT IENAME
	    			 FROM INV_EDITOR
	    			 WHERE DOCID = '$docID' AND ISSUE_NO = ".$issue['ISSUE_NO'];
                $authors = '';
                $resultAu = $conn->query($sql1);
                if ($resultAu->num_rows > 0) {
                    while ( $author = $resultAu->fetch_assoc()) {
                        $authors .= $author['IENAME']." / ";
                    };
                }
                $issues .= "<td>".$authors."</td>";
                $i ++;
            }
            $issues .= "</table>";
        }

        $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME, P.ADDRESS, J.JVOLUME, C.ENAME
    		FROM DOCUMENT D, PUBLISHER P, JOURNAL_VOLUME J, CHIEF_EDITOR C
    		WHERE D.DOCID = J.DOCID AND D.PUBLISHERID = P.PUBLISHERID AND D.DOCID = '$docID'
    		AND C.EDITOR_ID = J.EDITOR_ID";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            echo 	"<div class='card'>
                        
                        <div class='card-header'>
                            <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE'].$copy_label."</h4>
                        </div>
                        <table class='table'>
                            <tr>
                                <th>Publish Year</th>
                                <td>".$row['PDATE']."</td>
                            </tr>
                            <tr>
                                <th>Publisher</th>
                                <td>".$row['PUBNAME']."</td>
                            </tr>
                            <tr>
                                <th>Publisher Location</th>
                                <td>".$row['ADDRESS']."</td>
                            </tr>
                            <tr>
                                <th>Chief Editor</th>
                                <td>".$row['ENAME']."</td>
                            </tr>
                         </table>
                         <hr style='border-width: 10px;'>
                        ".$issues."
                        <div class='card-footer text-md-center'>
                            <form class='form-inline' method='get' action='reserve.php'>
                                <fieldset ".$submit_active.">
                                <input name='docid' value='$docID' hidden/>
                                <button type='submit' class='btn btn-primary '>Reserve</button>
                                <div class='form-group'>
                                    <select name='num-copy' class='c-select' id='copy-list' style='max-width:200px;'>
                                    ".$select_count."
                                    </select>
                                </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>";
        }
//        Proceeding
    }elseif (strpos($docID, 'P') !== false) {
        //Query Proceeding Detail
        $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME, P.ADDRESS, PR.CDATE, PR.CLOCATION, PR.CEDITOR
    		FROM DOCUMENT D, PUBLISHER P, PROCEEDINGS PR
    		WHERE D.DOCID = PR.DOCID AND D.PUBLISHERID = P.PUBLISHERID AND D.DOCID = '$docID'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            echo "<div class='card'>
                    <div class='card-header'>
                        <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE'].$copy_label."</h4>
                    </div>
                    <table class='table'>
                        <tr>
                            <th>Publish Year</th>
                            <td>".$row['PDATE']."</td>
                        </tr>
                        <tr>
                            <th>Publisher</th>
                            <td>".$row['PUBNAME']."</td>
                        </tr>
                        <tr>
                            <th>Publisher Location</th>
                            <td>".$row['ADDRESS']."</td>
                        </tr>
                        <tr>
                            <th>Conference Date</th>
                            <td>".$row['CDATE']."</td>
                        </tr>
                        <tr>
                            <th>Conference Location</th>
                            <td>".$row['CLOCATION']."</td>
                        </tr>
                        <tr>
                            <th>Conference Editor</th>
                            <td>".$row['CEDITOR']."</td>
                        </tr>

                     </table>
                     <div class='card-footer text-md-center'>
                        <form class='form-inline' method='get' action='reserve.php'>
							<fieldset ".$submit_active.">
							<input name='docid' value='$docID' hidden/>
                            <button type='submit' class='btn btn-primary '>Reserve</button>
							<div class='form-group'>
								<select name='num-copy' class='c-select' id='copy-list' style='max-width:200px;'>
								".$select_count."
								</select>
							</div>
							</fieldset>
						</form>
                    </div>
    			</div>";
        }
    }
    $conn->close();
    ?>
    </div>
</div>
<!-- // End Content -->

<script>
function gosubmit() {
    $('form').submit();
}

$('button.dropdown-item').click(function () {
    $('#byDropdown').html($(this).html());
    $('input[name="by"]').val($(this).html());
});

</script>

<?php
include('layout/footer.php');
?>