<?php

    session_start();

    include("connect.php");

    if ($_SERVER[ 'REQUEST_METHOD'] "POST")

{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
     
    if(!empty($email) && !empty($password) && !is_numeric ($email))
    
    {
        $query = "insert into register (name, email, password) values('$name',' $email','$password')";

        mysqli_query($con, $query);

        echo "<script type='text/javascript'> alert('Successfully Register')</script>";
         
    }
    else
    {
        echo "<script type='text/javascript'> alert('please enter valid')</script>";
    }
}
 

