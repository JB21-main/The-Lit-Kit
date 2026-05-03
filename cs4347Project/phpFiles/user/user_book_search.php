<?php
require_once '../user/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit();
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Junge&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="https://kit.fontawesome.com/c00cc4f5f4.js" crossorigin="anonymous"></script>
    <!---->
    
</head>
<body>

  <?php include '../common/nav.php'; ?> 
  
  <main>
  <div class="centered-body">
    <div class="table-container">
        <div class="horizontal-container">
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

</main>
</body>
</html>