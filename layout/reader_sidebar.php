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
        <li class="sidebar-menu-item <?php echo ($page == 'Search') ? "active" : ""; ?>">
            <a href="reader.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">search</i>
                Search</a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'Reserved') ? "active" : ""; ?>">
            <a href="reader_reserves.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">lock</i>
                Reserved</a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'Borrowed') ? "active" : ""; ?>">
            <a href="reader_borrowed.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">check_circle</i>
                Borrowed</a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'Reservations') ? "active" : ""; ?>">
            <a href="reader_findreader.php" class="sidebar-menu-button">
                <i class="sidebar-menu-icon material-icons">print</i>
                Print Reservations
            </a>
        </li>
        <li class="sidebar-menu-item <?php echo ($page == 'PublisherDocs') ? "active" : ""; ?>">
            <a href="reader_find_publisher.php" class="sidebar-menu-button">
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