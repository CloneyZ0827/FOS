<?php

include('../config/dbcon.php');
include('../config/function.php');

if (isset($_POST['saveAdmin'])) {
    // Retrieve form data
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $confirm_password = validate($_POST['confirm_password']);
    $position = validate($_POST['position']);
    $is_ban = isset($_POST['is_ban']) && $_POST['is_ban'] == 'true' ? 1 : 0;

    // Validate inputs
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
        redirect('admins-create.php', 'Full Name must contain only letters and numbers.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect('admins-create.php', 'Invalid email format.');
    } elseif (!preg_match("/@admin\.com$/", $email)) { // Check if email ends with @admin.com
        redirect('admins-create.php', 'Email must end with @admin.com.');
    } elseif (strlen($password) < 8) {
        redirect('admins-create.php', 'Password must be at least 8 characters long.');
    } elseif ($password !== $confirm_password) {
        redirect('admins-create.php', 'Passwords do not match.');
    } else {
        // Check if the email is already used by another user
        $EmailCheckQuery = "SELECT * FROM admins WHERE a_email = ?";
        $stmt = $conn->prepare($EmailCheckQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            redirect('admins-create.php', 'Email already used by another user.');
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Prepare data for insertion
            $data = [
                'a_name' => $name,
                'a_email' => $email,
                'password' => $hashed_password,
                'a_position' => $position,
                'is_ban' => $is_ban
            ];

            // Insert new admin data
            $result = insert('admins', $data);

            if ($result) {
                redirect('admins.php', 'Admin Created Successfully!');
            } else {
                redirect('admins-create.php', 'Something Went Wrong!');
            }
        }
    }
}


if (isset($_POST['updateAdmin'])) {
    $adminId = validate($_POST['adminId']);

    // Fetch existing admin data
    $adminData = getById('admins', $adminId);
    if ($adminData['status'] != 200) {
        redirect('admins-edit.php?id=' . $adminId, 'Admin not found.');
    }

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']); // New password field
    $position = validate($_POST['position']);
    
    // Ensure that is_ban is set correctly (check if checkbox was checked)
    $is_ban = isset($_POST['is_ban']) && $_POST['is_ban'] == 'true' ? 1 : 0;

    // Handle password change (only if a new password is provided)
    if ($password != '') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the new password
    } else {
        // Retain the existing password if no new one is entered
        $hashedPassword = $adminData['data']['password'];
    }

    // Ensure that both name and email are not empty
    if ($name != '' && $email != '') {
        $data = [
            'a_name' => $name,
            'a_email' => $email,
            'password' => $hashedPassword, // Use the hashed password
            'a_position' => $position,
            'is_ban' => $is_ban // This will update the ban status
        ];

        // Update admin data in the database
        $result = update('admins', $adminId, $data);

        if ($result) {
            redirect('admins-edit.php?id=' . $adminId, 'Admin Updated Successfully!');
        } else {
            redirect('admins-edit.php?id=' . $adminId, 'Something Went Wrong!');
        }
    } else {
        redirect('admins-edit.php?id=' . $adminId, 'Please fill in all required fields.');
    }
}



if(isset($_POST['saveCategory']))
{
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = validate($_POST['status']) == true ? 1:0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status
    ];
    $result = insert('categories',$data);

    if($result){
        redirect('categories.php','Category Created Successfully!');
    } else {
        redirect('categories-create.php','Something Went Wrong!');
    }
}

if(isset($_POST['updateCategory']))
{    
    $categoryId = validate($_POST['categoryId']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = validate($_POST['status']) ? 1:0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status
    ];
    $result = update('categories',$categoryId,$data);

    if($result){
        redirect('categories-edit.php?id='.$categoryId,'Category Updated Successfully!');
    } else {
        redirect('categories-edit.php?id='.$categoryId,'Something Went Wrong!');
    }
}

if (isset($_POST['saveMenu'])) {
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = validate($_POST['status']) == true ? 1 : 0;

    // Check if category is selected
    if (empty($category_id)) {
        redirect('products-create.php', 'Please select a valid category.');
    }

    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/menus";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time() . '.' . $image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);
        $finalImage = "assets/uploads/menus/" . $filename;
    } else {
        $finalImage = '';
    }

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status
    ];

    $result = insert('menu', $data);

    if ($result) {
        redirect('products.php', 'Menu Created Successfully!');
    } else {
        redirect('products-create.php', 'Something Went Wrong!');
    }
}


if (isset($_POST['updateMenu'])) {
    $menu_id = validate($_POST['menu_id']);
    $menuData = getById('menu', $menu_id);
    if (!$menuData) {
        redirect('products.php', 'No such product found');
    }

    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) ? 1 : 0; // Correctly handle the checkbox state

    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/menus";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time() . '.' . $image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);

        $finalImage = "assets/uploads/menus/" . $filename;

        $deleteImage = "../" . $menuData['data']['image'];
        if (file_exists($deleteImage)) {
            unlink($deleteImage);
        }
    } else {
        $finalImage = $menuData['data']['image'];
    }

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status // Ensure the status is updated properly
    ];

    $result = update('menu', $menu_id, $data);

    if ($result) {
        redirect('products-edit.php?id=' . $menu_id, 'Menu Updated Successfully!');
    } else {
        redirect('products-edit.php?id=' . $menu_id, 'Something Went Wrong!');
    }
}

?>