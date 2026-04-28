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
<!--links for fonts and style sheet-->
    <link rel="stylesheet" href="/CS4347DatabaseProject/cs4347Project/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Junge&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet"/>
    <script src="https://kit.fontawesome.com/c00cc4f5f4.js" crossorigin="anonymous"></script>
    <!---->
<style>
a {
  text-decoration: none;
}
.content {
  padding: 40px 100px;
}

.section-title {
  font-size: 1.4rem;
  margin-bottom: 18px;
}

.book-row {
  display: flex;
  gap: 24px;
  background-color: white;
}

.book-card {
  width: 200px;
  height: 250px;
  display: flex;
  border-radius: 4px;
  transition: transform 0.2s, box-shadow 0.2s;
  cursor: pointer;
  color: black;
  background-color: #c8bbb8;
  font-family: "Lato";
  text-decoration: none;
  align-items: center;
  justify-content: center; 
}

.book-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.center-text {
  display: flex;
  align-items: center;
  justify-content: center; 
}
.center-text-p {
  display: flex;
  align-items: center;
  justify-content: center; 
  font-weight: 700;
}
</style>
</head>

<body>
<?php include '../common/nav.php'; ?> 
  <!-- logo and name at the top
 <header class="top-bar">
    <div style="width:200px">
        < ?php
          echo "<span class='welcome'>" . $_SESSION['fname'] ." " . $_SESSION['lname'] . "</span>";
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
<main class="content">

<div class="section">
  <p class="section-title">Popular in Your Favorite Genres</p>
  <div class="book-row">
    <?php while($row = $recommendations->fetch_assoc()): ?>
    <a href="book_info.php?id=<?php echo $row['mmsID']; ?>">
      <div class="book-card">
        <div style="padding: 15px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 6px;">
          <p class= "center-text-p"><?php echo htmlspecialchars($row['Title']); ?></p>
          <div class= "center-text" style="font-size: 0.85rem; font-style: italic;">
            by <?php echo htmlspecialchars($row['authorName']); ?>
    </div>
        </div>
      </div>
    </a>
    <?php endwhile; ?>
  </div>
</div>

</main>

</body>
</html>