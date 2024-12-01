<?php

if(isset($_SESSION['customerLoggedIn']))
{
    $email = validate($_SESSION['customerUser']['email']);

    $query = "SELECT * FROM customer WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 0)
    {
        customerLogoutSession();
        redirect('../CLogin/login.php','Access Denied...');
    }
}
else
{
    redirect('../CLogin/login.php','Login to Continue...');
}

?>