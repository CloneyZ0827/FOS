<?php

session_start();
include('config/dbcon.php');
include('config/function.php');

if (isset($_POST['registerAdmin'])) {
    // Retrieve form data
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $confirm_password = validate($_POST['confirm_password']);

    // Validate inputs
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
        redirect('register.php', 'Full Name must contain only letters and numbers.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect('register.php', 'Invalid email format.');
    } elseif (!preg_match("/@admin\.com$/", $email)) { // Check if email ends with @admin.com
        redirect('register.php', 'Email must end with @admin.com.');
    } elseif (strlen($password) < 8) {
        redirect('register.php', 'Password must be at least 8 characters long.');
    } elseif ($password !== $confirm_password) {
        redirect('register.php', 'Passwords do not match.');
    } else {
        // Check if the email is already used by another user
        $EmailCheckQuery = "SELECT * FROM admins WHERE a_email = ?";
        $stmt = $conn->prepare($EmailCheckQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            redirect('register.php', 'Email already used by another user.');
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Prepare data for insertion
            $data = [
                'a_name' => $name,
                'a_email' => $email,
                'password' => $hashed_password,
            ];

            // Insert new admin data
            $result = insert('admins', $data);

            if ($result) {
                redirect('login.php', 'Admin Created Successfully!');
            } else {
                redirect('register.php', 'Something Went Wrong!');
            }
        }
    }
}
?>
