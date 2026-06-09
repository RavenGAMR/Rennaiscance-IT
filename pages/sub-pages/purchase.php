<?php
require_once __DIR__ . '/../../login-DB/auth.php';
$pdo = getPDO();

// preserve an incoming product across a login redirect
$incoming = $_REQUEST['product'] ?? null;
if($incoming){
  $_SESSION['pending_product'] = $incoming;
}

// now require login (auth.php starts the session)
requireLogin();
$user = currentUser();
$pdo = getPDO();

// determine product: prefer explicit request, then pending session, then referer query
$product = $_REQUEST['product'] ?? ($_SESSION['pending_product'] ?? null);
$return = $_GET['return'] ?? 'diensten.php';
// try to extract product from HTTP_REFERER if still missing
if(!$product && !empty($_SERVER['HTTP_REFERER'])){
  $ref = parse_url($_SERVER['HTTP_REFERER']);
  if(!empty($ref['query'])){
    parse_str($ref['query'], $qs);
    if(!empty($qs['product'])){
      $product = $qs['product'];
      $_SESSION['pending_product'] = $product;
    }
  }
}
// if still no product, try to find the most recent purchase for this user
if(!$product && !empty($user['id'])){
  $recent = $pdo->prepare("SELECT product FROM purchases WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
  $recent->execute([$user['id']]);
  $r = $recent->fetch();
  if($r && !empty($r['product'])){
    $product = $r['product'];
    $_SESSION['pending_product'] = $product;
  }
}
// decode and sanitize return URL so encoded queries like "article.php%3Fslug%3D..." work
$return_decoded = urldecode($return);
// trim any trailing fragment-only marker
$return_decoded = rtrim($return_decoded, "#");
// if return is a relative file (no scheme, not starting with /), make it site-relative based on current request
if(!preg_match('#^https?://#i', $return_decoded) && strlen($return_decoded) && $return_decoded[0] !== '/'){
  $base = rtrim(dirname($_SERVER['REQUEST_URI']), '/');
  $return_location = $base . '/' . ltrim($return_decoded, '/');
} else {
  $return_location = $return_decoded ?: 'diensten.php';
}
$return_url = htmlspecialchars($return_location, ENT_QUOTES);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $product = $_POST['product'] ?? $product;
  if($product){
    // avoid duplicate purchases
    $check = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE user_id = ? AND product = ?");
    $check->execute([$user['id'], $product]);
    if($check->fetchColumn() == 0){
      $stmt = $pdo->prepare("INSERT INTO purchases (user_id, product) VALUES (?, ?)");
      $stmt->execute([$user['id'], $product]);
    }
    // clear pending product after purchase
    if(isset($_SESSION['pending_product'])){
        unset($_SESSION['pending_product']);
    }
  }
  header('Location: ' . $return_location);
  exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aankoop</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="/stylesheet/Style.css">
</head>
<body>
  <?php include __DIR__ . '/../../components/navbar.php'; ?>
  <main class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title mb-3">Aankoop</h3>
            <p>Gebruiker: <?php echo htmlspecialchars($user['username']); ?></p>
            <?php if(!$product): ?>
              <div class="alert alert-warning">Geen product geselecteerd.</div>
              <p>
                <a href="<?php echo $return_url; ?>" onclick="window.location.href='<?php echo $return_url; ?>'; return false;">Terug</a>
                or <a href="#" onclick="history.back(); return false;">ga terug</a>
              </p>
            <?php else:
              $paid = userHasPurchase($user['id'], $product);
              // try to get a human-friendly title from articles
              $product_title = $product;
              $tstmt = $pdo->prepare("SELECT title FROM articles WHERE slug = ? LIMIT 1");
              $tstmt->execute([$product]);
              $trow = $tstmt->fetch();
              if($trow && !empty($trow['title'])){
                $product_title = $trow['title'];
              }
            ?>
              <p>Product: <strong><?php echo htmlspecialchars($product_title); ?></strong></p>
              <?php if($paid): ?>
                <div class="alert alert-success">Je hebt dit product al betaald.</div>
                <p><a href="<?php echo $return_url; ?>" onclick="window.location.href='<?php echo $return_url; ?>'; return false;" class="btn btn-outline-primary">Terug</a></p>
              <?php else: ?>
                <form method="post">
                  <input type="hidden" name="product" value="<?php echo htmlspecialchars($product); ?>">
                  <button type="submit" class="btn btn-primary">Koop nu (gesimuleerd)</button>
                                    <a href="<?php echo $return_url; ?>" onclick="window.location.href='<?php echo $return_url; ?>'; return false;" class="btn btn-link">Annuleer</a>
                </form>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php include __DIR__ . '/../../components/footer.php'; ?>
</body>
</html>
