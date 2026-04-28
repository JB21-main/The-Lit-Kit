<?php
session_start();
require_once 'admin_check.php';
require_once '../user/db_connect.php';

$admin_id = $_SESSION['user_id'];

// Form submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_fname = $_POST['FName'];
    $new_lname = $_POST['LName'];
    $new_email = $_POST['Email'];

    $update_sql = "UPDATE users SET FName = ?, LName = ?, Email = ? WHERE userID = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $new_fname, $new_lname, $new_email, $admin_id);

    if($stmt->execute()) {
        $_SESSION['FName'] = $new_fname;
        $_SESSION['LName'] = $new_lname;
        $_SESSION['email'] = $new_email;

        header("Location: admin_account.php?msg=success");
        exit();
    } else {
        $error = "Could not update account";
    }
}

// Pre-fill the form with current data
$query = "SELECT FName, LName, Email, Role FROM users WHERE userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin_data = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>The Lit Kit</title>
    <!--links for fonts and style sheet-->
    <!--links for fonts and style sheet-->
    <link rel="stylesheet" href="/CS4347DatabaseProject/cs4347Project/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Junge&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet"/>
    <script src="https://kit.fontawesome.com/c00cc4f5f4.js" crossorigin="anonymous"></script>
    <!---->
    <!---->
    
</head>

<body>
<?php include '../common/nav.php'; ?> 
<!-- HEADER 
<header class="top-bar">
    <div style="width:200px;"></div>

    <span class="logo-text">The Lit Kit</span>

    <div style="width:200px; text-align:right;">
        <a href="/CS4347DatabaseProject/cs4347Project/phpFiles/user/logout.php" class="sign-in">Logout</a>
    </div>
</header>


<nav>
    <a href="adminMainPage.php">Home</a>
    <a href="manage_books.php">Books Inventory</a>
    <a href="admin_account.php">Account</a>
</nav>
-->
<div class="card-background">
    

    <!--div for editing account details-->
    <form action="update_admin.php" method = "POST" class="div-border">
        <h1>Edit Account Details</h1>
        <div class="form-text">
        <!--Replace placeholders with user name, email, pref etc once connected to db-->
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <label for="FName">First Name:</label>
        <input type="text" name="FName"  id="FName" value="<?php echo htmlspecialchars($admin_data['FName']);?>" required>
        
        <label for="FName">Last Name:</label>
        <input type="text" name="LName"  id="LName" value="<?php echo htmlspecialchars($admin_data['LName']);?>" required>

        <label for="email">Email Address:</label>
        <input type="text" name = "Email"  id="Email" value="<?php echo htmlspecialchars($admin_data['Email']);?>" required>
    
</div>
            <div class="div-button">
                <button type = "submit">Update Account</button>
            </div>
    </form>

</div>
    
</body>
<footer></footer>
</html> 