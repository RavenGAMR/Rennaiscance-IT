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
<body class="helpdesk-page">
    <?php include 'navbar.php'; ?>
    <?php
    // Try to load helpdesk articles from DB. If table missing or empty, fallback to hardcoded list below.
    $dbArticles = null;
    try {
      require_once __DIR__ . '/db.php';
      $pdo = getPDO();
      $stmt = $pdo->query("SELECT id,title,slug,image,content,category FROM helpdesk_articles ORDER BY id ASC");
      $dbArticles = $stmt->fetchAll();
    } catch (Exception $e) {
      $dbArticles = null;
    }
    ?>
    <main class="container py-5">
      <h1 class="mb-4 black-heading">Helpdesk artikelen</h1>

      <nav class="nav nav-pills justify-content-center mb-5">
        <a class="nav-link active" href="#" data-filter="all">Alle</a>
        <a class="nav-link" href="#" data-filter="email">E-mail</a>
        <a class="nav-link" href="#" data-filter="ftp">FTP</a>
      </nav>

    <?php
    if (is_array($dbArticles) && count($dbArticles) > 0) :
        foreach ($dbArticles as $a) :
            $cat = htmlspecialchars($a['category'] ?? 'email');
            $img = htmlspecialchars($a['image'] ?? '');
            $title = htmlspecialchars($a['title'] ?? '');
            $id = (int)$a['id'];
    ?>
            <div class="card article-card mb-4" data-category="<?php echo $cat; ?>">
              <div class="d-flex align-items-center">
                <div class="me-3" style="flex: 0 0 180px;">
                  <img src="<?php echo $img; ?>" alt="" class="article-card-image img-fluid" />
                </div>
                <div class="flex-fill">
                  <div class="card-body p-0">
                    <h2 class="card-title"><?php echo $title; ?></h2>
                    <a href="Helpdeskartikel.php?id=<?php echo $id; ?>" class="stretched-link"></a>
                  </div>
                </div>
              </div>
            </div>
    <?php
        endforeach;
    endif;
    ?>
    </main>

    <script>
      document.addEventListener('DOMContentLoaded', function(){
        const filters = document.querySelectorAll('.nav-link[data-filter]');
        const cards = document.querySelectorAll('.card.article-card');
        function applyFilter(name){
          cards.forEach(card => {
            const cat = card.dataset.category || 'email';
            if(name === 'all') card.classList.remove('d-none');
            else if(name === 'email') {
              if(cat === 'email') card.classList.remove('d-none'); else card.classList.add('d-none');
            } else if(name === 'ftp') {
              if(cat === 'ftp') card.classList.remove('d-none'); else card.classList.add('d-none');
            }
          });
        }
        filters.forEach(f => f.addEventListener('click', function(e){
          e.preventDefault();
          filters.forEach(x => x.classList.remove('active'));
          this.classList.add('active');
          applyFilter(this.dataset.filter);
        }));
      });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>