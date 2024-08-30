<?php
session_start();
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == 'admin' && $password == 'admin123') {
        $_SESSION['loggedin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $login_error = 'Invalid username or password';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Coffee shop Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #6f4e37;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
    </style>
</head>
<body>

<div class="container mt-4">

<div class="row justify-content-center">
    <div class="col-md-4">
        <h2 class="text-center">Login</h2>
        <form method="post" class="form-signin">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-lg btn-primary btn-block">Login</button>
        </form>
        <?php if ($login_error): ?>
            <div class="alert alert-danger mt-3"><?= $login_error ?></div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
