<div class="d-flex flex-column h-100 gap-4 px-2">
    <!-- Parte superior -->
    <div>
        <h2 class="d-flex align-items-center gap-2 fs-5 fw-semibold text-primary mb-4">
            <i class="bi bi-folder-fill text-warning fs-4"></i> Mi Drive
        </h2>

        <form method="POST" class="input-group mb-3 rounded-3 overflow-hidden shadow-sm">
            <input type="text" name="new_folder" class="form-control border-0 py-2" placeholder="Nueva carpeta" required>
            <button type="submit" class="btn btn-primary px-3">
                <i class="bi bi-folder-plus"></i>
            </button>
        </form>

        <button type="button" class="btn btn-outline-primary w-100 py-2 mb-3 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-upload me-2"></i>Subir archivo
        </button>
    </div>

    <!-- Parte inferior siempre abajo -->
    <div class="mt-auto">
        <a href="trash.php" class="btn btn-outline-danger w-100 py-2 mb-3 rounded-3 shadow-sm">
            🗑 Ver papelera
        </a>

        <hr class="text-muted my-3">

        <div class="text-center small text-muted mb-2">
            <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']) ?>
        </div>

        <a href="logout.php" class="btn btn-warning w-100 py-2 rounded-3 shadow-sm">
            🔒 Cerrar sesión
        </a>
    </div>
</div>
