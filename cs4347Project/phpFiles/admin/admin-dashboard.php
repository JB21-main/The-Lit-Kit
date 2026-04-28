<?php
require_once '../user/db_connect.php';
require_once 'admin_check.php';
$total_books = $conn->query("SELECT COUNT(*) as count FROM books")->fetch_assoc()['count'];
$total_copies = $conn->query("SELECT COUNT(*) as count FROM books_copy")->fetch_assoc()['count'];
$checked_out = $conn->query("SELECT COUNT(*) as count FROM books_copy WHERE status = 'Checked Out'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Lit Kit</title>
    <!--links for fonts and style sheet-->
    <link rel="stylesheet" href="/CS4347DATABASEPROJECT/cs4347Project/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Junge&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet"/>
    <script src="https://kit.fontawesome.com/c00cc4f5f4.js" crossorigin="anonymous"></script>
    <!---->
</head>
<body class="dashboard-background">
    <?php include '../common/nav.php'; ?> 
    
    <h1 class="welcome-msg">Welcome to your Lit Kit dashboard!</h1>
    

<div class="stats-row">
  <div class="stat-card">
    <p class="stat-number"><?= $total_books ?></p>
    <p class="stat-label">Total Books</p>
  </div>
  <div class="stat-card">
    <p class="stat-number"><?= $total_copies ?></p>
    <p class="stat-label">Total Copies</p>
  </div>
  <div class="stat-card">
    <p class="stat-number"><?= $checked_out ?></p>
    <p class="stat-label">Checked Out</p>
  </div>
</div>
    
   
    
</body>
</html>