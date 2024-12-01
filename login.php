<?php 

include('includes/header.php');

if(isset($_SESSION['loggedIn'])){
    ?>
    <script>window.location.href = 'index.php';</script>    
    <?php
}
?>

<div class="py-5">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

            <?php alertMessage(); ?>
                <div class="p-5 rounded-4" style="background-color: rgba(58, 58, 58, 0.9); color:#d8bc97; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);">
                    <h2 class="mb-3"><strong>Urban Daybreak Admin </strong><br> Sign In</h2>
                    <form action="login-code.php" method="POST">

                    <div class="mb-3">
                        <label>Enter Email Id</label>
                        <input type="email" name="email" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label>Enter Password</label>
                        <input type="password" name="password" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="loginBtn" class="btn btn-custom w-100 mt-2">
                            Log In
                        </button>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
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

    .form-control {
        border-radius: 8px;
        border: 1px solid #d8bc97;
        background-color: rgba(58,58,58, 0.5); /* Light transparent background */
        color: #d8bc97;
    }

    .form-control:focus {
        box-shadow: 0 0 5px rgba(216, 188, 151, 0.7);
        background-color: rgba(216, 188, 151, 0.8);
        border-color: #d8bc97;
    }
</style>

<?php include('includes/footer.php'); ?>
