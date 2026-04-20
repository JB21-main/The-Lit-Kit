<?php
include 'db_connect.php';

$current_user_id = isset($_GET['id']) ? $_GET['id'] : 123456;

// SQL
$sql = "SELECT FName, LName, Email
        FROM users
        WHERE userID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$userInfo = $result->fetch_assoc();

// Get genre
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

// If no genre found or selected
$g1 = isset($user_genres[0]) ? $user_genres[0] : "None Selected";
$g2 = isset($user_genres[1]) ? $user_genres[1] : "None Selected";
$g3 = isset($user_genres[2]) ? $user_genres[2] : "None Selected";


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Junge&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <title>Account</title>
    
</head>
<body >
    <div class="div-border">
    <h1 >Account Details</h1>
   
    <h3>Name</h3>
    <input type="acct-text" value="<?php echo htmlspecialchars($userInfo['FName'] . ' ' . $userInfo['LName']); ?>" readonly>  <!-- change to actual value, same for below-->
    <h3>Email</h3>
    <input type="acct-text" value="<?php echo htmlspecialchars($userInfo['Email']); ?>" readonly> 

    <h1>Account Details</h1>
    <h3>Your top 3 genres:</h3>
    <div>
        <h3>First Choice:</h3>
        <input type="acct-text" value="<?php echo htmlspecialchars($g1);?>" readonly> 
        <h3>Second Choice:</h3>
        <input type="acct-text" value="<?php echo htmlspecialchars($g2);?>" readonly> 
        <h3>Third Choice:</h3>
        <input type="acct-text" value="<?php echo htmlspecialchars($g3);?>" readonly> 
    </div>
    </div>
    <div class = "div-button">
        <a href="update_account.php?id=<?php echo $current_user_id; ?>"> 
            <button type = "button">Edit Account</button>
        </a>
    </div>
</body>
</html>