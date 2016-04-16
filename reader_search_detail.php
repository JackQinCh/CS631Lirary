<?php
include ('session.php');
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
    					<h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE']."</h4>
    					<p class='card-text'>Publish Year: ".$row['PDATE']."</p>
    					<p class='card-text'>Publisher: ".$row['PUBNAME']."</p>
    					<p class='card-text'>Publisher Location: ".$row['ADDRESS']."</p>
    					<p class='card-text'>Authors: ".$authors."</p>
    					<p class='card-text'>ISBN: ".$row['ISBN']."</p>
    					<a href='#'' class='btn btn-primary'>Reserve</a>
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
    //Query Issues and Authors
    
    //Query Journal Detail
    $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME, P.ADDRESS, J.JVOLUME, C.ENAME
    		FROM DOCUMENT D, PUBLISHER P, JOURNAL_VOLUME J, CHIEF_EDITOR C
    		WHERE D.DOCID = J.DOCID AND D.PUBLISHERID = P.PUBLISHERID AND D.DOCID = '$id'
    		AND C.EDITOR_ID = J.EDITOR_ID";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
    	$row = $result->fetch_assoc();
    	echo 	"<div class='card'>
    				<div class='card-block'>
    					<h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE']."</h4>
    					<p class='card-text'>Publish Year: ".$row['PDATE']."</p>
    					<p class='card-text'>Publisher: ".$row['PUBNAME']."</p>
    					<p class='card-text'>Publisher Location: ".$row['ADDRESS']."</p>
    					<p class='card-text'>Chief Editor: ".$row['ENAME']."</p>
    					
    					<a href='#'' class='btn btn-primary'>Reserve</a>
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
    //Query Proceeding Detail
    $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME, P.ADDRESS, PR.CDATE, PR.CLOCATION, PR.CEDITOR
    		FROM DOCUMENT D, PUBLISHER P, PROCEEDINGS PR
    		WHERE D.DOCID = PR.DOCID AND D.PUBLISHERID = P.PUBLISHERID AND D.DOCID = '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
    	$row = $result->fetch_assoc();
    	echo 	"<div class='card'>
    				<div class='card-block'>
    					<h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE']."</h4>
    					<p class='card-text'>Publish Year: ".$row['PDATE']."</p>
    					<p class='card-text'>Publisher: ".$row['PUBNAME']."</p>
    					<p class='card-text'>Publisher Location: ".$row['ADDRESS']."</p>
    					<p class='card-text'>Conference Date: ".$row['CDATE']."</p>
    					<p class='card-text'>Conference Location: ".$row['CLOCATION']."</p>
    					<p class='card-text'>Conference Editor: ".$row['CEDITOR']."</p>
    					
    					<a href='#'' class='btn btn-primary'>Reserve</a>
    				</div>
    			</div>";
    }
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <!-- My Style -->
    <link rel="stylesheet" type="text/css" href="css/site.css">
</head>
<body>
<!-- // Navbar -->
<nav class="navbar navbar-dark navbar-full bg-primary navbar-fixed-top">
    <a class="navbar-brand" href="index.php"><i class="material-icons md-48">school</i> Library</a>
    <div class="nav navbar-nav pull-md-right">
        <a class="nav-item nav-link active">Welcome <?php echo $readerName?></a>
    </div>
</nav>
<!-- // End Navbar -->

<!-- // Sidebar-->
<div class="sidebar">
    <div class="sidebar-heading">Roles</div>
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item active">
            <a class="sidebar-menu-button" href="reader_index.php">
            <i class="sidebar-menu-icon material-icons">face</i>
            Reader
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="admin_index.php">
            <i class="sidebar-menu-icon material-icons">perm_identity</i>
            Administrator
            </a>
        </li>
    </ul>

    <div class="sidebar-heading">Reader Menu</div>
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item active">
            <a href="reader.php" class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">search</i>
            Search</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="" class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">shopping_cart</i>
            Checkout</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="" class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">assignment_return</i>
            Return</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="" class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">lock</i>
            Reserve</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="" class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">check_circle</i>
            Borrow</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="" class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">monetization_on</i>
            Fine
            <span class="sidebar-menu-label label label-default">$20</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="" class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">print</i>
            Print Reverves
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="" class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">description</i>
            Print Publisher Docs
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="reader_logout.php" class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">power_settings_new</i>
            Quit
            </a>
        </li>
    </ul>
</div>
<!-- //End Sidebar-->

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

<footer class="nav navbar footer navbar-fixed-bottom">
    <div class="container text-md-center">
        <span class="text-muted">&copy Zhonghua 2016</span>
    </div>
</footer>
<!-- jQuery first, then Bootstrap JS. -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
<!-- My JS -->
<script type="text/javascript" src="js/site.js"></script>

<script>
function gosubmit() {
    $('form').submit();
}

$('button.dropdown-item').click(function () {
    $('#byDropdown').html($(this).html());
    $('input[name="by"]').val($(this).html());
});

</script>


</body>
</html>