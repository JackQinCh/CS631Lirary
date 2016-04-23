<?php
// Get Request Data
$search = $_GET['search'];
//
$page = 'Reservations';
include('layout/header.php');
include('layout/reader_sidebar.php');
?>

<!-- // Content -->
<div class="container layout-content">
    <ol class="breadcrumb m-t-1">
        <li class="active">Search</li>
    </ol>
    <!-- Search Bar -->
    <div class="m-t-1 m-b-1">
        <form action="" method="get">
            <input name="search" type="text" value="<?php echo $search?>" class="form-control" placeholder="Search Reader Name">
        </form>
    </div>
    <!-- End Search Bar -->

    <!-- Search List -->
    <?php

    if ($search != ""){
        // Create connection
        $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //Query
        $sql = "SELECT READERID, RTYPE, RNAME
                FROM READER
                WHERE RNAME LIKE '%$search%'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            while ($row = $result->fetch_assoc()){
                echo "<div class='card card-block search-list-item' 
                onclick=window.location.href='reader_reader_detail.php?readID=".$row['READERID']."&search=".$search."'>
                    <h4 class='card-title'>".$row['RNAME']."</h4>
                    <p class='card-text text-muted'>".$row['RTYPE']."</p>
                    </div>";
            }

        }else{
            echo "<div class='alert alert-danger' role='alert'>
        <strong>Oops!</strong> No reader found. Try another name!</div>";
        }

        $conn->close();
    }
    ?>
    <!-- End Search List -->
</div>
<!-- // End Content -->
<script>
    $('.search-list-item').hover(
        function () {
            $(this).addClass('card-inverse');
            $(this).addClass('card-primary');
            $(this).css('cursor', 'pointer');
        },
        function () {
            $(this).removeClass('card-inverse');
            $(this).removeClass('card-primary');
            $(this).css('cursor', 'default');
        }
    );
</script>

<?php
include('layout/footer.php');
?>