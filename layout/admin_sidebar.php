<!-- // Sidebar-->
<div class="sidebar">
    <div class="sidebar-heading">Roles</div>
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="reader_index.php">
                <i class="sidebar-menu-icon material-icons">face</i>
                Reader
            </a>
        </li>
        <li class="sidebar-menu-item active">
            <a class="sidebar-menu-button" href="admin_index.php">
                <i class="sidebar-menu-icon material-icons">perm_identity</i>
                Administrator
            </a>
        </li>
    </ul>

    <div class="sidebar-heading">Admin Menu</div>
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item <?php echo ($page == 'Search') ? "active" : ""; ?>">
            <a href="admin.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">search</i>
                Search</a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'AddCopy') ? "active" : ""; ?>">
            <a href="admin_add_copy.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">note_add</i>
                Add Copy</a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'AddReader') ? "active" : ""; ?>">
            <a href="admin_add_reader.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">account_circle</i>
                Add Reader</a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'PrtBranchInfo') ? "active" : ""; ?>">
            <a href="admin_branch_info.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">print</i>
                Print Branch Info.
            </a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'TopBorrowers') ? "active" : ""; ?>">
            <a href="admin_top_borrowers.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">supervisor_account</i>
                Top 10 Borrowers
            </a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'TopBooks') ? "active" : ""; ?>">
            <a href="admin_top_books.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">star_rate</i>
                Top 10 Books
            </a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'PopBooks') ? "active" : ""; ?>">
            <a href="admin_pop_books.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">favorite</i>
                Top 10 Pop Books
            </a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'AvgFine') ? "active" : ""; ?>">
            <a href="" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">monetization_on</i>
                Average Fine
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="admin_logout.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">power_settings_new</i>
                Quit
            </a>
        </li>
    </ul>
</div>
<!-- //End Sidebar-->