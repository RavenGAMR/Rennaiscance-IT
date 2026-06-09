<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rennaiscance IT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link rel="stylesheet" href="Style.css">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'navbar.php';
    $pdo = getPDO();
    $articles = [];
    try {
        $stmt = $pdo->query('SELECT id, title, slug, content FROM articles ORDER BY id DESC');
        $articles = $stmt->fetchAll();
    } catch (PDOException $e) {
        $articles = [];
    }
    ?>
    <main class="container py-5">
      <h1 class="mb-4">Weblog Artikelen</h1>
      <?php if (count($articles) === 0): ?>
        <div class="alert alert-info">Er zijn nog geen artikelen beschikbaar.</div>
      <?php else: ?>
        <div class="row g-4">
          <?php foreach ($articles as $article): ?>
            <div class="col-12 col-md-6">
              <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                  <a href="WeblogArticle.php?id=<?php echo (int) $article['id']; ?>" class="stretched-link" style="text-decoration: none;">
                    <h2 class="card-title h5 mb-2"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                  </a>
                  <p class="text-muted mb-0"><strong>Slug:</strong> <?php echo htmlspecialchars($article['slug'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>