<?php
require_once __DIR__ . '/auth.php';
$pdo = getPDO();
$stmt = $pdo->query('SELECT id,title,slug FROM articles ORDER BY id ASC LIMIT 3');
$services = $stmt->fetchAll();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Diensten - Rennaiscance IT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="Style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <main class="container py-5">
        <h1 class="mb-4">Onze Diensten</h1>
        <div class="row g-4">
            <?php foreach($services as $s): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h2 class="h5"><?php echo htmlspecialchars($s['title']); ?></h2>
                        <p class="text-muted mb-3">Dienst omschrijving uit database.</p>
                        <div class="mt-auto">
                            <?php
                                $product = $s['slug'];
                                $paid = $user ? userHasPurchase($user['id'], $product) : false;
                            ?>
                            <?php if($paid): ?>
                                <span class="badge bg-success">Betaald</span>
                                <a href="article.php?slug=<?php echo urlencode($s['slug']); ?>" class="btn btn-outline-primary btn-sm ms-2">Bekijk</a>
                            <?php else: ?>
                                <?php if($user): ?>
                                    <a href="purchase.php?product=<?php echo urlencode($product); ?>&return=<?php echo urlencode('diensten.php'); ?>" class="btn btn-primary">Koop dienst</a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-primary">Log in om te kopen</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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
