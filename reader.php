<?php
include('session.php');
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
    <div style="margin-top: 15rem;">
        <form action="reader_search.php" method="get">
        <div class="input-group">
          <div class="input-group-btn">
            <button id="byDropdown" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Title
            </button>
            <div class="dropdown-menu">
              <button class="dropdown-item active" type="button" >Title</button>
              <button class="dropdown-item" type="button" >ID</button>
              <button class="dropdown-item" type="button" >Publisher</button>
            </div>
          </div>

          <input name="search" type="text" class="form-control" aria-label="Search Document" placeholder="Search Document" onkeydown='if(event.keyCode==13){gosubmit();}'>

          <input name="by" id="searchBy" value="Title" hidden></input>
        </div>
        </form>
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