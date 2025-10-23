
<div class="login-wrapper text-light">
    <div class="card-container" id="">
        <div class="card bg-dark border-warning border-1 shadow p-4 rounded-4 mx-auto" style="max-width: 400px;">
            <div class="text-center mb-4">
                <img src="img/logo2.png" alt="Logo Simba" class="img-fluid mx-auto d-block mb-3 login-logo">
                <h2 class="h5 text-white-50">Iniciar Sesión</h2>
            </div>

            <form action="controllers/cini.php" method="POST">
                <div class="mb-3">
                    <label for="cedusu" class="form-label text-white">No de documento:</label>
                    <input type="text" class="form-control bg-dark text-white border-secondary" id="cedusu" name="cedusu" placeholder="Escriba su T.I, C.C, C.E o PPT" required>
                </div>
                <div class="mb-3">
                    <label for="contusu" class="form-label text-white">Contraseña</label>
                    <input type="password" class="form-control bg-dark text-white border-secondary" id="contusu" name="contusu" placeholder="******" required>
                </div>
                <button type="submit" class="btn btn-warning w-100">
                    <i class="fa-solid fa-right-to-bracket"></i> Ingresar
                </button>
                <a href="index.php?pg=1002" class="h6 text-light d-block text-center mt-2">Olvidé mi contraseña</a>
            </form>
        </div>
    </div>
</div>

</*?php
$hash = password_hash("5656", PASSWORD_DEFAULT);
echo $hash;
?*/>
