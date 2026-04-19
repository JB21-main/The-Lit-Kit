<?php
    $name = $_POST['name'];
    list($firstName, $lastName) = explode(' ', $name);
    $email = $_POST['email'];
    $genre1 = $_POST['genre1'];
    $genre2 = $_POST['genre2'];
    $genre3 = $_POST['genre3'];

    //Database Connection
    $conn = new mysqli('localhost', 'root', '', 'cs4347project');
    if($conn->connect_error){
        die('Connection Failed: '.$conn->connect_error);
    }
    else{
        $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
    }
?>