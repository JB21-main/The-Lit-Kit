<?php
session_start();
require_once '../user/db_connect.php';
require_once 'admin_check.php';

// Get data from session
$admin_id = $_SESSION['user_id'] ?? 'N/A';
$admin_fname = $_SESSION['fnaame'] ?? 'Admin';
$admin_lname = $_SESSION['lname'] ?? '';
$admin_email = $_SESSION['email'] ?? 'Not set';
$admin_role = $_SESSION['Role'] ?? 'Administrator';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--links for fonts and style sheet-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Junge&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <!---->
    <title>Admin Account</title>
</head>
<!--change hard coded values once db connected-->
<body>
    <div>
        <!--div with heading and edit button TODO: move button to right-->
        <div class="horiz-spaced-div">
            <h1 >Account Details</h1>
           <div><button class="justify-right">Edit Account</button></div>
        </div>
    
    <h3>Name</h3>
    <input type="acct-text" value="<?php echo htmlspecialchars($admin_fname . ' ' . $admin_lname); ?>" readonly>  <!-- change to actual value, same for below-->
    <h3>Email</h3>
    <input type="acct-text" value="<?php echo htmlspecialchars($admin_email); ?>" readonly> 

    <h1 >Role Details</h1>
    <p><strong>Role:</strong> <?php echo ucfirst($admin_role); ?></p>
    <p><strong>ID:</strong> <?php echo htmlspecialchars($admin_id); ?></p>
</body>
<footer>

</footer>
</html>