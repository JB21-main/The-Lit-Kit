<?php
    session_start();

    if (isset($_SESSION['user_id'])) {
    header("Location: /cs4347Project/phpFiles/mainPage.php");
    }   
    else {
        header("Location: /cs4347Project/phpFiles/mainPage.php");
    }
    
    exit();
?>