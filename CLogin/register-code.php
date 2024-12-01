<?php
session_start();
include('../config/dbcon.php');
include('../config/function.php');

if (isset($_POST['registerCustomer'])) {
    // Retrieve form data
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $password = validate($_POST['password']);
    $confirm_password = validate($_POST['confirm_password']);

    // Validate inputs
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
        redirect('register.php?table_no=' . $_SESSION['table_no'], 'Full Name must contain only letters and numbers.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect('register.php?table_no=' . $_SESSION['table_no'], 'Invalid email format.');
    } elseif (strpos($email, '@admin.com') !== false) { // Prevent emails with @admin.com
        redirect('register.php?table_no=' . $_SESSION['table_no'], 'Emails with @admin.com are not allowed.');
    } elseif (!preg_match("/^[0-9]+$/", $phone)) {
        redirect('register.php?table_no=' . $_SESSION['table_no'], 'Phone number must contain only digits.');
    } elseif (strlen($password) < 8) {
        redirect('register.php?table_no=' . $_SESSION['table_no'], 'Password must be at least 8 characters long.');
    } elseif ($password !== $confirm_password) {
        redirect('register.php?table_no=' . $_SESSION['table_no'], 'Passwords do not match.');
    } else {
        // Check if the email is already used
        $EmailCheckQuery = "SELECT * FROM customer WHERE email = ?";
        if ($stmt = $conn->prepare($EmailCheckQuery)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                redirect('register.php?table_no=' . $_SESSION['table_no'], 'Email already used by another user.');
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Prepare data for insertion
                $data = [
                    'cName' => $name,
                    'email' => $email,
                    'phoneNo' => $phone,
                    'password' => $hashed_password,
                ];

                // Insert new customer data using the insert function
                $insertResult = insert('customer', $data);

                if ($insertResult) {
                    Redirect('login.php?table_no=' . $_SESSION['table_no'], 'Registration successful! Please log in.');
                } else {
                    redirect('register.php?table_no=' . $_SESSION['table_no'], 'Something went wrong during registration.');
                }
            }

            // Close statement
            $stmt->close();
        } else {
            redirect('register.php?table_no=' . $_SESSION['table_no'], 'Error in preparing SQL query.');
        }
    }
}
?>
