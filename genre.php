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

    .sort-genres{
        margin: 15px 0 0 45px;
    }

    .filter {
        margin-left: 15px;
        background-color: #006bb3;
        border: none;
        color: white;
    }
</style>

<?php

include 'includes/header.php';
include 'classes/Database.php';

$api_key = 'bb695faf91868acce90515f951cb5548';
$totalPages = 3;

if (isset($_GET['page'])) {
    $currentPage = max(1, $_GET['page']);
} else {
    $currentPage = 1;
}

$genre_url = "https://api.themoviedb.org/3/genre/movie/list?api_key=$api_key";
$genre_data = json_decode(file_get_contents($genre_url), true);
$genres = [];
if ($genre_data && isset($genre_data['genres'])) {
    $genres = $genre_data['genres'];
}

$selectedGenre = isset($_GET['genre']) ? $_GET['genre'] : 0;

for ($page = $currentPage; $page <= $currentPage; $page++) {
    $genreParam = ($selectedGenre == 0) ? '' : "&with_genres=$selectedGenre";
    $movie_url = "https://api.themoviedb.org/3/discover/movie?api_key=$api_key&page=$page$genreParam";
    $movie_data = json_decode(file_get_contents($movie_url), true);

    if ($movie_data && isset($movie_data['results'])) {
        echo '<form class="sort-genres" method="get">';
        echo '<label for="genre-select">Select a Genre:</label>';
        echo '<select name="genre">';
        echo '<option value="0">All Genres</option>';
        foreach ($genres as $genre) {
            $selected = ($genre['id'] == $selectedGenre) ? 'selected' : '';
            echo '<option value="' . $genre['id'] . '" ' . $selected . '>' . $genre['name'] . '</option>';
        }
        echo '</select>';
        echo '<button class="filter" type="submit">Filter</button>';
        echo '</form>';
        
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
            echo '<button><a href="?page=' . ($currentPage - 1) . '&genre=' . $selectedGenre . '">Previous Page</a></button>';
        }
        if ($page < $totalPages) {
            echo '<button><a href="?page=' . ($currentPage + 1) . '&genre=' . $selectedGenre . '">Next Page</a></button>';
        }
        echo '</div>';
    } else {
        echo "Error: Unable to retrieve movie data for page $page.";
    }
}
?>

<?php include 'includes/footer.php'; ?>
