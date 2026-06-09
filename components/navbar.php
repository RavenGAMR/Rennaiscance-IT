<?php require_once __DIR__ . '/../login-DB/auth.php'; ?>
<nav class="navbar navbar-expand-sm navbar-light bg-white shadow-sm">
    <div class="container-fluid">
            <a class="navbar-brand" href="/Index.php">
            <img src="Media/Logos/Renaissance-vol.png" alt="Rennaiscance logo" class="navbar-logo" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarID"
            aria-controls="navbarID" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarID">
                <div class="navbar-nav ms-auto">
                    <?php $u = currentUser(); ?>
                    <a class="nav-link" href="/Index.php">Home</a>
                    <a class="nav-link" href="/pages/Weblog.php">Weblog</a>
                    <a class="nav-link" href="/pages/diensten.php">Diensten</a>
                    <a class="nav-link" href="/pages/Helpdesk.php">Helpdesk</a>
                    <a class="nav-link" href="/pages/Contact.php">Contact</a>
                    <?php if($u): ?>
                        <?php if($u['role'] === 'admin'): ?>
                            <a class="nav-link" href="/pages/Admin.php">Admin</a>
                        <?php endif; ?>
                        <a class="nav-link" href="/login-DB/logout.php">Logout (<?php echo htmlspecialchars($u['username']); ?>)</a>
                    <?php else: ?>
                        <a class="nav-link active" href="/login-DB/login.php">Login</a>
                    <?php endif; ?>
                </div>
            </div>
    </div>
</nav>


