<?php include 'includes/header.php'; ?>

<style>
    .tv-show-card {
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

    .tv-show-poster {
        max-width: 200px;
        max-height: 300px;
    }

    .tv-show-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .tv-show-grid a{
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

    .sort-tv-shows {
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
$api_key = 'bb695faf91868acce90515f951cb5548';
$totalPages = 3;

if (isset($_GET['page'])) {
    $currentPage = max(1, $_GET['page']);
} else {
    $currentPage = 1;
}

$genre_url = "https://api.themoviedb.org/3/genre/tv/list?api_key=$api_key";
$genre_data = json_decode(file_get_contents($genre_url), true);
$genres = [];
if ($genre_data && isset($genre_data['genres'])) {
    $genres = $genre_data['genres'];
}

$selectedGenre = isset($_GET['genre']) ? $_GET['genre'] : 0;

for ($page = $currentPage; $page <= $currentPage; $page++) {
    $genreParam = ($selectedGenre == 0) ? '' : "&with_genres=$selectedGenre";
    $tv_show_url = "https://api.themoviedb.org/3/discover/tv?api_key=$api_key&page=$page$genreParam";
    $tv_show_data = json_decode(file_get_contents($tv_show_url), true);

    if ($tv_show_data && isset($tv_show_data['results'])) {
        echo '<form class="sort-tv-shows" method="get">';
        echo '<label for="genre-select">Select a Genre:</label>';
        echo '<select id="genre-select" name="genre">';
        echo '<option value="0">All Genres</option>';
        foreach ($genres as $genre) {
            $selected = ($genre['id'] == $selectedGenre) ? 'selected' : '';
            echo '<option value="' . $genre['id'] . '" ' . $selected . '>' . $genre['name'] . '</option>';
        }
        echo '</select>';
        echo '<button class="filter" type="submit">Filter</button>';
        echo '</form>';
        
        echo '<div class="tv-show-grid">';
        foreach ($tv_show_data['results'] as $tvShow) {
            echo '<a href="tv_show_overview.php?tv_show_id=' . $tvShow['id'] . '">';
            echo '<div class="tv-show-card">';
            if ($tvShow['poster_path']) {
                $posterUrl = 'https://image.tmdb.org/t/p/w500' . $tvShow['poster_path'];
                echo '<img class="tv-show-poster" src="' . $posterUrl . '" alt="TV Show Poster">';
            }
            echo '<h4>' . $tvShow['name'] . '</h4>';
            echo '<p>First Air Date: ' . $tvShow['first_air_date'] . '</p>';
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
        echo "Error: Unable to retrieve TV show data for page $page.";
    }
}
?>

<?php include 'includes/footer.php'; ?>
