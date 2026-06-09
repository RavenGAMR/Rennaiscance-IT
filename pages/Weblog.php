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
    require_once __DIR__ . '/db.php';
    $pdo = getPDO();
    $articles = [];
    try {
        $stmt = $pdo->query('SELECT id, title, slug, image, category FROM weblogs ORDER BY id DESC');
        $articles = $stmt->fetchAll();
    } catch (Exception $e) {
        $articles = [];
    }
    ?>
    <main class="container py-5">
      <h1 class="mb-4 black-heading">Weblog Artikelen</h1>
      <?php if (count($articles) === 0): ?>
        <div class="alert alert-info">Er zijn nog geen artikelen beschikbaar.</div>
      <?php else: ?>
        <nav class="nav nav-pills justify-content-center mb-4">
          <a class="nav-link active" href="#" data-filter="all">Alle</a>
          <?php
            $cats = [];
            foreach($articles as $a) { if(!empty($a['category'])) $cats[$a['category']] = true; }
            foreach(array_keys($cats) as $c):
          ?>
            <a class="nav-link" href="#" data-filter="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></a>
          <?php endforeach; ?>
        </nav>
        <div class="mb-4">
          <div class="row">
            <?php foreach ($articles as $article):
                $cat = htmlspecialchars($article['category'] ?? 'general');
                $img = htmlspecialchars($article['image'] ?? '');
                $title = htmlspecialchars($article['title'] ?? '');
                $id = (int)$article['id'];
            ?>
              <div class="col-12 col-md-6 mb-3 article-card" data-category="<?php echo $cat; ?>">
                <div class="card h-100">
                  <div class="row g-0 align-items-center">
                    <div class="col-auto" style="width:140px;">
                      <img src="<?php echo $img; ?>" alt="" class="article-card-image img-fluid" />
                    </div>
                    <div class="col">
                      <div class="card-body">
                        <h2 class="h5 mb-1"><?php echo $title; ?></h2>
                        <a href="Weblogartikel.php?id=<?php echo $id; ?>" class="stretched-link"></a>
                        <p class="text-muted mb-0 small"><?php echo htmlspecialchars($article['slug'] ?? ''); ?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
    <script>
      document.addEventListener('DOMContentLoaded', function(){
        const filters = document.querySelectorAll('.nav-link[data-filter]');
        const cards = document.querySelectorAll('.article-card');
        filters.forEach(f => f.addEventListener('click', function(e){
          e.preventDefault();
          filters.forEach(x => x.classList.remove('active'));
          this.classList.add('active');
          const name = this.dataset.filter;
          cards.forEach(c => {
            const cat = c.dataset.category || 'general';
            if(name === 'all' || cat === name) c.classList.remove('d-none'); else c.classList.add('d-none');
          });
        }));
      });
    </script>
</body>
</html>