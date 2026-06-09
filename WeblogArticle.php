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
    $article = null;
    
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        try {
            $stmt = $pdo->prepare('SELECT id, title, slug, content FROM articles WHERE id = ? LIMIT 1');
            $stmt->execute([(int) $_GET['id']]);
            $article = $stmt->fetch();
        } catch (PDOException $e) {
            // Error fetching article
        }
    }
    ?>
    <main class="container py-5">
      <?php if ($article === null): ?>
        <div class="alert alert-danger">Artikel niet gevonden.</div>
        <a href="Weblog.php" class="btn btn-primary">Terug naar Weblog</a>
      <?php else: ?>
        <div class="row">
          <div class="col-12">
            <a href="Weblog.php" class="btn btn-secondary mb-4">← Terug naar Weblog</a>
            <h1 class="mb-2"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="text-muted mb-4"><strong>Slug:</strong> <?php echo htmlspecialchars($article['slug'], ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="card shadow-sm">
              <div class="card-body">
                <div class="card-text">
                  <?php echo nl2br(htmlspecialchars($article['content'], ENT_QUOTES, 'UTF-8')); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
