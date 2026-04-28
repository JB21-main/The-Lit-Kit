<?php
require_once '../user/db_connect.php';
require_once 'admin_check.php';

# Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->begin_transaction();
    try {
        $stmt1 = $conn->prepare("DELETE FROM book_genre WHERE mmsID = ?");
        $stmt1->bind_param("s", $delete_id);
        $stmt1->execute();

        $stmt2 = $conn->prepare("DELETE FROM books WHERE mmsID = ?");
        $stmt2->bind_param("s", $delete_id);
        $stmt2->execute();
        
        $conn->commit();
        $msg = "Book deleted successfully";
        header("Location: manage_books.php?msg=deleted");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $msg = "Error deleting book";
    }
}
$books = $conn->query("SELECT b.mmsID, b.Title, a.authorName, 
                        GROUP_CONCAT(g.genreName SEPARATOR ', ') as genreList
                        FROM books b 
                        JOIN author a ON b.authorID = a.authorID
                        LEFT JOIN book_genre bg ON b.mmsID = bg.mmsID
                        LEFT JOIN genres g ON g.genreID = bg.genreID
                        GROUP BY b.mmsID, b.Title, a.authorName");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
<body class="centered-background">
<!--
  
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
                echo "<a href='/CS4347DatabaseProject/cs4347Project/phpFiles/user/logout.php' class='sign-in'>Logout</a>";
            }
        ?>
    </div>
</header>

  <nav>
    <a href="adminMainPage.php">Home</a>
    <a href="manage_books.php">Books Inventory</a>
    <a href="admin_account.php">Account</a>
  </nav>
  -->
  <?php include '../common/nav.php'; ?> 
  <div class="centered-body">
    <div class="table-container">
        <div class="horizontal-container">
        <a href="add-book.php"><button type="button">Add Book</button></a>
        <div class="search-row">

          <input class="search-input" type="text" id="book-search" placeholder="Search by title, id, or author..">
          <button>Search</button>
        </div>
    </div>
      <table class="book-table">
        <thead>
          <tr>
            <th>MMSID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Add Copy</th>
            <th>Edit</th>
            <th>Delete</th>
            <th>Copies</th>
          </tr>
        </thead>

        <tbody>
          <?php while($row = $books->fetch_assoc()): ?>
          
            <tr>
              <td><?= $row['mmsID']; ?></td>
              <td><?= htmlspecialchars($row['Title']); ?></td>
              <td><?= htmlspecialchars($row['authorName']); ?></td>
              <td><?= htmlspecialchars($row['genreList']); ?></td>
              <td class= "centered-td">
                <a class="table-icon-a" href="add-copy.php?id=<?= $row['mmsID']; ?>"><i class="fa-solid fa-plus"></i></a>
              </td>
              <td class= "centered-td">
                <a class="table-icon-a" href="edit-book.php?id=<?= $row['mmsID']; ?>"><i class="fa-solid fa-pen"></i></a>
              </td>
              <td class= "centered-td">
                  <a href="?delete_id=<?= $row['mmsID']; ?>" onclick="return confirm('Are you sure?')">
                  <button class="delete-td" type="button"><i class="fa-solid fa-trash"></i></button>
                  </a>
              </td>
              <td class= "centered-td">
                <a class="table-icon-a" onclick="toggleCopies('copies-<?= $row['mmsID'] ?>')" style="cursor:pointer;">
                  <i class="fa-solid fa-chevron-down"></i>
                </a>
              </td>
            </tr>
          
          <!-- copies for each row of books- hidden until toggleCopies -->
          <tr id="copies-<?= $row['mmsID'] ?>" style="display:none;">
            <td colspan="8">
              <table class="copies-table">
                <thead>
                  <tr>
                    <th>Barcode</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $mmsID = $row['mmsID'];
                    $copyStmt = $conn->prepare("SELECT Barcode, status FROM books_copy WHERE mmsID = ?");
                    $copyStmt->bind_param("s", $mmsID);
                    $copyStmt->execute();
                    $copies = $copyStmt->get_result();

                    if ($copies->num_rows === 0):
                  ?>
                    <tr><td colspan="2">No copies available.</td></tr>
                  <?php else: ?>
                    <?php while($copy = $copies->fetch_assoc()): ?>
                      <tr>
                        <td><?= htmlspecialchars($copy['Barcode']) ?></td>
                        <td><?= htmlspecialchars($copy['status']) ?></td>
                      </tr>
                    <?php endwhile; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </td>
          </tr>
          <?php endwhile; ?>
          
        </tbody>
      </table>
      

    </div>
  </div>
  <script>
function toggleCopies(id) {
    const row = document.getElementById(id);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}
</script>

</body>
</html>