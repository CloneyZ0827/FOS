<?php
require '../config/function.php';


// Check if table_no is in the URL and store it in the session
if (isset($_GET['table_no']) && !isset($_SESSION['customerUser']['table_no'])) {
    $_SESSION['customerUser']['table_no'] = $_GET['table_no'];
}

// Now you can use $_SESSION['customerUser']['table_no'] throughout your site
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Urban Daybreak - Customers</title>

    <link href="../Customers/assets/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body style="background-image: url('BG.jpg');">

  
    <?php include('navbar.php') ?>
