<?php
if(session_status(0 === PHP_SESSION_NONE))
    {
        session_start();
    }

$is_admin = isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin';
?>

<header>
        <div class="top-bar"> <!--div for top bar: logo and profile-->
            <div class="top-bar-placeholder"> </div>
            <a class="logo-text" href="#">Logo</a> <!--logo image-->
            <div class="profile-container"> <!--profile dropdown-->
                <button class="profile-button">
                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Account'); ?>
                    <i class="fa-solid fa-angle-down"></i>
                </button>

            </div>
        </div>
        <nav> <!--bottom bar: navigation bar-->
            <?php if ($is_admin):  
                <a class="nav-a" href="mainPage.php">Dashboard</a>
                <a class="nav-a" href="book_rec.php">Manage Books</a>
                <a class="nav-a" href="user_account.php">Account</a>
            <?php else: ?>
                <a class="nav-a" href="adminMainPage.php">Home</a>
                <a class="nav-a" href="manage_books.php">My Books</a>
                <a class="nav-a" href="admin_account.php">Account</a>
            <?php endif; ?>
            
        </nav>

    </header>




