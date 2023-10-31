<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'classes/Database.php';

$db = new Database('localhost', 'movie_website', 'utf8mb4', 'root', '');
$conn = $db->connect();

if (isset($_POST['watch_now'])) {
    if (isset($_POST['movie_id'])) {
        $movie_id = $_POST['movie_id'];

        $user_id = 1;

        $sql = "INSERT INTO watchlist (user_id, movie_id) VALUES (:user_id, :movie_id)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "Movie added to your watchlist successfully!";
            } else {
                echo "Error adding the movie to your watchlist.";
            }
        } else {
            echo "Error preparing the statement.";
        }
    } else {
        echo "Movie ID not specified.";
    }
} else {
    echo "Watch now button not pressed.";
}
?>
