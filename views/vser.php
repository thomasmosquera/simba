<?php
    require_once("controllers/cser.php");
    $servicioEditar = isset($servicioEditar) ? $servicioEditar : null;
?>

<div class="container-fluid px-4 py-5">
        <div class="text-center mb-5">

            <h1 class="display-5 fw-bold text-warning">Registro del Servicios SIMBA</h1>
            <p class="lead">
                Bienvenido, <span class="text-warning">
                    <?= isset($_SESSION['nomusu']) ? strtoupper($_SESSION['nomusu']) : 'Invitado' ?>
                </span>. Aquí tienes el formulario de registro.
            </p>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?= htmlspecialchars($_GET['msg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card bg-secondary text-white shadow-lg p-4">
                    <div class="card-body">
                        <form method="POST" action="index.php?pg=1011&ope=SaVE">
                            <input type="hidden" name="accion" value="<?= $servicioEditar ? 'actualizar' : 'registrar' ?>">
                            <?php if ($servicioEditar): ?>
                                <input type="hidden" name="idser" value="<?= $servicioEditar['idser'] ?>">
                            <?php endif; ?>
                            <h5 class="card-title"><i class="fa-solid fa-bath"></i> Registro de Servicio</h5>
                            <hr class="text-white">
                            <div class="mb-3">
                                <label for="nombreser" class="form-label">Nombre del servicio</label>
                                <input type="text" name="nomser" id="nombreser" class="form-control" placeholder="Baño" required
                                    value="<?= $servicioEditar['nomser'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label for="descser" class="form-label">Descripción</label>
                                <textarea name="descser" id="descser" rows="3" class="form-control bg-dark" style="color: white" require><?= $servicioEditar['descser'] ?? '' ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="preser" class="form-label">Precio</label>
                                <input type="number" step="any" min="0" name="preser" id="preser" class="form-control" placeholder="$1000" required
                                    value="<?= $servicioEditar['preser'] ?? '' ?>">
                            </div>
                            <button type="submit" class="btn btn-warning w-100">
                                <?= $servicioEditar ? 'Actualizar Servicio' : 'Registrar Servicio' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-secondary text-white shadow-lg p-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i class="fas fa-list me-2"></i> Servicios Registrados</h5>
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($servicios as $ser): ?>
                                    <tr>
                                        <td><?= $ser['idser'] ?></td>
                                        <td><?= $ser['nomser'] ?></td>
                                        <td><?= $ser['descser'] ?></td>
                                        <td>$<?= number_format($ser['preser'], 2) ?></td>
                                        <td>
                                            <a href="index.php?pg=1011&editar=<?= $ser['idser'] ?>" class="btn btn-outline-warning">Editar</a>
                                            <form method="POST" action="index.php?pg=1011&ope=Eli" style="display:inline-block;" onsubmit="return confirmarEliminacion()">
                                                <input type="hidden" name="accion" value="eliminar">
                                                <input type="hidden" name="idser" value="<?= $ser['idser'] ?>">
                                                <button class="btn btn-outline-danger">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (count($servicios) === 0): ?>
                                    <tr><td colspan="5" class="text-center">No hay servicios registrados.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que deseas eliminar este servicio?\nEsta acción no se puede deshacer.");
        }
    </script>
</div>