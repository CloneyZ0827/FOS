<?php

require 'config/function.php';

if(isset($_POST['loginBtn']))
{

    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    if($email != '' && $password != '')
    {
        $query = "SELECT * FROM admins WHERE a_email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if($result){

            if(mysqli_num_rows($result) == 1){

                $row = mysqli_fetch_assoc($result);
                $hasedpassword = $row['password'];

                if(!password_verify($password,$hasedpassword)){
                    redirect('login.php','Invalid Password');
                }

                if($row['is_ban'] == 1){
                    redirect('login.php','Your account has been banned!');
                }

                $_SESSION['loggedIn'] = true;
                $_SESSION['loggedInUser'] = [
                    'user_id' => $row['a_id'],
                    'name' => $row['a_name'],
                    'email' => $row['a_email'],
                    'position' => $row['a_position'],
                ];

                redirect('Admins/index.php','You have Successfully Logged In');

            }else{
                redirect('login.php','Invalid Email Adderss');
            }

        } else {
            redirect('login.php','Something Went Wrong!');
        }
    } 
    else
    {
        redirect('login.php','All fields are mendetory!');
    }
}

?>