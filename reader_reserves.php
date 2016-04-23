<?php
include ('session.php');

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
        <li class="sidebar-menu-item">
            <a href="reader.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">search</i>
                Search</a>
        </li>
        <li class="sidebar-menu-item active">
            <a href="reader_reserves.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">lock</i>
                Reserved</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="reader_borrowed.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">check_circle</i>
                Borrowed</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="reader_findreader.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">print</i>
                Print Reservations
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
    <h4 class="text-muted">Reserved List</h4>
    <!-- Reserves List -->
    <?php
    // Create connection
    $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //
    $sql = "SELECT D.DOCID, D.TITLE, R.DTIME, C.POSITION, B.LNAME, B.LLOCATION, R.RESUMBER
            FROM DOCUMENT D, RESERVES R, COPY C, BRANCH B
            WHERE D.DOCID = C.DOCID AND C.DOCID = R.DOCID AND R.LIBID = C.LIBID AND C.LIBID = B.LIBID 
              AND R.READERID = '$readerId' AND R.COPYNO = C.COPYNO";

    $result = $conn->query($sql);
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            echo "<div class='card card-block search-list-item'>
                    <h4 class='card-title'>".$row['DOCID'].": ".$row['TITLE'].
                "</h4><p class='card-text text-muted'>reserved at ".$row['DTIME']."</p>
                <p class='card-text'>Position: ".$row['LNAME']." (".$row['LLOCATION'].") &bull; ".$row['POSITION']."</p>
                <a class='btn btn-success btn-sm' href='checkout_reserve.php?resid=".$row['RESUMBER']."'>Check Out</a>
                <a class='btn btn-danger btn-sm' href='cancel_reserve.php?resid=".$row['RESUMBER']."'>Cancel</a>
                </div>";
        }
    }else{
        echo "0 reserved";
    }
    $conn->close();
    ?>
    <!-- End Reserves List -->
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

</script>
</body>
</html>