<?php
session_start();
include 'includes/header.php';
include 'classes/Database.php';

if (isset($_SESSION['isloggedin']) && $_SESSION['isloggedin'] === true) {
    $user_id = $_SESSION['id'];

    $conn = new mysqli("localhost", "root", "", "movie_website");





    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT name, surname FROM user WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $firstName = $row['name'];
            $lastName = $row['surname'];

            echo '<section class="my-5">';
            echo '<div class="container">';
            echo '<div class="row">';
            echo '<div class="col-12">';
            echo "<h3>Welcome to your profile, $firstName $lastName</h3>";
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</section>';
        } else {
            echo "User not found";
        }
    } else {
        echo "Error: " . $conn->error;
    }
}


include 'includes/footer.php';
?>
