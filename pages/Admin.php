<?php
require_once __DIR__ . '/auth.php';
requireAdmin();
?>
<?php
// Handle CRUD actions for diensten (articles)
$pdo = getPDO();
// AJAX detection and helper
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
function ajaxResponse($status, $msg){
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'msg' => $msg]);
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $action = $_POST['action'] ?? '';
    if($action === 'create'){
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $price = floatval(str_replace(',', '.', $_POST['price'] ?? 0));
        if($title && $slug){
            $stmt = $pdo->prepare('INSERT INTO articles (title, slug, content, price) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $slug, $content, $price]);
            $articleId = $pdo->lastInsertId();
            // handle uploaded image for article
            if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                $f = $_FILES['image'];
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $name = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $dest = __DIR__ . '/uploads/articles/' . $name;
                if(move_uploaded_file($f['tmp_name'], $dest)){
                    $u = $pdo->prepare('UPDATE articles SET image = ? WHERE id = ?');
                    $u->execute([$name, $articleId]);
                }
            }
            if($isAjax) ajaxResponse('ok','created');
            header('Location: Admin.php?msg=created');
            exit;
        }
    }
    if($action === 'update'){
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $price = floatval(str_replace(',', '.', $_POST['price'] ?? 0));
        if($id && $title && $slug){
            $stmt = $pdo->prepare('UPDATE articles SET title = ?, slug = ?, content = ?, price = ? WHERE id = ?');
            $stmt->execute([$title, $slug, $content, $price, $id]);
            // handle uploaded image for article (replace)
            if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                $f = $_FILES['image'];
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $name = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $dest = __DIR__ . '/uploads/articles/' . $name;
                if(move_uploaded_file($f['tmp_name'], $dest)){
                    $u = $pdo->prepare('UPDATE articles SET image = ? WHERE id = ?');
                    $u->execute([$name, $id]);
                }
            }
            if($isAjax) ajaxResponse('ok','updated');
            header('Location: Admin.php?msg=updated');
            exit;
        }
    }
    if($action === 'delete'){
        $id = intval($_POST['id'] ?? 0);
        if($id){
            $stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
            $stmt->execute([$id]);
            if($isAjax) ajaxResponse('ok','deleted');
            header('Location: Admin.php?msg=deleted');
            exit;
        }
    }
    // Helpdesk articles CRUD
    if($action === 'create_helpdesk'){
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'email');
        if($title){
            $stmt = $pdo->prepare('INSERT INTO helpdesk_articles (title, slug, content, category) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $slug, $content, $category]);
            $hid = $pdo->lastInsertId();
            if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                $f = $_FILES['image'];
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $name = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $rel = 'uploads/helpdesk/' . $name;
                $dest = __DIR__ . '/' . $rel;
                if(move_uploaded_file($f['tmp_name'], $dest)){
                    $u = $pdo->prepare('UPDATE helpdesk_articles SET image = ? WHERE id = ?');
                    $u->execute([$rel, $hid]);
                }
            }
            if($isAjax) ajaxResponse('ok','help_created');
            header('Location: Admin.php?msg=help_created'); exit;
        }
    }
    if($action === 'update_helpdesk'){
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'email');
        if($id && $title){
            $stmt = $pdo->prepare('UPDATE helpdesk_articles SET title = ?, slug = ?, content = ?, category = ? WHERE id = ?');
            $stmt->execute([$title, $slug, $content, $category, $id]);
            if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                $f = $_FILES['image'];
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $name = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $rel = 'uploads/helpdesk/' . $name;
                $dest = __DIR__ . '/' . $rel;
                if(move_uploaded_file($f['tmp_name'], $dest)){
                    $u = $pdo->prepare('UPDATE helpdesk_articles SET image = ? WHERE id = ?');
                    $u->execute([$rel, $id]);
                }
            }
            if($isAjax) ajaxResponse('ok','help_updated');
            header('Location: Admin.php?msg=help_updated'); exit;
        }
    }
    if($action === 'delete_helpdesk'){
        $id = intval($_POST['id'] ?? 0);
        if($id){
            $stmt = $pdo->prepare('DELETE FROM helpdesk_articles WHERE id = ?');
            $stmt->execute([$id]);
            if($isAjax) ajaxResponse('ok','help_deleted');
            header('Location: Admin.php?msg=help_deleted'); exit;
        }
    }
    // Weblogs CRUD
    if($action === 'create_weblog'){
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'general');
        if($title){
            $stmt = $pdo->prepare('INSERT INTO weblogs (title, slug, content, category) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $slug, $content, $category]);
            $wid = $pdo->lastInsertId();
            if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                $f = $_FILES['image'];
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $name = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $rel = 'uploads/weblogs/' . $name;
                $dest = __DIR__ . '/' . $rel;
                if(move_uploaded_file($f['tmp_name'], $dest)){
                    $u = $pdo->prepare('UPDATE weblogs SET image = ? WHERE id = ?');
                    $u->execute([$rel, $wid]);
                }
            }
            if($isAjax) ajaxResponse('ok','weblog_created');
            header('Location: Admin.php?msg=weblog_created'); exit;
        }
    }
    if($action === 'update_weblog'){
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'general');
        if($id && $title){
            $stmt = $pdo->prepare('UPDATE weblogs SET title = ?, slug = ?, content = ?, category = ? WHERE id = ?');
            $stmt->execute([$title, $slug, $content, $category, $id]);
            if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                $f = $_FILES['image'];
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $name = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $rel = 'uploads/weblogs/' . $name;
                $dest = __DIR__ . '/' . $rel;
                if(move_uploaded_file($f['tmp_name'], $dest)){
                    $u = $pdo->prepare('UPDATE weblogs SET image = ? WHERE id = ?');
                    $u->execute([$rel, $id]);
                }
            }
            if($isAjax) ajaxResponse('ok','weblog_updated');
            header('Location: Admin.php?msg=weblog_updated'); exit;
        }
    }
    if($action === 'delete_weblog'){
        $id = intval($_POST['id'] ?? 0);
        if($id){
            $stmt = $pdo->prepare('DELETE FROM weblogs WHERE id = ?');
            $stmt->execute([$id]);
            if($isAjax) ajaxResponse('ok','weblog_deleted');
            header('Location: Admin.php?msg=weblog_deleted'); exit;
        }
    }
    if($action === 'changerole'){
        $uid = intval($_POST['user_id'] ?? 0);
        $newRole = $_POST['role'] ?? 'user';
        $current = currentUser();
        if(!$uid || !$current){
            if($isAjax) ajaxResponse('error','rolefail');
            header('Location: Admin.php?msg=rolefail'); exit;
        }
        // Prevent admin changing their own role to avoid lockout
        if($uid == $current['id']){
            if($isAjax) ajaxResponse('error','cantself');
            header('Location: Admin.php?msg=cantself'); exit;
        }
        $allowed = ['user','admin'];
        if(!in_array($newRole, $allowed, true)) $newRole = 'user';
        $ustmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
        $ustmt->execute([$newRole, $uid]);
        if($isAjax) ajaxResponse('ok','rolechanged');
        header('Location: Admin.php?msg=rolechanged'); exit;
    }
    
}
// Helpers for edit/create views
$editing = false;
$editArticle = null;
$action = $_GET['action'] ?? '';
if($action === 'edit' && !empty($_GET['id'])){
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare('SELECT id,title,slug,content,price FROM articles WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $editArticle = $stmt->fetch();
    if($editArticle){ $editing = true; }
}

// AJAX: return article edit form partial
if(isset($_GET['action']) && $_GET['action'] === 'edit' && !empty($_GET['id']) && isset($_GET['ajax'])){
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare('SELECT id,title,slug,content,price FROM articles WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if(!$row){ http_response_code(404); echo 'Dienst niet gevonden.'; exit; }
    ?>
    <form method="post" action="Admin.php" enctype="multipart/form-data" data-ajax="true">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <div class="mb-3">
            <label class="form-label">Titel</label>
            <input name="title" class="form-control" required value="<?php echo htmlspecialchars($row['title']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input name="slug" class="form-control" required value="<?php echo htmlspecialchars($row['slug']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="6"><?php echo htmlspecialchars($row['content']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Prijs (EUR)</label>
            <input name="price" class="form-control" required value="<?php echo htmlspecialchars(number_format($row['price'] ?? 0, 2, ',', '.')); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Afbeelding (optioneel)</label>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>
        <div class="d-grid">
            <button class="btn btn-primary" type="submit">Opslaan</button>
        </div>
    </form>
    <?php
    exit;
}

// Helpdesk editing
$editingHelpdesk = false;
$editHelpdesk = null;
$hAction = $_GET['helpdesk_action'] ?? '';
if($hAction === 'edit' && !empty($_GET['hid'])){
    $hid = intval($_GET['hid']);
    $hst = $pdo->prepare('SELECT id,title,slug,content,image,category FROM helpdesk_articles WHERE id = ? LIMIT 1');
    $hst->execute([$hid]);
    $editHelpdesk = $hst->fetch();
    if($editHelpdesk){ $editingHelpdesk = true; }
}

// If requested via AJAX, return only the edit form HTML for the helpdesk article
if(isset($_GET['helpdesk_action']) && $_GET['helpdesk_action'] === 'edit' && !empty($_GET['hid']) && isset($_GET['ajax'])){
    $hid = intval($_GET['hid']);
    $hst = $pdo->prepare('SELECT id,title,slug,content,image,category FROM helpdesk_articles WHERE id = ? LIMIT 1');
    $hst->execute([$hid]);
    $row = $hst->fetch();
    if(!$row){ http_response_code(404); echo 'Artikel niet gevonden.'; exit; }
    // Render the edit form (partial)
    ?>
    <form method="post" action="Admin.php" enctype="multipart/form-data" data-ajax="true">
        <input type="hidden" name="action" value="update_helpdesk">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <div class="mb-2">
            <label class="form-label">Titel</label>
            <input name="title" class="form-control" required value="<?php echo htmlspecialchars($row['title']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Slug</label>
            <input name="slug" class="form-control" value="<?php echo htmlspecialchars($row['slug'] ?? ''); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Categorie</label>
            <select name="category" class="form-select">
                <option value="email" <?php echo (($row['category'] ?? '') === 'email') ? 'selected' : ''; ?>>email</option>
                <option value="ftp" <?php echo (($row['category'] ?? '') === 'ftp') ? 'selected' : ''; ?>>ftp</option>
            </select>
        </div>
        <div class="mb-2">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="6"><?php echo htmlspecialchars($row['content'] ?? ''); ?></textarea>
        </div>
        <div class="mb-2">
            <label class="form-label">Afbeelding (optioneel)</label>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">Opslaan</button>
        </div>
    </form>
    <?php
    exit;
}

// Weblog editing
$editingWeblog = false;
$editWeblog = null;
$wAction = $_GET['weblog_action'] ?? '';
if($wAction === 'edit' && !empty($_GET['wid'])){
    $wid = intval($_GET['wid']);
    $wst = $pdo->prepare('SELECT id,title,slug,content,image,category FROM weblogs WHERE id = ? LIMIT 1');
    $wst->execute([$wid]);
    $editWeblog = $wst->fetch();
    if($editWeblog){ $editingWeblog = true; }
}

// If requested via AJAX, return only the edit form HTML for the weblog
if(isset($_GET['weblog_action']) && $_GET['weblog_action'] === 'edit' && !empty($_GET['wid']) && isset($_GET['ajax'])){
    $wid = intval($_GET['wid']);
    $wst = $pdo->prepare('SELECT id,title,slug,content,image,category FROM weblogs WHERE id = ? LIMIT 1');
    $wst->execute([$wid]);
    $row = $wst->fetch();
    if(!$row){ http_response_code(404); echo 'Weblog niet gevonden.'; exit; }
    ?>
    <form method="post" action="Admin.php" enctype="multipart/form-data" data-ajax="true">
        <input type="hidden" name="action" value="update_weblog">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <div class="mb-2">
            <label class="form-label">Titel</label>
            <input name="title" class="form-control" required value="<?php echo htmlspecialchars($row['title']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Slug</label>
            <input name="slug" class="form-control" value="<?php echo htmlspecialchars($row['slug'] ?? ''); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Categorie</label>
            <input name="category" class="form-control" value="<?php echo htmlspecialchars($row['category'] ?? 'general'); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="6"><?php echo htmlspecialchars($row['content'] ?? ''); ?></textarea>
        </div>
        <div class="mb-2">
            <label class="form-label">Afbeelding (optioneel)</label>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">Opslaan</button>
        </div>
    </form>
    <?php
    exit;
}

// Fetch all diensten for listing
$stmt = $pdo->query('SELECT id,title,slug,price FROM articles ORDER BY id DESC');
$articles = $stmt->fetchAll();

// Fetch helpdesk articles for listing
try {
    $hstmt = $pdo->query('SELECT id,title,slug,category FROM helpdesk_articles ORDER BY id DESC');
    $helpdeskArticles = $hstmt->fetchAll();
} catch (Exception $e) {
    $helpdeskArticles = [];
}

// Fetch weblogs for listing
try{
    $wstmt = $pdo->query('SELECT id,title,slug,category FROM weblogs ORDER BY id DESC');
    $weblogs = $wstmt->fetchAll();
}catch(Exception $e){
    $weblogs = [];
}

// Fetch users for role management
$ust = $pdo->query('SELECT id,username,email,role FROM users ORDER BY id ASC');
$users = $ust->fetchAll();
// current user
$current = currentUser();

?>
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
    <?php include 'navbar.php'; ?>
    <main class="container py-5">
        <h1 class="black-heading">Admin - Diensten beheer</h1>
        <?php if(!empty($_GET['msg'])): ?>
            <?php $m = $_GET['msg']; ?>
            <?php if($m === 'created'): ?>
                <div class="alert alert-success">Dienst aangemaakt.</div>
            <?php elseif($m === 'updated'): ?>
                <div class="alert alert-success">Dienst bijgewerkt.</div>
            <?php elseif($m === 'deleted'): ?>
                <div class="alert alert-success">Dienst verwijderd.</div>
            <?php elseif($m === 'help_created'): ?>
                <div class="alert alert-success">Helpdeskartikel aangemaakt.</div>
            <?php elseif($m === 'help_updated'): ?>
                <div class="alert alert-success">Helpdeskartikel bijgewerkt.</div>
            <?php elseif($m === 'help_deleted'): ?>
                <div class="alert alert-success">Helpdeskartikel verwijderd.</div>
            <?php elseif($m === 'rolechanged'): ?>
                <div class="alert alert-success">Gebruikersrol bijgewerkt.</div>
            <?php elseif($m === 'cantself'): ?>
                <div class="alert alert-warning">Je kunt je eigen rol niet wijzigen.</div>
            <?php elseif($m === 'rolefail'): ?>
                <div class="alert alert-danger">Rolwijziging mislukt.</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="h5">Bestaande diensten</h2>
                        <table class="table table-striped mt-3">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titel</th>
                                    <th>Prijs</th>
                                    <th>Slug</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($articles as $a): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($a['id']); ?></td>
                                        <td><?php echo htmlspecialchars($a['title']); ?></td>
                                        <td>&euro; <?php echo number_format($a['price'] ?? 0,2,',','.'); ?></td>
                                        <td><?php echo htmlspecialchars($a['slug']); ?></td>
                                        <td>
                                            <a href="#" data-id="<?php echo $a['id']; ?>" class="btn btn-sm btn-outline-primary open-article-edit">Bewerk</a>
                                            <form method="post" style="display:inline" onsubmit="return confirm('Weet je het zeker dat je deze dienst wilt verwijderen?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Verwijder</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <?php if($editing && $editArticle): ?>
                            <h2 class="h5 black-heading">Bewerk dienst</h2>
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($editArticle['id']); ?>">
                                <div class="mb-3">
                                    <label class="form-label">Titel</label>
                                    <input name="title" class="form-control" required value="<?php echo htmlspecialchars($editArticle['title']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input name="slug" class="form-control" required value="<?php echo htmlspecialchars($editArticle['slug']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea name="content" class="form-control" rows="6"><?php echo htmlspecialchars($editArticle['content']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Prijs (EUR)</label>
                                    <input name="price" class="form-control" required value="<?php echo htmlspecialchars(number_format($editArticle['price'] ?? 0, 2, ',', '.')); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Afbeelding (optioneel)</label>
                                    <input type="file" name="image" accept="image/*" class="form-control">
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary" type="submit">Opslaan</button>
                                    <a href="Admin.php" class="btn btn-secondary">Annuleer</a>
                                </div>
                            </form>
                        <?php else: ?>
                            <h2 class="h5 black-heading">Nieuwe dienst toevoegen</h2>
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="create">
                                <div class="mb-3">
                                    <label class="form-label">Titel</label>
                                    <input name="title" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input name="slug" class="form-control" required placeholder="bijv. mijn-dienst">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea name="content" class="form-control" rows="6"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Prijs (EUR)</label>
                                    <input name="price" class="form-control" required value="0,00">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Afbeelding (optioneel)</label>
                                    <input type="file" name="image" accept="image/*" class="form-control">
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-success" type="submit">Maak dienst</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h2 class="h5">Gebruikersbeheer</h2>
                <p class="text-muted">Wijzig rollen van geregistreerde gebruikers (admins kunnen zichzelf niet wijzigen).</p>
                <table class="table table-sm mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gebruiker</th>
                            <th>E-mail</th>
                            <th>Rol</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['id']); ?></td>
                                <td><?php echo htmlspecialchars($u['username']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['role']); ?></td>
                                <td>
                                    <?php if($current && $current['id'] == $u['id']): ?>
                                        <span class="text-muted">Ik</span>
                                    <?php else: ?>
                                        <form method="post" class="d-flex gap-2 align-items-center">
                                            <input type="hidden" name="action" value="changerole">
                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($u['id']); ?>">
                                            <select name="role" class="form-select form-select-sm" style="width:auto;">
                                                <option value="user" <?php echo ($u['role'] === 'user') ? 'selected' : ''; ?>>user</option>
                                                <option value="admin" <?php echo ($u['role'] === 'admin') ? 'selected' : ''; ?>>admin</option>
                                            </select>
                                            <button class="btn btn-sm btn-primary" type="submit">Wijzig</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Helpdesk beheer (onderaan Admin) -->
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h5">Helpdesk artikelen</h2>
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-sm mt-3">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titel</th>
                                    <th>Categorie</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($helpdeskArticles as $h): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($h['id']); ?></td>
                                    <td><?php echo htmlspecialchars($h['title']); ?></td>
                                    <td><?php echo htmlspecialchars($h['category']); ?></td>
                                    <td>
                                        <a href="#" data-hid="<?php echo $h['id']; ?>" class="btn btn-sm btn-outline-primary open-helpdesk-edit">Bewerk</a>
                                        <form method="post" style="display:inline" onsubmit="return confirm('Weet je het zeker dat je dit artikel wilt verwijderen?');">
                                            <input type="hidden" name="action" value="delete_helpdesk">
                                            <input type="hidden" name="id" value="<?php echo $h['id']; ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Verwijder</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <?php if($editingHelpdesk && $editHelpdesk): ?>
                                    <h3 class="h6">Bewerk helpdeskartikel</h3>
                                    <form method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="update_helpdesk">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($editHelpdesk['id']); ?>">
                                        <div class="mb-2">
                                            <label class="form-label">Titel</label>
                                            <input name="title" class="form-control" required value="<?php echo htmlspecialchars($editHelpdesk['title']); ?>">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Slug</label>
                                            <input name="slug" class="form-control" value="<?php echo htmlspecialchars($editHelpdesk['slug'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Categorie</label>
                                            <select name="category" class="form-select">
                                                <option value="email" <?php echo (($editHelpdesk['category'] ?? '') === 'email') ? 'selected' : ''; ?>>email</option>
                                                <option value="ftp" <?php echo (($editHelpdesk['category'] ?? '') === 'ftp') ? 'selected' : ''; ?>>ftp</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Content</label>
                                            <textarea name="content" class="form-control" rows="6"><?php echo htmlspecialchars($editHelpdesk['content'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Afbeelding (optioneel)</label>
                                            <input type="file" name="image" accept="image/*" class="form-control">
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary" type="submit">Opslaan</button>
                                            <a href="Admin.php" class="btn btn-secondary">Annuleer</a>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <h3 class="h6">Nieuw helpdeskartikel</h3>
                                    <form method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="create_helpdesk">
                                        <div class="mb-2">
                                            <label class="form-label">Titel</label>
                                            <input name="title" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Slug</label>
                                            <input name="slug" class="form-control" placeholder="bijv. mijn-handleiding">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Categorie</label>
                                            <select name="category" class="form-select">
                                                <option value="email">email</option>
                                                <option value="ftp">ftp</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Content</label>
                                            <textarea name="content" class="form-control" rows="6"></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Afbeelding (optioneel)</label>
                                            <input type="file" name="image" accept="image/*" class="form-control">
                                        </div>
                                        <div class="d-grid">
                                            <button class="btn btn-success" type="submit">Maak artikel</button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weblogs beheer -->
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h5">Weblogs</h2>
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-sm mt-3">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titel</th>
                                    <th>Categorie</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($weblogs as $w): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($w['id']); ?></td>
                                    <td><?php echo htmlspecialchars($w['title']); ?></td>
                                    <td><?php echo htmlspecialchars($w['category']); ?></td>
                                    <td>
                                        <a href="#" data-wid="<?php echo $w['id']; ?>" class="btn btn-sm btn-outline-primary open-weblog-edit">Bewerk</a>
                                        <form method="post" style="display:inline" onsubmit="return confirm('Weet je het zeker dat je dit weblog wilt verwijderen?');">
                                            <input type="hidden" name="action" value="delete_weblog">
                                            <input type="hidden" name="id" value="<?php echo $w['id']; ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Verwijder</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="h6">Nieuw weblog</h3>
                                <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="create_weblog">
                                    <div class="mb-2">
                                        <label class="form-label">Titel</label>
                                        <input name="title" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Slug</label>
                                        <input name="slug" class="form-control" placeholder="bijv. mijn-blogpost">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Categorie</label>
                                        <input name="category" class="form-control" value="general">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Content</label>
                                        <textarea name="content" class="form-control" rows="6"></textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Afbeelding (optioneel)</label>
                                        <input type="file" name="image" accept="image/*" class="form-control">
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-success" type="submit">Maak weblog</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- AJAX result modal -->
            <div class="modal fade" id="ajaxModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Bericht</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">Bezig...</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function(){
                const modalEl = document.getElementById('ajaxModal');
                const bsModal = new bootstrap.Modal(modalEl);

                function showMessage(msg){
                    modalEl.querySelector('.modal-body').textContent = msg;
                    bsModal.show();
                }

                function bindAjaxForm(form){
                    if(!form) return;
                    form.addEventListener('submit', function(e){
                        e.preventDefault();
                        const fd = new FormData(form);
                        fetch(form.action || window.location.href, {
                            method: 'POST',
                            body: fd,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        }).then(r => r.json()).then(data => {
                            modalEl.querySelector('.modal-body').textContent = data.msg || 'Klaar';
                            bsModal.show();
                        }).catch(err => {
                            modalEl.querySelector('.modal-body').textContent = 'Er is een fout opgetreden';
                            bsModal.show();
                        });
                    });
                }

                // Bind existing forms in the page (main area)
                document.querySelectorAll('main form[method="post"]').forEach(bindAjaxForm);

                // Handle clicks on edit links to load form into modal
                document.addEventListener('click', function(e){
                    const tHelp = e.target.closest('.open-helpdesk-edit');
                    const tArt = e.target.closest('.open-article-edit');
                    const tWeb = e.target.closest('.open-weblog-edit');
                    let url = null;
                    if(tHelp){
                        e.preventDefault();
                        const hid = tHelp.getAttribute('data-hid');
                        if(!hid) return;
                        url = 'Admin.php?helpdesk_action=edit&hid=' + encodeURIComponent(hid) + '&ajax=1';
                    } else if(tArt){
                        e.preventDefault();
                        const id = tArt.getAttribute('data-id');
                        if(!id) return;
                        url = 'Admin.php?action=edit&id=' + encodeURIComponent(id) + '&ajax=1';
                    } else if(tWeb){
                        e.preventDefault();
                        const wid = tWeb.getAttribute('data-wid');
                        if(!wid) return;
                        url = 'Admin.php?weblog_action=edit&wid=' + encodeURIComponent(wid) + '&ajax=1';
                    } else {
                        return;
                    }
                    modalEl.querySelector('.modal-body').textContent = 'Laden...';
                    bsModal.show();
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(r => r.text())
                        .then(html => {
                            modalEl.querySelector('.modal-body').innerHTML = html;
                            // bind the form in modal to submit via AJAX
                            const form = modalEl.querySelector('form');
                            if(form) bindAjaxForm(form);
                        }).catch(err => {
                            modalEl.querySelector('.modal-body').textContent = 'Kon formulier niet laden.';
                        });
                });

                // Ensure any data-bs-dismiss buttons reliably close the modal (fallback)
                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(btn){
                    btn.addEventListener('click', function(e){
                        try{ bsModal.hide(); }catch(err){ /* ignore */ }
                    });
                });
            });
            </script>

            <?php include 'footer.php'; ?>
</body>
</html>
