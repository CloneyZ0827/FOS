<?php

require '../config/function.php';

if(isset($_POST['loginBtn']))
{
    // Retrieve and validate input fields
    $email = validate($_POST['email']); // Trim spaces
    $password = validate($_POST['password']); // Trim spaces

    // Check if fields are not empty
    if($email != '' && $password != '')
    {
        // Query to check if email exists in the Customer table
        $query = "SELECT * FROM customer WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result)
        {
            if(mysqli_num_rows($result) == 1)
            {
                // Fetch customer data from the result
                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row['password'];  // Use the correct column for password

                // Verify the entered password with the hashed password
                if(!password_verify($password, $hashedPassword))
                {
                    redirect('login.php?table_no=' . $_SESSION['table_no'], 'Invalid Password');
                }

                // Set session variables for the logged-in customer
                $_SESSION['customerLoggedIn'] = true;  //loggedIn
                $_SESSION['customerUser'] = [          //loggedInUser
                    'id' => $row['id'],           // Customer's unique ID
                    'name' => $row['cName'],          // Customer's name
                    'email' => $row['email'],        // Customer's email
                    'phone' => $row['phoneNo'],      // Customer's phone number
                ];

                // Redirect to the home or dashboard page
                redirect('../Customers/index.php?table_no=' . $_SESSION['table_no'], 'You have successfully logged in!');
            }
            else
            {
                // Invalid email address
                redirect('login.php?table_no=' . $_SESSION['table_no'], 'Invalid Email Address');
            }
        }
        else
        {
            // Something went wrong with the query
            redirect('login.php?table_no=' . $_SESSION['table_no'], 'Something Went Wrong!');
        }
    }
    else
    {
        // Fields are empty
        redirect('login.php?table_no=' . $_SESSION['table_no'], 'All fields are mandatory!');
    }
}
?>
