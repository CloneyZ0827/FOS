<nav class="sb-topnav navbar navbar-expand" style="background-color: #3a3a3a;">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="../CLogin/index.php?table_no=<?= htmlspecialchars($_SESSION['customerUser']['table_no'] ?? '') ?>" style="color: #d8bc97;">Urban Daybreak</a>
    
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!" style="color: #d8bc97;">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Table No-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <?php
        // Get table_no from URL or session
        $table_no = isset($_GET['table_no']) ? $_GET['table_no'] : (isset($_SESSION['customerUser']['table_no']) ? $_SESSION['customerUser']['table_no'] : '');
        
        // Display table_no if it exists
        if ($table_no) {
            echo '<h7><span class="navbar-text" style="color: #d8bc97;">Table No: ' . htmlspecialchars($table_no) . '</span></h7>';
        }
        ?>
    </form>
    
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #d8bc97;">
                <i class="fas fa-user fa-fw"></i>
                <?= $_SESSION['customerUser']['name']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="../CLogin/logout.php?table_no=<?= htmlspecialchars($_SESSION['customerUser']['table_no'] ?? '') ?>">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>
