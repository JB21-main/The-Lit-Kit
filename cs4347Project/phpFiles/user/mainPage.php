<?php
  require_once 'db_connect.php';
  session_start();

  $firstTime = true;

  if (isset($_SESSION['user_id'])) {

    $check = $conn->prepare("SELECT * FROM prefers WHERE userID = ?");
    $check->bind_param("i", $_SESSION['user_id']);
    $check->execute();
    $result = $check->get_result();

    // if user already has preferences → NOT first time
    if ($result->num_rows > 0) {
        $firstTime = false;
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>The Lit Kit</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/cs4347DATABASEPROJECT/cs4347Project/css/main.css">
  <!--links for fonts and style sheet-->
    <link rel="stylesheet" href="/CS4347DatabaseProject/cs4347Project/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Junge&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c00cc4f5f4.js" crossorigin="anonymous"></script>
    <!---->
</head>
<body>
  <?php include '../common/nav.php'; ?> 
  <!-- logo and welcome at the top 
 <header class="top-bar">
    <div style="width:200px">
        < ?php
          if (isset($_SESSION['fname'])) {
            echo "<span class='welcome'>Welcome, " . $_SESSION['fname'] . "</span>";
          }
        ?>
    </div>

    <span class="logo-text">The Lit Kit</span>

    <div style="width:200px; text-align:right;">
        < ?php
            if (isset($_SESSION['user_id'])) {
                echo "<a href='logout.php' class='sign-in'>Logout</a>";
            }
        ?>
    </div>
</header>


  <nav>
    <a href="mainPage.php">Home</a>
    <a href="book_rec.php">My Books</a>
    <a href="user_account.php">Account</a>
  </nav>
-->
  <!-- main section of the page -->
  <main class="hero">
    <div class="hero-center">
      <h1 class="hero-title">Everything you <br>need to get back to<br>Literature</h1>
    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="signIn.php" class="btn-start">Get Started</a>

    <?php elseif ($firstTime): ?>
      <a href="preferences.php" class="btn-start">Get Started</a>
    <?php else: ?>
      <a href="book_rec.php" class="btn-start">Go to Recommendations</a>
    <?php endif; ?>
    </div>
  </main>

</body>
</html>