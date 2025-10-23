<div class="login-wrapper text-light">
    <div class="card-container" id="">
        <div class="card bg-dark border-warning border-1 shadow p-4 rounded-4 mx-auto" style="max-width: 400px;">
            <div class="text-center mb-4">
                <img src="img/logo2.png" alt="Logo Simba" class="img-fluid mx-auto d-block mb-3 login-logo">
                <h2 class="h5 text-white-50">Recuperar contraseña</h2>
            </div>

            <?php if (isset($_GET['mensaje'])): ?>
                <div class="alert alert-<?= htmlspecialchars($_GET['tipo'] ?? 'warning') ?> alert-dismissible fade show" role="alert">
                    <?= $_GET['mensaje'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <form action="index.php" method="GET">
                <input type="hidden" name="pg" value="1003">
                <div class="mb-3">
                    <label for="correo" class="form-label text-white">Correo</label>
                    <input type="email" class="form-control bg-dark text-white border-secondary" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
                </div>
                
                <button type="submit" class="btn btn-warning w-100">
                    <i class="fa-solid fa-square-envelope"></i>
                    Recuperar 
                </button>
                <p class="text-secondary text-center">Recuerde que el correo debe estar registrado en el sistema.</p>
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        Regresar al inicio
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
