<?php
include 'includes/header.php';
include 'classes/Database.php';
session_start();

$errors = [];

if (isset($_POST['register_btn'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation checks
    if (empty($name)) {
        $errors[] = 'Please enter your name.';
    }
    if (empty($surname)) {
        $errors[] = 'Please enter your surname.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is not valid.';
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Please enter a password (at least 6 characters).';
    }

    if (count($errors) === 0) {
        $stm = $pdo->prepare('INSERT INTO `user` (`name`, `surname`, `email`, `password`) VALUES (?, ?, ?, ?)');


        if ($stm->execute([$name, $surname, $email, password_hash($password, PASSWORD_BCRYPT)])) {
            $id = $pdo->lastInsertId();
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['isloggedin'] = true;
            header('Location: index.php?id=' . $id);
        } else {
            $errorInfo = $stm->errorInfo();
            $errors[] = 'Database error: ' . $errorInfo[2];
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
                        <h5 class="card-title mb-4">Register</h5>
                        <?php if (count($errors)): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?php foreach ($errors as $error): ?>
                                    <p class="p-0 m-0"><?= $error ?></p>
                                <?php endforeach; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <div class="card-text">
                            <form action="register.php" method="post">
                                <div class="form-group mb-4">
                                    <input type="text" name="name" class="form-control" placeholder="Enter name" />
                                </div>
                                <div class="form-group mb-4">
                                    <input type="text" name="surname" class="form-control" placeholder="Enter surname" />
                                </div>
                                <div class="form-group mb-4">
                                    <input type="email" name="email" required class="form-control" placeholder="Enter email" />
                                </div>
                                <div class="form-group mb-4">
                                    <input type="password" name="password" required class="form-control" placeholder="Enter password" />
                                </div>
                                <button name="register_btn" type="submit" class="btn btn-sm btn-outline-primary">Register</button>
                                <a href="login.php" class="btn btn-sm btn-link">Login</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
