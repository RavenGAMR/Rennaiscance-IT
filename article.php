<?php
require_once __DIR__ . '/auth.php';
requireLogin();
$user = currentUser();
if(empty($_GET['slug'])){
    header('Location: diensten.php');
    exit;
}
$slug = $_GET['slug'];
if(!userHasPurchase($user['id'], 'weblog_access')){
    // redirect user to purchase page and pass return URL
    $return = 'article.php?slug=' . urlencode($slug);
    // pass product=weblog_access so purchase page knows what to buy
    header('Location: purchase.php?product=' . urlencode('weblog_access') . '&return=' . urlencode($return));
    exit;
}
$pdo = getPDO();
$stmt = $pdo->prepare('SELECT title, content FROM articles WHERE slug = ? LIMIT 1');
$stmt->execute([$slug]);
$article = $stmt->fetch();
if(!$article){
    http_response_code(404);
    echo 'Article not found';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($article['title']); ?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="Style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <a href="diensten.php" class="btn btn-secondary btn-sm mb-3">Terug</a>
                        <h1 class="h3 mb-3"><?php echo htmlspecialchars($article['title']); ?></h1>
                        <div class="article-content"><?php echo nl2br(htmlspecialchars($article['content'])); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="page-footer text-center text-white py-4">
        <div class="container">
            <ul class="footer-links mb-0">
                <li><a href="diensten.php">Diensten</a></li>
                <li><a href="Verwerkingsovereenkomst.php">Verwerkingsovereenkomst</a></li>
                <li><a href="Privacypolicy.php">Privacypolicy</a></li>
                <li><a href="Contact.php">Contact</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>
