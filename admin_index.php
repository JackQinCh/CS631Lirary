<?php
include("api/config.php");
session_start();
$error = "";
// Get Request Data
if (isset($_GET['username']) and isset($_GET['password'])){
    $username = $_GET["username"];
    $password = $_GET["password"];
    if ($username != '' || $password != '' ){
        if ($username == 'root' && $password == '0000'){
            $_SESSION['admin'] = "admin";
            header("location: admin.php");
        }else{
            $error = "Username or password is wrong! Please try again.";
        }
    }
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
    <!-- My Style -->
    <link rel="stylesheet" type="text/css" href="css/site.css">
</head>
<body>
<!-- // Navbar -->
<nav class="navbar navbar-dark navbar-full bg-primary navbar-fixed-top">
    <a class="navbar-brand" href="index.php"><i class="material-icons md-48">school</i> Library</a>
</nav>
<!-- // End Navbar -->

<!-- // Content -->
<div class="container p-t-2">
    <div class="card center-block m-t-3 text-md-center" style="max-width: 30rem;">
        <div class="card-header bg-success">
            <h1 class="card-title">Admin Login</h1>
        </div>
        <div class="card-block">
            <form action="" method="get">
                <div class="form-group">
                    <input name="username" type="text" class="form-control" placeholder="Username"/>
                </div>
                <div class="form-group">
                    <input name="password" type="password" class="form-control" placeholder="Password"/>
                </div>
                <?php
                if ($error != ""){
                    echo "<div class='alert alert-danger' role='alert'>
                        <strong>Oops!</strong> $error</div>";
                }
                ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>

        </div>
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
</body>
</html>