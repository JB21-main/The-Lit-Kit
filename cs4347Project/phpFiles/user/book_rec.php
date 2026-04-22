<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$rec_sql = "SELECT b.mmsID, b.Title, a.authorName, COUNT(bl.logID) as popularity
            FROM books b
            JOIN author a ON b.authorID = a.authorID
            JOIN book_genre bg ON b.mmsID = bg.mmsID
            JOIN prefers p ON bg.genreID = p.genreID
            LEFT JOIN book_log bl ON b.mmsID = bl.mmsID 
            WHERE p.userID = ?
            GROUP BY b.mmsID, b.Title, a.authorName
            ORDER BY popularity DESC, b.Title ASC
            LIMIT 3";

$stmt = $conn->prepare($rec_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recommendations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>The Lit Kit — My Books</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet"/>

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --dark: #1a1a1a;
  --border: #e0e0e0;
  --bg: #f5f3f0;
  --card: #d9d9d9;
  --crimson: #b91c1c;
}

body {
  font-family: 'EB Garamond', Georgia, serif;
  background: var(--bg);
  color: var(--dark);
}

/* header */
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 60px;
  border-bottom: 1px solid var(--border);
}

.logo-text {
  font-family: 'Playfair Display', Georgia, serif;
    font-style: italic;
    font-size: 2.0rem;
    color: var(--dark);
    letter-spacing: 0.01em;
}

.logout-link {
  font-size: 1.5rem;
  color: var(--dark);
  text-decoration: none;
  transition: color 0.2s;
}

.logout-link:hover {
  color: var(--crimson);
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

.content {
  padding: 40px 50px;
}

.section-title {
  font-size: 1.4rem;
  margin-bottom: 18px;
}

.book-row {
  display: flex;
  gap: 24px;
}

.book-card {
  width: 150px;
  height: 200px;
  background: var(--card);
  border-radius: 4px;
  transition: transform 0.2s, box-shadow 0.2s;
  cursor: pointer;
}

.book-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
</style>
</head>

<body>

<header class="top-bar">
  <div style="width:200px;"></div>

  <span class="logo-text">The Lit Kit</span>

  <div style="width:200px; text-align:right;">
    <a href="logout.php" class="logout-link">Logout</a>
  </div>
</header>

<nav>
  <a href="mainPage.php">Home</a>
  <a href="book_rec.php">My Books</a>

  <?php if ($user_id): ?>
    <a href="user_account.php?id=<?php echo $user_id; ?>">Account</a>
  <?php else: ?>
    <a href="signIn.php">Account</a>
  <?php endif; ?>
</nav>

<main class="content">

<div class="section">
  <p class="section-title">Popular in Your Favorite Genres</p>
  <div class="book-row">
    <?php while($row = $recommendations->fetch_assoc()): ?>
    <a href="book_info.php?id=<?php echo $row['mmsID']; ?>">
      <div class="book-card">
        <div style="padding: 15px;">
          <strong><?php echo htmlspecialchars($row['Title']); ?></strong><br>
          <span style="font-size: 0.85rem; font-style: italic;">
            by <?php echo htmlspecialchars($row['authorName']); ?>
          </span>
        </div>
      </div>
    </a>
    <?php endwhile; ?>
  </div>
</div>

</main>

</body>
</html>