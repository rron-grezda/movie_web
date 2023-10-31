<?php 
include 'includes/header.php'; 
include 'classes/Database.php';


if(isset($_SESSION['isloggedin']) && $_SESSION['isloggedin'] == true) {
    header('Location: index.php?user_id='.$_SESSION['user_id']);
}

$errors = [];

if(isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = 'Email is not valid!';
    }

    if(empty($password) || strlen($password) < 6) {
        $errors[] = "Please enter password (at least 6 characters)!";
    }

    if(count($errors) == 0) {
        $stm = $pdo->prepare('SELECT * FROM  `user` WHERE `email` = ?');
        $stm->execute([$email]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if($user == false) {
            $errors[] = 'User does not exist!';
        } else {
            if(password_verify($password, $user['password'])) {
                $id = $user['user_id'];
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
                $_SESSION['isloggedin'] = true;
                header('Location: profile.php?id='.$id);
            } else {
                $errors[] = 'Password is incorrect!';
            }
        }
    }
}

?>


<section class="my-5">
    <div class="container">
        <div class="row">
            <div class="col-5">
                <img src="./assets/img/movie_time.png" alt="Movie Flixer Illustration" />
            </div>
            <div class="col-6 offset-1 d-flex align-items-center">
                <div class="card w-75 mx-auto">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Login</h5>
                        <?php if(count($errors)): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?php foreach($errors as $error): ?>
                                    <p class="p-0 m-0"><?= $error ?>
                                <?php endforeach; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <div class="card-text">
                            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                                <div class="form-group mb-4">
                                    <input type="email" name="email" required class="form-control" placeholder="Enter email" />
                                </div>
                                <div class="form-group mb-4">
                                    <input type="password" name="password" required class="form-control" placeholder="Enter password" />
                                </div>
                                <button type="submit" name="login_btn" class="btn btn-sm btn-outline-primary">Login</button>
                                <a href="register.php" class="btn btn-sm btn-link">Register</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>