<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];

// Handle update
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $parts = explode(' ', trim($name), 2);
    $fName = $parts[0];
    $lName = isset($parts[1]) ? $parts[1] : '';
    $email = $_POST['email'];

    $updateUser = $conn->prepare("UPDATE users SET FName = ?, LName = ?, Email = ? WHERE userID = ?");
    $updateUser->bind_param("sssi", $fName, $lName, $email, $current_user_id);
    $updateUser->execute();

    $_SESSION['fname'] = $fName;
    $_SESSION['lname'] = $lName;
    $_SESSION['email'] = $email;

    // delete old prefer data
    $deletePrefer = $conn->prepare("DELETE FROM prefers WHERE userID = ?");
    $deletePrefer->bind_param("i", $current_user_id);
    $deletePrefer->execute();

    // insert new prefer data
    $newGenres = [$_POST['genre1'], $_POST['genre2'], $_POST['genre3']];

    if (count(array_unique($newGenres)) < 3) {
    echo "<p style='color:red; text-align:center;'>Please select 3 different genres.</p>";
    exit();
}

    foreach($newGenres as $gName) {
        $getGid = $conn->prepare("SELECT genreID FROM genres WHERE genreName = ?");
        $getGid->bind_param("s", $gName);
        $getGid->execute();
        $result = $getGid->get_result();

        if ($row = $result->fetch_assoc()) {
            $gid = $row['genreID'];
            $insert = $conn->prepare("INSERT INTO prefers (userID, genreID) VALUES (?, ?)");
            $insert->bind_param("ii", $current_user_id, $gid);
            $insert->execute();
        }
    }
    $_SESSION['success'] = "Account and Preferences updated!";
    header("Location: user_account.php");
    exit();
}

// Fetch and display personal data
$statement = $conn->prepare("SELECT FName, LName, Email FROM users WHERE userID = ?");
$statement->bind_param("i", $current_user_id);
$statement->execute();
$user = $statement->get_result()->fetch_assoc();

$firstName = $user['FName'];
$lastName = $user['LName'];
$email = $user['Email'];

// Fetch and display prefered genre
$genreStatement = $conn->prepare("SELECT g.genreName FROM genres g JOIN prefers p ON g.genreID = p.genreID WHERE p.userID = ?");
$genreStatement->bind_param("i", $current_user_id);
$genreStatement->execute();
$preferGenres = $genreStatement->get_result()->fetch_all(MYSQLI_ASSOC);

$genre1 = isset($preferGenres[0]) ? $preferGenres[0]['genreName'] : '';
$genre2 = isset($preferGenres[1]) ? $preferGenres[1]['genreName'] : '';
$genre3 = isset($preferGenres[2]) ? $preferGenres[2]['genreName'] : '';

if (isset($_POST['delete_acc'])) {
    // Delete related data

    // fix: no longer draw current user id from session, in case of manipulated session
    $stmt = $conn->prepare("DELETE FROM book_log WHERE performedBy = ?");
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM is_recommended WHERE userID = ?");
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM users WHERE userID = ?");
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    
    // Delete user
    $conn->query("DELETE FROM users WHERE userID = $current_user_id");

    // Destroy session (user no longer exists)
    session_destroy();

    // Redirect to main page
    header("Location: mainPage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Account | The Lit Kit</title>

 <!--links for fonts and style sheet-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Junge&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../../css/style.css">
    <!---->
</head>

<body>
<?php include '../common/nav.php'; ?> 
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
<!-- MAIN -->
<main class="acct-main">
<div class="card-background-acct">
<form action="update_account.php" method="POST">

    <div class="div-border-long">

    <!-- ACCOUNT DETAILS -->
    <h1>Update Account</h1>
    <div class="form-text">
    <label for="name">Name</label>
    <input type="text" name="name" id="name"
           value="<?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>" required>

    <label for="email">Email</label>
    <input type="text" name="email" id="email"
           value="<?php echo htmlspecialchars($email); ?>" required>
</div>
    <!-- PREFERENCES -->
    <h1>Update Preferences</h1>
    <h3>Your top 3 genres</h3>
<div class="form-text">
    <?php 
        $all_genres = ["Journalism", "Film & Psychology", "Feminist Literature", "Media Theory", "Philosophy", "History"];

        for ($i = 1; $i <= 3; $i++) {
            $current_val = ${"genre" . $i};

            echo "<label>Choice $i</label>";
            echo "<select id='genre$i' name='genre$i' required>";

            echo "<option value='' disabled " . ($current_val ? "" : "selected") . ">Select a genre</option>";

            foreach ($all_genres as $option) {
                $selected = ($option == $current_val) ? "selected" : "";
                echo "<option value='$option' $selected>$option</option>";
            }

            echo "</select>";
        }
    ?>
    </div>

    <!-- UPDATE BUTTON -->
    <div class="div-button">
        <button type="submit" name="update">Update Account</button>
    </div>
</div>
</div>

</form>
</div>
</main>
<div class="card-background-acct">
<!-- DELETE BOX -->
<div class="div-border-delete delete-box">

    <h1>Delete Account</h1>

    <p class="delete-text">
        This action is permanent and cannot be undone.
    </p>

    <form method="POST" onsubmit="return confirm('Are you sure? This is permanent.');">
        <div class="div-button">
            <button type="submit" name="delete_acc" class="delete-button">
                Delete Account
            </button>
        </div>
    </form>
</div>
</div>

<script>
const selects = [
    document.getElementById('genre1'),
    document.getElementById('genre2'),
    document.getElementById('genre3')
];

function updateDropdowns() {
    const selectedValues = selects.map(s => s.value).filter(v => v !== "");

    selects.forEach(select => {
        Array.from(select.options).forEach(option => {
            option.disabled = false;

            if (
                option.value !== "" &&
                option.value !== select.value &&
                selectedValues.includes(option.value)
            ) {
                option.disabled = true;
            }
        });
    });
}

selects.forEach(select => {
    select.addEventListener('change', updateDropdowns);
});

updateDropdowns();
</script>

</body>
</html>