<?php include 'includes/header.php'; 
      include 'classes/Database.php';  
?>

<style>
    .container {
        display: flex;
        align-items: center;
    }

    .poster-container {
        margin-right: 150px;
    }

    .movie-poster {
        max-width: 400px;
        max-height: 600px;
        margin-top: 90px;
    }

    .movie-data {
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
if (isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];
} else {
    echo "Error: Movie ID not specified.";
    exit;
}

$api_key = 'bb695faf91868acce90515f951cb5548';

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.themoviedb.org/3/movie/$movie_id?api_key=$api_key",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);


$movie_url = "https://api.themoviedb.org/3/movie/$movie_id?api_key=$api_key";
$movie_data = json_decode(file_get_contents($movie_url), true);

if ($movie_data) {
    $title = $movie_data['title'];

    $netflixLink = 'https://www.netflix.com/title/' . $movie_id;
    $amazonPrimeLink = 'https://www.amazon.com/Prime-Video/' . $movie_id;

    echo '<h1><label>Title:   ' . $title . ' </label></h1>';
    echo '<h2><i>Watch on Streaming Services:</i></h2>';
    echo '<ul>';
    echo '<li><a href="' . $netflixLink . '" target="_blank">Watch on Netflix</a></li>';
    echo '<li><a href="' . $amazonPrimeLink . '" target="_blank">Watch on Amazon Prime Video</a></li>';
    echo '</ul>';

    // Add a form with the "Watch Now" button
    echo '<form method="post" action="add_to_watchlist.php">';
    echo '<input type="hidden" name="movie_id" value="' . $movie_id . '">';
    echo '<button class="watch_now" type="submit" name="watch_now">Watch Now</button>';
    echo '</form>';
}

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $movieDetails = json_decode($response, true);

    if ($movieDetails) {
        echo '<div class="container">';
        echo '<div class="poster-container">';
        if ($movieDetails['poster_path']) {
            $posterUrl = 'https://image.tmdb.org/t/p/w400' . $movieDetails['poster_path'];
            echo '<img class="movie-poster" src="' . $posterUrl . '" alt="Movie Poster">';
        }
        echo '</div>';
        
        echo '<div class="movie-data">';
        echo '<h1>' . $movieDetails['title'] . '</h1>';
        echo '<p>Overview: ' . $movieDetails['overview'] . '</p>';
        echo '<p>Release Date: ' . $movieDetails['release_date'] . '</p>';
        echo '</div>';
        
        echo '</div>';
    } else {
        echo "Error: Unable to retrieve movie details.";
    }
}
?>
<?php include 'includes/footer.php'; ?>
