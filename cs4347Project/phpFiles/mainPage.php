<?php
  session_start();
  require_once 'db_connect.php';
  
  $firstTime = false;
  if(isset($_SESSION['user_id'])) {
      $check = $conn->prepare("SELECT Genre1 FROM users WHERE UserID = ?");
      $check->bind_param("i", $_SESSION['user_id']);
      $check->execute();
      $result = $check->get_result();
      $data = $result->fetch_assoc();
      
      if($data['Genre1'] == NULL){
        $firstTime = true;
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
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --crimson: #b91c1c;
      --crimson-hover: #991b1b;
      --dark: #1a1a1a;
      --border: #e0e0e0;
      --bg: #ffffff;
    }

    body {
      font-family: 'EB Garamond', Georgia, serif;
      background: var(--bg);
      color: var(--dark);
      min-height: 100vh;
    }

    /* top header layout */
    .top-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 18px 60px;
      border-bottom: 1px solid var(--border);
    }

    .logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 2px;
      text-decoration: none;
    }


    .logo-text {
      font-family: 'Playfair Display', Georgia, serif;
      font-style: italic;
      font-size: 2.0rem;
      color: var(--dark);
      letter-spacing: 0.01em;
    }

    .sign-in {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.5rem;
      color: var(--dark);
      text-decoration: none;
      cursor: pointer;
      transition: color 0.2s;
    }
    .sign-in:hover { color: var(--crimson); }

    /*added welcome message*/
    .welcome {
      margin-left: -20px;
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.5rem;
      color: var(--dark);
    }

    /* navigation bar links */
    nav {
      display: flex;
      justify-content: center;
      gap: 100px;
      padding: 14px 0;
      border-bottom: 1px solid var(--border);
    }

    nav a {
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.05rem;
      color: var(--dark);
      text-decoration: none;
      letter-spacing: 0.02em;
      position: relative;
      transition: color 0.2s;
    }

    nav a::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 1px;
      background: var(--crimson);
      transition: width 0.25s ease;
    }
    nav a:hover { color: var(--crimson); }
    nav a:hover::after { width: 100%; }

    /* main hero section */
    .hero {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 80px 40px;
      min-height: calc(100vh - 130px);
    }

    .hero-left {
      max-width: 520px;
      text-align: center;
    }

    .hero-title {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: clamp(2.6rem, 5vw, 3.8rem);
      font-weight: 400;
      line-height: 1.2;
      color: var(--dark);
      margin-bottom: 48px;
      letter-spacing: -0.01em;
    }

    .btn-start {
      display: inline-block;
      background: var(--crimson);
      color: #fff;
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.15rem;
      letter-spacing: 0.04em;
      padding: 18px 80px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 2px 8px rgba(185,28,28,0.18);
    }
    .btn-start:hover {
      background: var(--crimson-hover);
      transform: translateY(-1px);
      box-shadow: 0 5px 18px rgba(185,28,28,0.28);
    }
    .btn-start:active { transform: translateY(0); }


  </style>
</head>
<body>

  <!-- logo and sign in at the top -->
 <header class="top-bar">
    <div style="width:200px">
        <?php
            if (isset($_SESSION['user_id'])) {
                echo "<span class='welcome'>Welcome, " . $_SESSION['fname'] . "</span>";
            } 
        ?>
    </div>

    <span class="logo-text">The Lit Kit</span>

    <div style="width:200px; text-align:right;">
        <?php
            if (isset($_SESSION['user_id'])) {
                echo "<a href='logout.php' class='sign-in'>Logout</a>";
            }
        ?>
    </div>
</header>

  <!-- nav links -->
  <nav>
    <a href="#">Home</a>
    <a href="#">My Books</a>
    <a href="#">Account</a>
  </nav>

  <!-- main section of the page -->
  <main class="hero">
    <div class="hero-left">
      <h1 class="hero-title">Everything you <br>need to get back to<br>Literature</h1>
    <?php if($firstTime): ?>
      <a href="preferences.php" class="btn-start">Get Started</a>
    <?php endif; ?>
    </div>

  </main>

</body>
</html>