<nav class="sb-topnav navbar navbar-expand" style="background-color: #3a3a3a;">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="../CLogin/index.php?table_no=<?= htmlspecialchars($_SESSION['table_no'] ?? $_SESSION['tableNumber'] ?? '') ?>" style="color: #d8bc97;">Urban Daybreak</a>
    
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!" style="color: #d8bc97;">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Table No-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <?php
        // For guests, show the table number if it's set in the session
        if (isset($_SESSION['guestLoggedIn']) && $_SESSION['guestLoggedIn'] == true) {
            // For guests, you can display the table number from the session
            $table_no = isset($_SESSION['tableNumber']) ? $_SESSION['tableNumber'] : '';
            if ($table_no) {
                echo '<h7><span class="navbar-text" style="color: #d8bc97;">Table No: ' . htmlspecialchars($table_no) . '</span></h7>';
            }
        }
        // For customers, display the table number stored in the session
        elseif (isset($_SESSION['table_no'])) {
            echo '<h7><span class="navbar-text" style="color: #d8bc97;">Table No: ' . htmlspecialchars($_SESSION['table_no']) . '</span></h7>';
        }
        ?>
    </form>
    
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <?php if (isset($_SESSION['guestLoggedIn']) && $_SESSION['guestLoggedIn'] == true): ?>
            <!-- For Guests -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #d8bc97;">
                    <i class="fas fa-user fa-fw"></i> Guest
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../Guest/logout.php?table_no=<?= htmlspecialchars($_SESSION['table_no'] ?? $_SESSION['tableNumber'] ?? '') ?>">Logout</a></li>
                </ul>
            </li>
        <?php else: ?>
            <!-- For users who are neither customers nor guests (you can add login and register options) -->
            <li class="nav-item">
                <a href="../CLogin/login.php" class="nav-link" style="color: #d8bc97;">Login</a>
            </li>
            <li class="nav-item">
                <a href="../CLogin/register.php" class="nav-link" style="color: #d8bc97;">Register</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
