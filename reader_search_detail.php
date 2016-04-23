<?php
// Get Request Data
$docID = $_GET['docID'];
$search = $_GET['search'];
$searchBy = $_GET['by'];

function bookDetail($id='')
{
	// Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //Query Number of Remained Copies
    $sql = "SELECT COUNT(*)
    		FROM COPY
    		WHERE DOCID = '$id' AND
    			COPYNO NOT IN(
    				SELECT COPYNO
    				FROM RESERVES
    				WHERE DOCID = '$id'
    			) AND COPYNO NOT IN(
    				SELECT COPYNO
    				FROM BORROWS
    				WHERE DOCID = '$id' AND RDTIME IS NULL
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

    //Query Authors
    $authors = '';
    $sql = "SELECT A.ANAME
    		FROM AUTHOR A, WRITES W
    		WHERE W.DOCID = '$id' AND W.AUTHORID = A.AUTHORID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    	while ($row = $result->fetch_assoc()){
    		$authors .= $row['ANAME']." / ";
    	}
    }
    //Query Book Detail
    $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME, P.ADDRESS, B.ISBN 
    		FROM DOCUMENT D, PUBLISHER P, BOOK B
    		WHERE D.DOCID = B.DOCID AND D.PUBLISHERID = P.PUBLISHERID AND D.DOCID = '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
    	$row = $result->fetch_assoc();
    	echo 	"<div class='card'>
    				<div class='card-block'>
    					<h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE'].$copy_label."</h4>
    					<dl class='card-block dl-horizontal'>
    					<dt class='card-text col-sm-3'>Publish Year</dt>
    					<dd class='card-text col-sm-9'>".$row['PDATE']."</dd>
						<dt class='card-text col-sm-3'>Publisher</dt>
    					<dd class='card-text col-sm-9'>".$row['PUBNAME']."</dd>
    					<dt class='card-text col-sm-3'>Publisher Location</dt>
    					<dd class='card-text col-sm-9'>".$row['ADDRESS']."</dd>
    					<dt class='card-text col-sm-3'>Authors</dt>
    					<dd class='card-text col-sm-9'>".$authors."</dd>
    					<dt class='card-text col-sm-3'>ISBN</dt>
    					<dd class='card-text col-sm-9'>".$row['ISBN']."</dd>
    					</dl>

    					<form class='form-inline' method='get' action='reserve.php'>
							<fieldset ".$submit_active.">
							<div class='form-group'>
							    <label for='num-copy'>Qty:</label>
								<select name='num-copy' class='form-control' id='copy-list' style='max-width:40px;'>
								".$select_count."
								</select>
							</div>
								<input name='docid' value='$id' hidden/>
								<button type='submit' class='btn btn-primary btn-sm'>Reserve</button>
							</fieldset>
						</form>
    				</div>
    			</div>";
    }
    $conn->close();
}


function journalDetail($id='')
{
	// Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //Query Number of Remained Copies
    $sql = "SELECT COUNT(*)
    		FROM COPY
    		WHERE DOCID = '$id' AND
    			COPYNO NOT IN(
    				SELECT COPYNO
    				FROM RESERVES
    				WHERE DOCID = '$id'
    			) AND COPYNO NOT IN(
    				SELECT COPYNO
    				FROM BORROWS
    				WHERE DOCID = '$id' AND RDTIME IS NULL
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
    //Query Journal Detail
    $issues = "<h5>ISSUES:</h5><ol>";
    $sql = "SELECT ISSUE_NO, SCOPE
    		FROM JOURNAL_ISSUE
    		WHERE DOCID = '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    	while($issue = $result->fetch_assoc()){
    		$issues .= "<li><strong>".$issue['SCOPE']."</strong> by ";
	    	$sql1 = "SELECT IENAME
	    			 FROM INV_EDITOR
	    			 WHERE DOCID = '$id' AND ISSUE_NO = ".$issue['ISSUE_NO'];
	    	$authors = '';
	    	$resultAu = $conn->query($sql1);
	    	if ($resultAu->num_rows > 0) {
	    		while ( $author = $resultAu->fetch_assoc()) {
	    			$authors .= $author['IENAME']." / ";
	    		};
	    	}
	    	$issues .= $authors."</li>";
    	}
    	$issues .= "</ol>";	
    }

    $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME, P.ADDRESS, J.JVOLUME, C.ENAME
    		FROM DOCUMENT D, PUBLISHER P, JOURNAL_VOLUME J, CHIEF_EDITOR C
    		WHERE D.DOCID = J.DOCID AND D.PUBLISHERID = P.PUBLISHERID AND D.DOCID = '$id'
    		AND C.EDITOR_ID = J.EDITOR_ID";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
    	$row = $result->fetch_assoc();
    	echo 	"<div class='card'>
    				<div class='card-block'>
    					<h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE'].$copy_label."</h4>
    					<dl class='card-block dl-horizontal'>
    					<dt class='card-text col-sm-3'>Publish Year</dt>
    					<dd class='card-text col-sm-9'>".$row['PDATE']."</dd>
						<dt class='card-text col-sm-3'>Publisher</dt>
    					<dd class='card-text col-sm-9'>".$row['PUBNAME']."</dd>
    					<dt class='card-text col-sm-3'>Publisher Location</dt>
    					<dd class='card-text col-sm-9'>".$row['ADDRESS']."</dd>
    					<dt class='card-text col-sm-3'>Chief Editor</dt>
    					<dd class='card-text col-sm-9'>".$row['ENAME']."</dd>
    					</dl>
    					".$issues."
    					<form class='form-inline' method='get' action='reserve.php'>
							<fieldset ".$submit_active.">
							<div class='form-group'>
							    <label for='num-copy'>Qty:</label>
								<select name='num-copy' class='form-control' id='copy-list' style='max-width:40px;'>
								".$select_count."
								</select>
							</div>
								<input name='docid' value='$id' hidden/>
								<button type='submit' class='btn btn-primary btn-sm'>Reserve</button>
							</fieldset>
						</form>
    				</div>
    			</div>";
    }
    $conn->close();
}

