<?php
require_once __DIR__ . '/auth.php';
requireAdmin();
?>
<?php
// Handle CRUD actions for diensten (articles)
$pdo = getPDO();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $action = $_POST['action'] ?? '';
    if($action === 'create'){
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if($title && $slug){
            $stmt = $pdo->prepare('INSERT INTO articles (title, slug, content) VALUES (?, ?, ?)');
            $stmt->execute([$title, $slug, $content]);
            header('Location: Admin.php?msg=created');
            exit;
        }
    }
    if($action === 'update'){
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if($id && $title && $slug){
            $stmt = $pdo->prepare('UPDATE articles SET title = ?, slug = ?, content = ? WHERE id = ?');
            $stmt->execute([$title, $slug, $content, $id]);
            header('Location: Admin.php?msg=updated');
            exit;
        }
    }
    if($action === 'delete'){
        $id = intval($_POST['id'] ?? 0);
        if($id){
            $stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
            $stmt->execute([$id]);
            header('Location: Admin.php?msg=deleted');
            exit;
        }
    }
    if($action === 'changerole'){
        $uid = intval($_POST['user_id'] ?? 0);
        $newRole = $_POST['role'] ?? 'user';
        $current = currentUser();
        if(!$uid || !$current){
            header('Location: Admin.php?msg=rolefail'); exit;
        }
        // Prevent admin changing their own role to avoid lockout
        if($uid == $current['id']){
            header('Location: Admin.php?msg=cantself'); exit;
        }
        $allowed = ['user','admin'];
        if(!in_array($newRole, $allowed, true)) $newRole = 'user';
        $ustmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
        $ustmt->execute([$newRole, $uid]);
        header('Location: Admin.php?msg=rolechanged'); exit;
    }
}
// Helpers for edit/create views
$editing = false;
$editArticle = null;
$action = $_GET['action'] ?? '';
if($action === 'edit' && !empty($_GET['id'])){
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare('SELECT id,title,slug,content FROM articles WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $editArticle = $stmt->fetch();
    if($editArticle){ $editing = true; }
}

// Fetch all diensten for listing
$stmt = $pdo->query('SELECT id,title,slug FROM articles ORDER BY id DESC');
$articles = $stmt->fetchAll();

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
                                    <th>Slug</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($articles as $a): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($a['id']); ?></td>
                                        <td><?php echo htmlspecialchars($a['title']); ?></td>
                                        <td><?php echo htmlspecialchars($a['slug']); ?></td>
                                        <td>
                                            <a href="Admin.php?action=edit&id=<?php echo $a['id']; ?>" class="btn btn-sm btn-outline-primary">Bewerk</a>
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
                            <h2 class="h5">Bewerk dienst</h2>
                            <form method="post">
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
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary" type="submit">Opslaan</button>
                                    <a href="Admin.php" class="btn btn-secondary">Annuleer</a>
                                </div>
                            </form>
                        <?php else: ?>
                            <h2 class="h5">Nieuwe dienst toevoegen</h2>
                            <form method="post">
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

    <?php include 'footer.php'; ?>
</body>
</html>
