<?php
$page = 'Reservations';
include('layout/reader_header.php');
include('layout/reader_sidebar.php');
?>

<!-- // Content -->
<div class="container layout-content">
    <div style="margin-top: 15rem;margin-bottom: 15rem;">
        <form action="reader_search_reader.php" method="get">
            <input name="search" type="text" class="form-control" placeholder="Search Reader Name">
        </form>
    </div>
</div>
<!-- // End Content -->
<?php
include('layout/footer.php');
?>