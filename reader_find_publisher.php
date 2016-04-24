<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/23/16
 * Time: 18:35
 */

$page = 'PublisherDocs';
include('layout/reader_header.php');
include('layout/reader_sidebar.php');
?>

    <!-- // Content -->
    <div class="container layout-content">
        <div style="margin-top: 15rem;margin-bottom: 15rem;">
            <form action="reader_publisher_list.php" method="get">
                <input name="search" type="text" class="form-control" placeholder="Search Publisher Name">
            </form>
        </div>
    </div>
    <!-- // End Content -->
<?php
include('layout/footer.php');
?>