<?php
// Single template for weblogs. Use ?id=N to select article.
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
require_once __DIR__ . '/db.php';
$pdo = getPDO();
$article = null;
try {
    $stmt = $pdo->prepare('SELECT id,title,content,image,category FROM weblogs WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if($row) $article = $row;
} catch (Exception $e) {
    $article = null;
}

if(!$article){
    header('Location: Weblog.php');
    exit;
}

require_once __DIR__ . '/auth.php';

// markdown fallback
$mdPath = __DIR__ . '/content/weblog/' . $id . '.md';
function render_markdown($md) {
  $lines = preg_split("/\r\n|\n|\r/", $md);
  $html = '';
  $inList = false;
  foreach ($lines as $line) {
    if (preg_match('/^# (.+)/', $line, $m)) {
      if ($inList) { $html .= "</ul>\n"; $inList = false; }
      $html .= '<h1>' . htmlspecialchars($m[1]) . '</h1>' . "\n";
      continue;
    }
    if (preg_match('/^## (.+)/', $line, $m)) {
      if ($inList) { $html .= "</ul>\n"; $inList = false; }
      $html .= '<h2>' . htmlspecialchars($m[1]) . '</h2>' . "\n";
      continue;
    }
    if (preg_match('/^- (.+)/', $line, $m)) {
      if (!$inList) { $inList = true; $html .= "<ul>\n"; }
      $html .= '<li>' . htmlspecialchars($m[1]) . '</li>' . "\n";
      continue;
    }
    if (preg_match('/!\[(.*?)\]\((.*?)\)/', $line, $m)) {
      if ($inList) { $html .= "</ul>\n"; $inList = false; }
      $alt = htmlspecialchars($m[1]);
      $src = htmlspecialchars($m[2]);
      $html .= '<p><img src="' . $src . '" alt="' . $alt . '" class="img-fluid rounded-3 my-3" /></p>' . "\n";
      continue;
    }
    if (trim($line) === '') {
      if ($inList) { $html .= "</ul>\n"; $inList = false; }
      $html .= "\n";
      continue;
    }
    if ($inList) { $html .= "</ul>\n"; $inList = false; }
    $html .= '<p>' . htmlspecialchars($line) . '</p>' . "\n";
  }
  if ($inList) { $html .= "</ul>\n"; }
  return $html;
}

if (file_exists($mdPath) && empty(trim($article['content'] ?? ''))) {
  $md = file_get_contents($mdPath);
  $article['content'] = '<div class="article-content">' . render_markdown($md) . '</div>';
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
          $content_without_imgs = preg_replace('/<img[^>]*>/i', '', $content);
          echo $content_without_imgs;
        ?>
        <?php if(function_exists('currentUser') && ($u = currentUser()) && $u['role'] === 'admin'): ?>
          <div class="mt-4">
            <a href="Admin.php?weblog_action=edit&wid=<?php echo $article['id']; ?>" class="btn btn-sm btn-outline-primary">Bewerk artikel</a>
            <a href="Admin.php" class="btn btn-sm btn-success">Nieuw artikel</a>
          </div>
        <?php endif; ?>
      </div>
    </article>
    <p class="mt-4"><a href="Weblog.php">&larr; Terug naar Weblog</a></p>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>
