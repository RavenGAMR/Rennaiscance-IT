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
// Simple markdown renderer for the placeholders (supports # headings, lists, paragraphs and images).
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
    // Images: ![alt](src)
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

// Only use markdown fallback when the DB content is empty
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
          // Remove any <img> tags from content so the page shows only the main article image above
          $content = $article['content'] ?? '';
          $content_without_imgs = preg_replace('/<img[^>]*>/i', '', $content);
          echo $content_without_imgs;
        ?>
        <?php if(function_exists('currentUser') && ($u = currentUser()) && $u['role'] === 'admin'): ?>
          <div class="mt-4">
            <a href="Admin.php?helpdesk_action=edit&hid=<?php echo $article['id']; ?>" class="btn btn-sm btn-outline-primary">Bewerk artikel</a>
            <a href="Admin.php" class="btn btn-sm btn-success">Nieuw artikel</a>
          </div>
        <?php endif; ?>
      </div>
    </article>
    <p class="mt-4"><a href="Helpdesk.php">&larr; Terug naar Helpdesk</a></p>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>