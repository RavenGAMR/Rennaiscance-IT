<?php
// Single template for helpdesk articles. Use ?id=N to select article.
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$articles = [
  1 => [
    'title' => 'Hoe activeer ik in Horde webmail de prullenmand functie?',
    'image' => 'Media/Algemeen/thund.png',
    'content' => '<p>Deze handleiding laat zien hoe je de prullenmandfunctie in Horde webmail activeert, zodat verwijderde berichten niet direct verdwijnen en je eenvoudig berichten kunt terughalen.</p>'
  ],
  2 => [
    'title' => 'FTP verbinding instellen met Cyberduck',
    'image' => 'Media/Algemeen/mail.png',
    'content' => '<p>Cyberduck is een gebruiksvriendelijke FTP-client waarmee je verbinding maakt met een server en bestanden eenvoudig beheert. We leggen uit hoe je een FTP-verbinding instelt en opslaat voor toekomstig gebruik.</p>'
  ],
  3 => [
    'title' => 'E-mail adres instellen Mozilla Thunderbird',
    'image' => 'Media/Algemeen/mail2.webp',
    'content' => '<p>We beschrijven stap voor stap hoe je je e-mailaccount instelt in Mozilla Thunderbird, inclusief de juiste serverinstellingen voor verzenden en ontvangen.</p>'
  ],
  4 => [
    'title' => 'Webmail handleiding (placeholder)',
    'image' => 'Media/Algemeen/webmail.png',
    'content' => '<p>Dit is een tijdelijke placeholder voor artikel 4. Vervang dit met echte inhoud wanneer beschikbaar.</p>'
  ],
  5 => [
    'title' => 'FTP: bestandsbeheer en instellingen (placeholder)',
    'image' => 'Media/Algemeen/eend.webp',
    'content' => '<p>Dit is een tijdelijke placeholder voor artikel 5. Vervang dit met echte inhoud wanneer beschikbaar.</p>'
  ],
  6 => [
    'title' => 'Placeholder Artikel 6',
    'image' => 'Media/Algemeen/thund.png',
    'content' => '<p>Dit is een tijdelijke placeholder voor artikel 6. Vervang dit met echte inhoud wanneer beschikbaar.</p>'
  ]
];

if (!isset($articles[$id])) {
  header('Location: Helpdesk.php');
  exit;
}
$article = $articles[$id];

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

if (file_exists($mdPath)) {
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
        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
      </div>
      <div class="col-12 col-md-5 col-lg-4 mb-4">
        <img src="<?php echo $article['image']; ?>" alt="" class="img-fluid rounded-3" />
      </div>
      <div class="col-12 col-md-7 col-lg-8">
        <?php echo $article['content']; ?>
      </div>
    </article>
    <p class="mt-4"><a href="Helpdesk.php">&larr; Terug naar Helpdesk</a></p>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>