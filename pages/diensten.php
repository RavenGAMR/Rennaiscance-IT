<?php
require_once __DIR__ . '/../login-DB/auth.php';
$pdo = getPDO();
$stmt = $pdo->query('SELECT id,title,slug,image,price FROM articles ORDER BY id ASC');
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
    <link rel="stylesheet" href="/stylesheet/Style.css">
</head>
<body>
    <?php include __DIR__ . '/../components/navbar.php'; ?>
    <main class="container py-5">
        <h1 class="black-heading">Onze Diensten</h1>
        <div class="diensten-row g-4">
            <?php foreach($services as $s): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <?php $img = $s['image'] ?? null; ?>
                    <?php if(!empty($img)): ?>
                        <div class="article-card-image" style="background-image:url('uploads/articles/<?php echo htmlspecialchars($img); ?>');"></div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h2 class="black-heading"><?php echo htmlspecialchars($s['title']); ?></h2>
                        <p class="text-muted mb-2">&euro; <?php echo number_format($s['price'] ?? 0, 2, ',', '.'); ?></p>
                        <p class="text-muted mb-3">Klik voor meer informatie.</p>
                        <div class="mt-auto">
                            <?php
                                $product = $s['slug'];
                                $paid = $user ? userHasPurchase($user['id'], $product) : false;
                            ?>
                            <?php if($paid): ?>
                                <span class="badge bg-success">Betaald</span>
                                <a href="/pages/sub-pages/article.php?slug=<?php echo urlencode($s['slug']); ?>" class="btn btn-outline-primary btn-sm ms-2">Bekijk</a>
                            <?php else: ?>
                                <?php if($user): ?>
                                    <a href="/pages/sub-pages/purchase.php?product=<?php echo urlencode($product); ?>&return=<?php echo urlencode('/pages/diensten.php'); ?>" class="btn btn-primary">Koop dienst</a>
                                <?php else: ?>
                                    <a href="/login-DB/login.php" class="btn btn-primary">Log in om te kopen</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
