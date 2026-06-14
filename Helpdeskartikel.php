<?php
// Single template for helpdesk articles. Use ?id=N to select article.
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Make current user available to show admin actions
try {
  require_once __DIR__ . '/db.php';
  $pdo = getPDO();
  $stmt = $pdo->prepare('SELECT id,title,content,image,category FROM helpdesk_articles WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  if($row){
    $article = $row;
  }
} catch (Exception $e) {
  $article = null;
}

if(!$article){
  // No article found in DB — redirect back to listing
  header('Location: Helpdesk.php');
  exit;
}

// Make current user available to show admin actions
require_once __DIR__ . '/auth.php';

// If a markdown placeholder exists for this article, load it as content.
$mdPath = __DIR__ . '/content/helpdesk/' . $id . '.md';
// Only use markdown fallback when the DB content is empty
if (file_exists($mdPath) && empty(trim($article['content'] ?? ''))) {
  $md = file_get_contents($mdPath);
  $article['content'] = '<div class="article-content">' . $md . '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($article['title']); ?> - Rennaiscance IT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
  <link rel="stylesheet" href="Style.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <main class="container py-5">
    <article class="row">
      <div class="col-12">
        <h1 class="black-heading"><?php echo htmlspecialchars($article['title']); ?></h1>
      </div>
      <div class="col-12 col-md-5 col-lg-4 mb-4">
        <?php $img = htmlspecialchars($article['image'] ?? ''); ?>
        <?php if($img): ?>
          <img src="<?php echo $img; ?>" alt="" class="img-fluid rounded-3" />
        <?php endif; ?>
      </div>
      <div class="col-12 col-md-7 col-lg-8">
        <?php
          $content = $article['content'] ?? '';
          echo $content;
        ?>
        <?php if(function_exists('currentUser') && ($u = currentUser()) && $u['role'] === 'admin'): ?>
          <div class="mt-4">
            <a href="Admin.php?helpdesk_action=edit&hid=<?php echo $article['id']; ?>" class="btn btn-sm btn-outline-primary">Bewerk artikel</a>
            <a href="Admin.php" class="btn btn-sm btn-success">Nieuw artikel</a>
          </div>
        <?php endif; ?>
      </div>
    </article>
    <p class="mt-4"><a href="Helpdesk.php">Terug naar Helpdesk</a></p>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>