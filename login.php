<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rennaiscance IT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link rel="stylesheet" href="Style.css">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MrcW6ZMFY9F/2b5JgCFjR2NZ7cp0Fc0j7l+H/XgGiJQmQtKf7E1k3rW7r3ZQ5DGa" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                <div class="card sha    dow-sm border-0">
                    <div class="card-body p-4">
                        <h2 class="h4 text-center mb-4">Inloggen</h2>
                        <form action="login.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="jouw@email.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Wachtwoord</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Wachtwoord" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember">
                                    <label class="form-check-label" for="remember">Onthoud mij</label>
                                </div>
                                <a href="Contact.php" class="small">Wachtwoord vergeten?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Inloggen</button>
                        </form>
                        <p class="text-center mt-4 mb-0">Nog geen account? <a href="Contact.php">Neem contact op</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeo60tc0tS/xq7Q7crHsaHpdfj7xj4nQ3C5L5R4p5k2zd4hD" crossorigin="anonymous"></script>

    <footer class="page-footer text-center text-white py-4">
        <div class="container">
            <p class="mb-3"></p>
            <ul class="footer-links mb-0">
                <li><a href="Weblog.php">Weblog</a></li>
                <li><a href="Verwerkingsovereenkomst.php">Verwerkingsovereenkomst</a></li>
                <li><a href="Privacypolicy.php">Privacypolicy</a></li>
                <li><a href="Contact.php">Contact</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>
<?php
require_once __DIR__ . '/auth.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $u = $_POST['username'] ?? $_POST['email'] ?? '';
    $p = $_POST['password'] ?? '';
    if(loginUser($u,$p)){
        $user = currentUser();
        if($user && $user['role'] === 'admin'){
            header('Location: Admin.php');
            exit;
        }
        header('Location: Index.php');
        exit;
    }
    $error = 'Invalid credentials';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link rel="stylesheet" href="Style.css">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>

    <nav class="navbar navbar-expand-sm navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="Index.php">Rennaiscance</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarID"
                aria-controls="navbarID" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarID">
                <div class="navbar-nav ms-auto">
                    <?php $u = currentUser(); ?>
                    <a class="nav-link" href="Index.php">Home</a>
                    <a class="nav-link" href="Weblog.php">Weblog</a>
                    <a class="nav-link" href="Helpdesk.php">Helpdesk</a>
                    <a class="nav-link" href="Contact.php">Contact</a>
                    <?php if($u): ?>
                        <?php if($u['role'] === 'admin'): ?>
                            <a class="nav-link" href="Admin.php">Admin</a>
                        <?php endif; ?>
                        <a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($u['username']); ?>)</a>
                    <?php else: ?>
                        <a class="nav-link active" href="login.php">Login</a>
                        <a class="nav-link" href="register.php">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Login</h3>
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <p class="mt-3 mb-0">Or <a href="register.php">register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
