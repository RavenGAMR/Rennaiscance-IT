<?php
require_once __DIR__ . '/auth.php';
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $u = $_POST['username'] ?? $_POST['email'] ?? '';
    $p = $_POST['password'] ?? '';
    $return = $_POST['return'] ?? $_GET['return'] ?? 'Index.php';
    $return_decoded = urldecode($return);
    // make relative return site-relative if needed
    if(!preg_match('#^https?://#i', $return_decoded) && strlen($return_decoded) && $return_decoded[0] !== '/'){
        $base = rtrim(dirname($_SERVER['REQUEST_URI']), '/');
        $return_location = $base . '/' . ltrim($return_decoded, '/');
    } else {
        $return_location = $return_decoded ?: 'Index.php';
    }
    if(loginUser($u,$p)){
        $user = currentUser();
        if($user && $user['role'] === 'admin'){
            header('Location: Admin.php');
            exit;
        }
        header('Location: ' . $return_location);
        exit;
    }
    $error = 'Invalid credentials';
}
?>
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
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form action="login.php" method="post">
                            <input type="hidden" name="return" value="<?php echo htmlspecialchars($_GET['return'] ?? 'Index.php'); ?>">
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
                        <p class="text-center mt-4 mb-0">Nog geen account? <a href="register.php">Registreer hier</a></p>
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

<?php include 'navbar.php'; ?>

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
                            <input type="hidden" name="return" value="<?php echo htmlspecialchars($_GET['return'] ?? 'Index.php'); ?>">
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
