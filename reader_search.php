<?php
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

$page = 'Search';
include('layout/reader_header.php');
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

          <input name="by" value="<?php echo $searchBy?>" hidden /input>
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
    $conn->close();
}
?>
<!-- End Search List -->
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