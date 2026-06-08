<?php
require_once __DIR__ . '/auth.php';
requireLogin();
$user = currentUser();
if(empty($_GET['slug'])){
    header('Location: Weblog.php');
    exit;
}
$slug = $_GET['slug'];
if(!userHasPurchase($user['id'], 'weblog_access')){
    // redirect user to purchase page and pass return URL
    $return = 'article.php?slug=' . urlencode($slug);
    header('Location: purchase.php?return=' . urlencode($return));
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
  <link rel="stylesheet" href="Style.css">
</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="Index.php">Rennaiscance</a>
        </div>
    </nav>
    <main class="container py-5">
        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
        <div><?php echo nl2br(htmlspecialchars($article['content'])); ?></div>
    </main>
</body>
</html>
