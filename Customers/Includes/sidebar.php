<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu" style="background-color: rgba(58, 58, 58, 0.7);">
            <div class="nav">
                <div class="sb-sidenav-menu-heading" style="color: rgba(216, 188, 151, 0.6);">Orders</div>

                <a class="nav-link" href="index.php?table_no=<?= htmlspecialchars($_SESSION['customerUser']['table_no'] ?? '') ?>" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-cart-plus"></i></div>
                    Menu
                </a>

                <a class="nav-link" href="order-status.php?table_no=<?= htmlspecialchars($_SESSION['customerUser']['table_no'] ?? '') ?>" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fas fa-clipboard-check"></i></div>
                    Order Status
                </a>

                <div class="sb-sidenav-menu-heading" style="color: rgba(216, 188, 151, 0.6);">Manage</div>

                <a class="nav-link" href="order-history.php?table_no=<?= htmlspecialchars($_SESSION['customerUser']['table_no'] ?? '') ?>" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-folder-open"></i></div>
                    Order History
                </a>

                <!-- Account Settings -->
                <div class="sb-sidenav-menu-heading" style="color: rgba(216, 188, 151, 0.6);">Account</div>
                
                <a class="nav-link collapsed" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseAdmins"  
                    aria-expanded="false" aria-controls="collapseAdmins" style="color: #d8bc97;">
                    
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-cogs"></i></div>
                    Settings
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAdmins" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="edit-account.php?id=<?= htmlspecialchars($_SESSION['customerUser']['id'] ?? '') ?>" style="color: #d8bc97;">Edit Account</a>
                    <a class="nav-link" href="account.php?id=<?= htmlspecialchars($_SESSION['customerUser']['id'] ?? '') ?>" style="color: #d8bc97;">View Account</a>
                    </nav>
                </div>

            </div>
        </div>
        <div class="sb-sidenav-footer" style="background-color: rgba(58, 58, 58, 0.5);">
            <div class="small" style="color: #d8bc97;">Logged in as:</div>
            <span style="color: #d8bc97;"><?= $_SESSION['customerUser']['name']; ?></span>
        </div>

    </nav>
</div>
