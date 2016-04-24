<?php
$page = 'Search';
include('layout/reader_header.php');
include('layout/reader_sidebar.php');
?>

<!-- // Content -->
<div class="container layout-content">
    <div style="margin-top: 15rem;margin-bottom: 15rem;">
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

          <input name="by" id="searchBy" value="Title" hidden /input>
        </div>
        </form>
    </div>
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

</script>

<?php
include('layout/footer.php');
?>