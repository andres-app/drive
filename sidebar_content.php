<div class="d-grid gap-3">
    <h2 class="d-flex align-items-center gap-2">
        <i class="bi bi-folder-fill text-warning fs-4"></i> Mi Drive
    </h2>

    <form method="POST" class="input-group">
        <input type="text" name="new_folder" class="form-control" placeholder="Nueva carpeta" required>
        <button type="submit" class="btn btn-primary"><i class="bi bi-folder-plus"></i></button>
    </form>

    <button type="button" class="btn modal-option btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        📄 Subir archivo
    </button>

    <a href="trash.php" class="btn btn-outline-danger">🗑 Ver papelera</a>

    <hr>

    <div class="small text-muted">
        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
    </div>
    <a href="logout.php" class="btn btn-warning">Cerrar sesión</a>
</div>
