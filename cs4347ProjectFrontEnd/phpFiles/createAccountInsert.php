<?php
    session_start();

    //Variables to hold inputted info
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    //Database Connection
    $conn = new mysqli('localhost', 'root', '','cs4347project');
    if($conn->connect_error){
        die('Connection Failed: '.$conn->connect_error);
    }

    // Checking if email already used
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    
    if($result->num_rows > 0){
        echo"Email already in use";
    }
    
    else{
        //Inserts info into table
        $stmt = $conn->prepare("INSERT INTO users(fname, lname, email, password,role) VALUES(?,?,?,?,?)");
        $stmt->bind_param("sssss", $fname, $lname, $email, $password, $role);
        $stmt->execute();
        
        //Verification Message
        echo "Congrats the php works!";
        $stmt->close();
        $conn->close();
    }
?>