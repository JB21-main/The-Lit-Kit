<<<<<<< HEAD:cs4347ProjectFrontEnd/phpFiles/db_connect.php
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cs4347project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}
=======
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cs4347project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}
>>>>>>> origin/main:cs4347Project/phpFiles/db_connect.php
?>