<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/25/16
 * Time: 15:19
 */

$page = 'AddCopy';
include('layout/admin_header.php');
include('layout/admin_sidebar.php');
?>

    <!-- // Content -->
    <div class="container layout-content">
        <div style="margin-top: 15rem;margin-bottom: 15rem;">
            <form class="form-inline" action="admin_search_doc.php" method="get">
                <div class="form-group">
                    <label class="form-control-label" for="docid">Document ID</label>
                    <input name="docid" type="text" class="form-control" style="max-width: 100px">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="copyid">Copy ID</label>
                    <input name="copyid" type="text" class="form-control" style="max-width: 100px">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="libid">Library</label>
                    <input name="libid" type="text" class="form-control" style="max-width: 100px">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="position">Position</label>
                    <input name="position" type="text" class="form-control" style="max-width: 100px">
                </div>
                <input class="form-control btn btn-warning-outline" type="submit" value="Add">
            </form>
        </div>
    </div>
    <!-- // End Content -->

<?php
include('layout/footer.php');
?>