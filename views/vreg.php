<?php 
    require_once("controllers/creg.php");
?>

<?php if (isset($_GET['msg'])): ?>
    <div id="toast" class="toast">
        <?= htmlspecialchars($_GET['msg']); ?>
    </div>
<?php endif; ?>

<style>
.toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #FFC107;
    color: #fff;
    padding: 12px 20px;
    border-radius: 8px;
    opacity: 0;
    transition: opacity 0.5s;
    z-index: 9999;
}
.toast.show {
    opacity: 1;
}
</style>

<script>
window.addEventListener("DOMContentLoaded", () => {
    const toast = document.getElementById("toast");
    if (toast) {
        toast.classList.add("show");
        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000); // se esconde en 3 segundos
    }
});
</script>


<div>
    <div class="text-center">
        <h1 class="display-5 fw-bold text-warning">Registro del Usuario SIMBA</h1>
        <p class="lead">
            Bienvenido, <span class="text-warning">
                <?= isset($_SESSION['nomusu']) ? strtoupper($_SESSION['nomusu']) : 'Invitado' ?>
            </span>. Aquí tienes el formulario de registro.
        </p>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card bg-secondary text-white shadow-lg p-4">
                <div class="card-body">
                    <form method="POST" action="index.php?pg=1006&ope=SavE">
                        <input type="hidden" name="accion" value="<?= $datos ? 'editar' : 'registrar' ?>">
                        <?php if ($datos): ?>
                            <input type="hidden" name="idusu" value="<?= $datos['idusu'] ?>">
                        <?php endif; ?>
                        <div class="row">
                            <h5 class="card-title"><i class="fa-solid fa-user-plus"></i> Registro de Usuario</h5>
                            <hr class="text-white">

                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nomusu" value="<?= $datos['nomusu'] ?? '' ?>" class="form-control" placeholder="Juan"  required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="apeusu" value="<?= $datos['apeusu'] ?? '' ?>" class="form-control" placeholder="Perez" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="emausu" value="<?= $datos['emausu'] ?? '' ?>" class="form-control" placeholder="juanperez2@ejemplo.com" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telusus" value="<?= $datos['telusus'] ?? '' ?>" class="form-control"  placeholder="000 000 0000" pattern="[0-9]{10}" title="El teléfono debe tener exactamente 10 dígitos" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="dirusu" value="<?= $datos['dirusu'] ?? '' ?>" class="form-control" placeholder="Calle de Ejemplo # 123-A" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cédula</label>
                                <input type="text" name="cedusu" value="<?= $datos['cedusu'] ?? '' ?>" class="form-control" placeholder="1111111111" pattern="[0-9]{8,10}" title="La cédula debe tener entre 8 y 10 dígitos" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="contusu" class="form-control" placeholder="Mínimo 8 caracteres, incluye Mayús y número" pattern="^(?=.*[A-Z])(?=.*[0-9]).{8,}$" title="Mínimo 8 caracteres, al menos una mayúscula y un número" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipo de perfil</label>
                                <select name="idper" class="form-select bg-dark text-light" required>
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($perfiles as $perfil): ?>
                                        <option value="<?= $perfil['idper'] ?>"
                                            <?= ($datos['idper'] ?? '') == $perfil['idper'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($perfil['nomper']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <h5 class="card-title mb-3">
                                <i class="fas fa-user-friends me-2"></i> Contacto de Emergencia
                            </h5>
                            <hr class="text-white">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombre del contacto</label>
                                    <input type="text" name="nomemer" value="<?= $datos['nomemer'] ?? '' ?>" class="form-control" placeholder="Pepita rojas">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono del contacto</label>
                                    <input type="text" name="telemer" value="<?= $datos['telemer'] ?? '' ?>" class="form-control" placeholder="999 999 9999" pattern="[0-9]{10}" title="El teléfono de emergencia debe tener exactamente 10 dígitos">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 mt-4"><?= $datos ? 'Actualizar' : 'Registrar' ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-secondary text-white shadow-lg p-4 mt-4">
        <div class="card-body">
            <h5 class="card-title mb-4"><i class="fas fa-users me-2"></i> Usuarios Registrados</h5>
            <hr class="text-white">

            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= $u['cedusu'] ?></td>
                                <td><?= $u['nomusu'] . " " . $u['apeusu'] ?></td>
                                <td><?= $u['emausu'] ?></td>
                                <td><?= $u['telusus'] ?></td>
                                <td><?= strtoupper($u['tipo']) ?></td>
                                <td>
                                    <a href="index.php?pg=1006&ope=edit&idusu=<?= $u['idusu'] ?>" class="btn btn-outline-warning">
                                        Editar
                                    </a>
                                    <form method="POST" 
                                        action="index.php?pg=1006&ope=del" 
                                        style="display:inline-block;">
                                        <input type="hidden" name="idusu" value="<?= $u['idusu'] ?>">
                                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay usuarios registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
