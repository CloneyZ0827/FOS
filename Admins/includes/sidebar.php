<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu" style="background-color: rgba(58, 58, 58, 0.7);">
            <div class="nav" >
                <div class="sb-sidenav-menu-heading" style="color: rgba(216, 188, 151, 0.6);">Core</div>

                <a class="nav-link" href="index.php" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link" href="order-create.php" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-cart-plus"></i></div>
                    Create Order
                </a>

                <div class="sb-sidenav-menu-heading" style="color: rgba(216, 188, 151, 0.6);">Manage</div>

                <a class="nav-link collapsed" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-hotdog"></i></div>
                    Categories
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseCategory" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="categories-create.php" style="color: #d8bc97;">Create Category</a>
                        <a class="nav-link" href="categories.php" style="color: #d8bc97;">View Categories</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseProduct" aria-expanded="false" aria-controls="collapseProduct" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-hamburger"></i></div>
                    Menu
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseProduct" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="products-create.php" style="color: #d8bc97;">Create Menu</a>
                        <a class="nav-link" href="products.php" style="color: #d8bc97;">View Menu</a>
                    </nav>
                </div>

                <a class="nav-link" href="orders.php" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-calendar"></i></div>
                    Orders
                </a>

                <a class="nav-link" href="sales-report.php" style="color: #d8bc97;">
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-folder-open"></i></div>
                    Sales Report
                </a>


                <div class="sb-sidenav-menu-heading" style="color: rgba(216, 188, 151, 0.6);">Admin</div>
                
                <a class="nav-link collapsed" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseAdmins"  
                    aria-expanded="false" aria-controls="collapseAdmins" style="color: #d8bc97;">
                    
                    <div class="sb-nav-link-icon" style="color: rgba(216, 188, 151, 0.6);"><i class="fas fa-cogs"></i></div>
                    Admins
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAdmins" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="admins-create.php" style="color: #d8bc97;">Add Admin</a>
                        <a class="nav-link" href="admins.php" style="color: #d8bc97;">View Admins</a>
                    </nav>
                </div>

            </div>
        </div>
        <div class="sb-sidenav-footer" style="background-color: rgba(58, 58, 58, 0.5);">
            <div class="small" style="color: #d8bc97;">Logged in as:</div>
            <span style="color: #d8bc97;"><?= $_SESSION['loggedInUser']['name']; ?></span>
        </div>

    </nav>
</div>