<?php

include('../config/dbcon.php');
include('../config/function.php');

if (isset($_POST['updateCustomer'])) {
    $customerId = validate($_POST['customerId']);

    // Fetch existing customer data
    $customerData = getById('customer', $customerId);
    if ($customerData['status'] != 200) {
        redirect('edit-account.php?id=' . $customerId, 'Customer not found.');
    }

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']); // New phone number field
    $password = validate($_POST['password']); // Optional password field

    // Handle password change (only if a new password is provided)
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the new password
    } else {
        // Retain the existing password if no new one is entered
        $hashedPassword = $customerData['data']['password'];
    }

    // Ensure all required fields are filled
    if (!empty($name) && !empty($email) && !empty($phone)) {
        $data = [
            'cName' => $name,
            'email' => $email,
            'phoneNo' => $phone,
            'password' => $hashedPassword // Use the hashed password
        ];

        // Update customer data in the database
        $result = update('customer', $customerId, $data);

        if ($result) {
            // Update session with new customer data
            $_SESSION['customerUser']['name'] = $name;
            $_SESSION['customerUser']['email'] = $email;
            $_SESSION['customerUser']['phone'] = $phone;
        
            // Redirect to the edit-account page with a success message
            redirect('edit-account.php?id=' . $customerId, 'Customer Updated Successfully!');
        } else {
            // If something went wrong, redirect with an error message
            redirect('edit-account.php?id=' . $customerId, 'Something went wrong!');
        }               
    } else {
        redirect('edit-account.php?id=' . $customerId, 'Please fill in all required fields.');
    }
}
?>
