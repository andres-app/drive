<div class="d-flex flex-column h-100 gap-3">
    <!-- Parte superior -->
    <div>
        <h2 class="d-flex align-items-center gap-2">
            <i class="bi bi-folder-fill text-warning fs-4"></i> Mi Drive
        </h2>

        <form method="POST" class="input-group mb-3">
            <input type="text" name="new_folder" class="form-control" placeholder="Nueva carpeta" required>
            <button type="submit" class="btn btn-primary"><i class="bi bi-folder-plus"></i></button>
        </form>

        <button type="button" class="btn modal-option btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
            📄 Subir archivo
        </button>
    </div>

    <!-- Parte inferior siempre pegada abajo -->
    <div class="mt-auto">
        <a href="trash.php" class="btn btn-outline-danger w-100 mb-3">🗑 Ver papelera</a>

        <hr>
        <div class="small text-muted mb-1 text-center">
            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
        </div>
        <a href="logout.php" class="btn btn-warning w-100">Cerrar sesión</a>
    </div>
</div>
