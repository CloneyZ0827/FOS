<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu" style="background-color: rgba(58, 58, 58, 0.7);">
            <div class="nav">
                <div class="sb-sidenav-menu-heading" style="color: rgba(216, 188, 151, 0.6);">Orders</div>

                <a class="nav-link" href="index.php?table_no=<?= htmlspecialchars($_SESSION['table_no'] ?? '') ?>" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-cart-plus"></i></div>
                    Menu
                </a>

                <a class="nav-link" href="order-status.php?table_no=<?= htmlspecialchars($_SESSION['table_no'] ?? '') ?>" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-clipboard-check"></i></div>
                    Order Status
                </a>

                <a class="nav-link" href="logout.php" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fas fa-running"></i></div>
                    Log Out
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer" style="background-color: rgba(58, 58, 58, 0.5);">
            <div class="small" style="color: #d8bc97;">Logged in as:</div>
            <span style="color: #d8bc97;">Guest</span>
        </div>

    </nav>
</div>
