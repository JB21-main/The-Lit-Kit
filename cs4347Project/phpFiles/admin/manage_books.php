<?php
require_once '../user/db_connect.php';
require_once 'admin_check.php';

# Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->begin_transaction();
    try {
        $stmt1 = $conn->prepare("DELETE FROM book_genre WHERE mmsID = ?");
        $stmt1->bind_param("i", $delete_id);
        $stmt1->execute();

        $stmt2 = $conn->prepare("DELETE FROM books WHERE mmsID = ?");
        $stmt2->bind_param("i", $delete_id);
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
                        JOIN book_genre bg ON b.mmsID = bg.mmsID
                        JOIN genres g ON g.genreID = bg.genreID
                        GROUP BY b.mmsID, b.Title, a.authorName");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Books</title>
</head>
<body>
    <!--div with title and search bar TODO: move search bar to right-->
    <div>
        <h1>Manage Books:</h1>
        <input type="text" id="book-search" placeholder="Search by title, id, or author..">
    </div>
    <!--list of books- has placeholders for now-->
    <div class="book-list-container">
        <table class="book-table">
            <thead>
                <tr>
                    <th>MMSID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $books->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['mmsID']; ?></td>
                    <td><?php echo htmlspecialchars($row['Title']); ?></td>
                    <td><?php echo htmlspecialchars($row['authorName']); ?></td>
                    <td><?php echo htmlspecialchars($row['genreList']); ?></td>
                     <td>
                        <a href="edit_book.php?id=<?php echo $row['mmsID']; ?>">Edit</a> | 
                        <a href="?delete_id=<?php echo $row['mmsID']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Are you sure?')">Delete</a>
                     </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
</body>
</html>