function proceedingDetail($id='')
{
	// Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //Query Number of Remained Copies
    $sql = "SELECT COUNT(*)
    		FROM COPY
    		WHERE DOCID = '$id' AND
    			COPYNO NOT IN(
    				SELECT COPYNO
    				FROM RESERVES
    				WHERE DOCID = '$id'
    			) AND COPYNO NOT IN(
    				SELECT COPYNO
    				FROM BORROWS
    				WHERE DOCID = '$id' AND RDTIME IS NULL
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
    //Query Proceeding Detail
    $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME, P.ADDRESS, PR.CDATE, PR.CLOCATION, PR.CEDITOR
    		FROM DOCUMENT D, PUBLISHER P, PROCEEDINGS PR
    		WHERE D.DOCID = PR.DOCID AND D.PUBLISHERID = P.PUBLISHERID AND D.DOCID = '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
    	$row = $result->fetch_assoc();
    	echo 	"<div class='card'>
    				<div class='card-block'>
    					<h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE'].$copy_label."</h4>
						<dl class='card-block dl-horizontal'>
    					<dt class='card-text col-sm-3'>Publish Year</dt>
    					<dd class='card-text col-sm-9'>".$row['PDATE']."</dd>
						<dt class='card-text col-sm-3'>Publisher</dt>
    					<dd class='card-text col-sm-9'>".$row['PUBNAME']."</dd>
    					<dt class='card-text col-sm-3'>Publisher Location</dt>
    					<dd class='card-text col-sm-9'>".$row['ADDRESS']."</dd>
    					<dt class='card-text col-sm-3'>Conference Date</dt>
    					<dd class='card-text col-sm-9'>".$row['CDATE']."</dd>
    					<dt class='card-text col-sm-3'>Conference Location</dt>
    					<dd class='card-text col-sm-9'>".$row['CLOCATION']."</dd>
    					<dt class='card-text col-sm-3'>Conference Editor</dt>
    					<dd class='card-text col-sm-9'>".$row['CEDITOR']."</dd>
    					</dl>
    					<form class='form-inline' method='get' action='reserve.php'>
							<fieldset ".$submit_active.">
							<div class='form-group'>
							    <label for='num-copy'>Qty:</label>
								<select name='num-copy' class='form-control' id='copy-list' style='max-width:40px;'>
								".$select_count."
								</select>
							</div>
								<input name='docid' value='$id' hidden/>
								<button type='submit' class='btn btn-primary btn-sm'>Reserve</button>
							</fieldset>
						</form>
    				</div>
    			</div>";
    }
    $conn->close();
}

$page = 'Search';
include('layout/header.php');
include('layout/reader_sidebar.php');

?>

<!-- // Content -->
<div class="container layout-content">
<!-- // Breadcrumb -->
	<ol class="breadcrumb m-t-1">
		<li><a href="<?php echo "reader_search.php?search=".$search."&by=".$searchBy ?>">Search</a></li>
		<li class="active">Detail</li>
	</ol>
<!-- // End Breadcrumb -->
    <div class="m-t-1">
<?php 
    if (strpos($docID, 'B') !== false) {
        echo bookDetail($docID);
    }elseif (strpos($docID, 'J') !== false) {
        echo journalDetail($docID);
    }elseif (strpos($docID, 'P') !== false) {
        echo proceedingDetail($docID);
    }
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