<?php include 'includes/header.php'; ?>

<style>
    .movie-card {
        border: 1px solid #006bb3;
        padding: 10px;
        margin: 50px 10px -10px 50px;
        width: 300px;
        height: 450px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
    }

    .movie-poster {
        max-width: 200px;
        max-height: 300px;
    }

    .movie-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .movie-grid a{
        color: inherit;
        text-decoration: none;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin: 20px 0;
    }

    .pagination button {
        margin: 0 10px;
        background-color: #006bb3;
        border: none;
        color: white;
    }

    .pagination a {
        margin: 0 10px;
        color: inherit;
        text-decoration: none;
    }
</style>

<?php
$api_key = 'bb695faf91868acce90515f951cb5548';
$totalPages = 3;

if (isset($_GET['page'])) {
    $currentPage = max(1, $_GET['page']);
} else {
    $currentPage = 1;
}

for ($page = $currentPage; $page <= $currentPage; $page++) {
    $movie_url = "https://api.themoviedb.org/3/trending/movie/day?api_key=$api_key&page=$page";
    $movie_data = json_decode(file_get_contents($movie_url), true);

    if ($movie_data && isset($movie_data['results'])) {
        echo '<div class="movie-grid">';
        foreach ($movie_data['results'] as $movie) {
            echo '<a href="movie_overview.php?movie_id=' . $movie['id'] . '">';
            echo '<div class="movie-card">';
            if ($movie['poster_path']) {
                $posterUrl = 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'];
                echo '<img class="movie-poster" src="' . $posterUrl . '" alt="Movie Poster">';
            }
            echo '<h4>' . $movie['title'] . '</h4>';
            echo '<p>Release Date: ' . $movie['release_date'] . '</p>';
            echo '</div>';
            echo '</a>';
        }
        echo '</div>';
        echo '<br>';
        echo '<div class="pagination">';
        if ($currentPage > 1) {
            echo '<button><a href="?page=' . ($currentPage - 1) . '">Previous Page</a></button>';
        }
        if ($page < $totalPages) {
            echo '<button><a href="?page=' . ($currentPage + 1) . '">Next Page</a></button>';
        }
        echo '</div>';
    } else {
        echo "Error: Unable to retrieve movie data for page $page.";
    }
}
?>

<?php include 'includes/footer.php'; ?>
