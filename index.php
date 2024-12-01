<?php include('includes/header.php'); ?>

<div class="py-5">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="p-5 rounded-4 shadow-lg" 
                     style="background-color: rgba(58, 58, 58, 0.9); color: #d8bc97;">
                    <h1 class="mb-4"><strong>Urban Daybreak Admin</strong></h1>
                    <?php if (!isset($_SESSION['loggedIn'])): ?>
                    <a href="login.php" class="btn btn-custom mt-4">
                        Login
                    </a>
                    <a href="register.php" class="btn btn-custom mt-4">
                        Register
                    </a>
                    <?php else: ?>
                        <a href="Admins" class="btn btn-custom mt-4">Dashboard</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-custom {
        background-color: #d8bc97; /* Button background */
        color: #3a3a3a; /* Button text */
        border: none;
        font-weight: bold;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-custom:hover {
        transform: scale(1.05); /* Slightly enlarge on hover */
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
        background-color: #3a3a3a; /* Slightly darker shade */
    }
</style>

<?php include('includes/footer.php'); ?>
