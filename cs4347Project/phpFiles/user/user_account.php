<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];

// USER INFO
$sql = "SELECT FName, LName, Email FROM users WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$userInfo = $result->fetch_assoc();

// GENRES
$genre_sql = "SELECT g.genreName
              FROM genres g
              JOIN prefers p ON g.genreID = p.genreID
              WHERE p.userID = ?";

$stmt_g = $conn->prepare($genre_sql);
$stmt_g->bind_param("i", $current_user_id);
$stmt_g->execute();
$genre_result = $stmt_g->get_result();

$user_genres = [];
while ($row = $genre_result->fetch_assoc()) {
    $user_genres[] = $row['genreName'];
}

$g1 = $user_genres[0] ?? "None Selected";
$g2 = $user_genres[1] ?? "None Selected";
$g3 = $user_genres[2] ?? "None Selected";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account | The Lit Kit</title>


<!--links for fonts and style sheet-->
    <link rel="stylesheet" href="/CS4347DatabaseProject/cs4347Project/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Junge&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=EB+Garamond&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c00cc4f5f4.js" crossorigin="anonymous"></script>
    <!---->
</head>

<body>

<!-- HEADER 
<header class="top-bar">
    <div style="width:200px;"></div>

    <span class="logo-text">The Lit Kit</span>

    <div style="width:200px; text-align:right;">
        <a href="logout.php" class="sign-in">Logout</a>
    </div>
</header>


 

<nav>
    <a href="mainPage.php">Home</a>
    <a href="book_rec.php">My Books</a>
    <a href="user_account.php">Account</a>
</nav>

-->
<?php include '../common/nav.php'; ?> 

<!-- MAIN CONTENT -->
<main>
<div class="card-background">
    <div class="div-border-long">

        <h1>Account Details</h1>
        <div class="form-text">
        <h3>Name</h3>
        <input type="text"
               value="<?php echo htmlspecialchars($userInfo['FName'] . ' ' . $userInfo['LName']); ?>"
               readonly>

        <h3>Email</h3>
        <input type="text"
               value="<?php echo htmlspecialchars($userInfo['Email']); ?>"
               readonly>

        <h1>Preferences</h1>

        <h3>First Choice</h3>
        <input type="text" value="<?php echo htmlspecialchars($g1); ?>" readonly>

        <h3>Second Choice</h3>
        <input type="text" value="<?php echo htmlspecialchars($g2); ?>" readonly>

        <h3>Third Choice</h3>
        <input type="text" value="<?php echo htmlspecialchars($g3); ?>" readonly>
</div>
        <div class="div-button">
            <a href="update_account.php">
                <button type="button">Edit Account</button>
            </a>
        </div>

    </div>
</div>
</main>

</body>
</html>