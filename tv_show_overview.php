<?php include 'includes/header.php'; ?>

<style>
    .container {
        display: flex;
        align-items: center;
    }

    .poster-container {
        margin-right: 150px;
    }

    .tv-show-poster {
        max-width: 400px;
        max-height: 600px;
        margin-top: 90px;
    }

    .tv-show-data {
        flex: 1;
    }

    .watch_now{
        margin: 0 10px;
        background-color: #006bb3;
        border: none;
        color: white;
    }
</style>

<?php
$api_key = 'bb695faf91868acce90515f951cb5548';

if (isset($_GET['tv_show_id'])) {
    $tv_show_id = $_GET['tv_show_id'];
    $tv_show_url = "https://api.themoviedb.org/3/tv/$tv_show_id?api_key=$api_key";
    $tv_show_data = json_decode(file_get_contents($tv_show_url), true);


if ($tv_show_data) {
    $name = $tv_show_data['name'];

    $netflixLink = 'https://www.netflix.com/title/' . $tv_show_id;
    $amazonPrimeLink = 'https://www.amazon.com/Prime-Video/' . $tv_show_id;

    echo '<h1><label>Title:   ' . $name . ' </label></h1>';
    echo '<h2><i>Watch on Streaming Services:</i></h2>';
    echo '<ul>';
    echo '<li><a href="' . $netflixLink . '" target="_blank">Watch on Netflix</a></li>';
    echo '<li><a href="' . $amazonPrimeLink . '" target="_blank">Watch on Amazon Prime Video</a></li>';
    echo '</ul>';
    echo '<button class="watch_now" type="submit">Watch Now</button>';
}


    if ($tv_show_data) {
        echo '<div class="container">';

        echo '<div class="poster-container">';
        if ($tv_show_data['poster_path']) {
            $posterUrl = 'https://image.tmdb.org/t/p/w500' . $tv_show_data['poster_path'];
            echo '<img class="tv-show-poster" src="' . $posterUrl . '" alt="TV Show Poster">';
        }
        echo '</div>';

        echo '<div class="tv-show-data">';
        echo '<h1>' . $tv_show_data['name'] . '</h1>';
        echo '<p>Overview: ' . $tv_show_data['overview'] . '</p>';
        echo '<p>First Air Date: ' . $tv_show_data['first_air_date'] . '</p>';
        echo '</div>';

        echo '</div>';
    } else {
        echo "Error: Unable to retrieve TV show data for TV show ID $tv_show_id.";
    }
} else {
    echo "Error: TV show ID not provided.";
}
?>

<?php include 'includes/footer.php'; ?>





