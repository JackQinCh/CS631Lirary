<?php
include ('session.php');
// Get Request Data
$search = $_GET['search'];
$searchBy = $_GET['by'];
$by = '';
switch ($searchBy) {
    case 'Title': $by = 'TITLE';break;
    case 'ID': $by = 'DOCID';break;
    case 'Publisher': $by = 'PUBNAME';break;
    default: $by = 'TITLE';break;
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
    <ol class="breadcrumb m-t-1">
        <li class="active">Search</li>
    </ol>
<!-- Search Bar -->
    <div class="m-t-1 m-b-1">
        <form action="" method="get">
        <div class="input-group">
          <div class="input-group-btn">
            <button id="byDropdown" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php echo $searchBy?>
            </button>
            <div class="dropdown-menu">
              <button class="dropdown-item active" type="button" >Title</button>
              <button class="dropdown-item" type="button" >ID</button>
              <button class="dropdown-item" type="button" >Publisher</button>
            </div>
          </div>
            
          <input name="search" type="text" class="form-control" aria-label="Search Document" placeholder="Search Document" value="<?php echo $search?>" onkeydown='if(event.keyCode==13){gosubmit();}'>

          <input name="by" value="<?php echo $searchBy?>" hidden></input>
        </div>
        </form>
    </div>
<!-- End Search Bar -->

<!-- Search List -->
<?php

$sql = '';
if ($search != ""){
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //Query
    if ($by == 'DOCID') {
        $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME 
                FROM DOCUMENT D, PUBLISHER P
                WHERE D.PUBLISHERID = P.PUBLISHERID AND $by = '$search'";
    }else{
        $sql = "SELECT D.DOCID, D.TITLE, D.PDATE, P.PUBNAME  
                FROM DOCUMENT D, PUBLISHER P 
                WHERE D.PUBLISHERID = P.PUBLISHERID AND $by LIKE '%$search%'";
    }
    $result = $conn->query($sql);
    $conn->close();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $label = '';
            if (strpos($row['DOCID'], 'B') !== false) {
                $label = "<span class='label label-success searchlist-label'>book</span>";
            }elseif (strpos($row['DOCID'], 'J') !== false) {
                $label = "<span class='label label-info searchlist-label'>journal</span>";
            }elseif (strpos($row['DOCID'], 'P') !== false) {
                $label = "<span class='label label-warning searchlist-label'>proceeding</span>";
            }
            echo "<div class='card card-block search-list-item' onclick=window.location.href='reader_search_detail.php?docID=".$row['DOCID']."&search=".$search."&by=".$searchBy."'>
            <h4 class='card-title'>".$row['TITLE'].$label.
            "</h4><p class='card-text text-muted'>".$row['PDATE']." Published by <u><em>".$row['PUBNAME']."</em></u></p></div>";
        } 
    }else{
        echo "<div class='alert alert-danger' role='alert'>
        <strong>Oops!</strong> No document found. Try another keyword!</div>";
    }
}
?>
<!-- End Search List -->
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

$('.search-list-item').hover(
    function () {
        $(this).addClass('card-inverse');
        $(this).addClass('card-primary');
    },
    function () {
        $(this).removeClass('card-inverse');
        $(this).removeClass('card-primary');
    }
    );

</script>
</body>
</html>