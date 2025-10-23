<link rel="stylesheet" href="css/styles.css">
<div id="contenido">
    <div id="cuadropr">
        <img id="simbalet" src="img/logo2.png" alt="Logo SIMBA">
        <h2 style="margin-bottom: 15px;">Recuperar contraseña</h2>

        <?php if (isset($mensaje)): ?>
            <div style="background-color: #333; color: #FFC107; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <form action="index.php?pg=2001&accion=recuperar" method="POST">
            <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>

            <button type="submit" class="mt-2 w-100">
                <i class="fas fa-paper-plane"></i> Enviar enlace de recuperación
            </button>
        </form>

        <div class="mt-3">
            <a href="index.php" class="text-white" style="text-decoration: underline;">Volver al inicio de sesión</a>
        </div>
    </div>

    <div id="imgfondo">
        <img src="img/logo1.png" class="img-fluid" style="width: 50vw; opacity: 0.1;">
    </div>
</div>
