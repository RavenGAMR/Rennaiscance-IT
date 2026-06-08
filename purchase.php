<?php
require_once __DIR__ . '/auth.php';
requireLogin();
$user = currentUser();
$pdo = getPDO();
$return = $_GET['return'] ?? 'Weblog.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // simple simulated purchase
    $stmt = $pdo->prepare("INSERT INTO purchases (user_id, product) VALUES (?, ?)");
    $stmt->execute([$user['id'], 'weblog_access']);
    header('Location: ' . $return);
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Purchase access</title></head>
<body>
<h1>Purchase access to the Weblog</h1>
<p>User: <?php echo htmlspecialchars($user['username']); ?></p>
<form method="post">
  <button type="submit">Buy access (simulated)</button>
</form>
<p><a href="<?php echo htmlspecialchars($return); ?>">Cancel</a></p>
</body>
</html>
