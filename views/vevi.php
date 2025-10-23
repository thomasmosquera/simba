<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../models/mevi.php';

$pdo = (new conexion())->get_conexion();
$evi = new Evidencia($pdo);
$reservas = $evi->listarReservas();
$evidencias = $evi->listarEvidencias();

// Definir $edit_evi si se recibe el parámetro edit
$edit_evi = null;
if (isset($_GET['edit'])) {
    $idevi = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT *, tipevi FROM evidencia WHERE idevi = ?");
    $stmt->execute([$idevi]);
    $edit_evi = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="container-fluid px-4 py-5">
    <div class="card bg-secondary text-light shadow-lg p-4 mb-5">
        <div class="card-body">
            <div class="titulo-pagina mb-4">
                <h1 class="display-6 fw-bold text-warning">📂 Subida de Evidencias</h1>
            </div>

            <?php if (!empty($_SESSION['mensaje'])): ?>
                <div class="alert alert-info">
                    <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                </div>
            <?php endif; ?>

            <form action="/simba/controllers/cevi.php" method="POST" enctype="multipart/form-data" class="row g-3">
                <?php if ($edit_evi): ?>
                    <input type="hidden" name="idevi" value="<?php echo $edit_evi['idevi']; ?>">
                <?php endif; ?>

                <div class="col-md-6">
                    <label for="idreser" class="form-label">Reserva:</label>
                    <select name="idreser" id="idreser" class="form-select bg-dark text-light" required>
                        <option value="">Seleccione una reserva</option>
                        <?php foreach ($reservas as $res): ?>
                            <option value="<?php echo $res['idres']; ?>"
                                <?php
                                if ($edit_evi && $res['idres'] == $edit_evi['idres']) echo 'selected';
                                ?>>
                                <?php echo $res['nommasc'] . " - " . $res['nomusu'] . " " . $res['apeusu']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="tipo" class="form-label">Tipo de Evidencia:</label>
                    <select name="tipo" class="form-select bg-dark text-light" required>
                        <option value="">Seleccione tipo</option>
                        <option value="Paseo" <?php if ($edit_evi && $edit_evi['tipevi'] == 'Paseo') echo 'selected'; ?>>Paseo</option>
                        <option value="Alimento" <?php if ($edit_evi && $edit_evi['tipevi'] == 'Alimento') echo 'selected'; ?>>Alimento</option>
                        <option value="Baño" <?php if ($edit_evi && $edit_evi['tipevi'] == 'Baño') echo 'selected'; ?>>Baño</option>
                        <option value="Otro" <?php if ($edit_evi && $edit_evi['tipevi'] == 'Otro') echo 'selected'; ?>>Otro</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea name="descripcion" class="form-control bg-dark text-light" required><?php echo $edit_evi ? $edit_evi['desevi'] : ''; ?></textarea>
                </div>

                <div class="col-md-6">
                    <label for="archivo" class="form-label">Archivo:</label>
                    <input type="file" name="archivo" class="form-control bg-dark text-light" accept="image/*,video/*,application/pdf" <?php echo $edit_evi ? '' : 'required'; ?>>
                    <?php if ($edit_evi): ?>
                        <input type="hidden" name="archivo_actual" value="<?php echo $edit_evi['arcevi']; ?>">
                        <small class="text-dark">Actual: <?php echo $edit_evi['arcevi']; ?></small>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label for="fechevi" class="form-label">Fecha y Hora:</label>
                    <input type="datetime-local" name="fechevi" class="form-control bg-dark text-light"
                        value="<?php echo $edit_evi ? date('Y-m-d\TH:i', strtotime($edit_evi['fecevi'])) : date('Y-m-d\TH:i'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="responsable" class="form-label">Responsable:</label>
                    <input type="text" id="responsable" name="responsable"
                        class="form-control bg-dark text-light"
                        value="<?php echo $edit_evi ? $edit_evi['resp'] : ''; ?>">
                </div>

                <div class="col-12">
                    <?php if ($edit_evi): ?>
                        <button type="submit" name="update" class="btn btn-dark w-100">Actualizar Evidencia</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-warning w-100">Guardar Evidencia</button>
                    <?php endif; ?>
                </div>
            </form>

            <?php if ($edit_evi): ?>
                <div class="alert alert-warning mb-3">
                    <strong>Editando evidencia de:</strong>
                    <?php
                        // Busca la reserva seleccionada
                        foreach ($reservas as $res) {
                            if ($res['idres'] == $edit_evi['idres']) {
                                echo $res['nommasc'] . " - " . $res['nomusu'] . " " . $res['apeusu'];
                                break;
                            }
                        }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card bg-secondary text-light shadow-lg p-4">
        <div class="card-body">
            <h3 class="card-title mb-4">
                <i class="fas fa-list me-2"></i> Evidencias Registradas
            </h3>
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Mascota</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Archivo</th>
                            <th>Responsable</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($evidencias)): ?>
                        <?php foreach ($evidencias as $evi): ?>
                            <tr>
                                <td><?php echo $evi['fecevi']; ?></td>
                                <td><?php echo $evi['nommasc']; ?></td>
                                <td><?php echo $evi['nomusu'] . " " . $evi['apeusu']; ?></td>
                                <td><?php echo $evi['tipevi']; ?></td>
                                <td><?php echo $evi['desevi']; ?></td>
                                <td>
                                    <?php if (!empty($evi['archivo'])): ?>
                                        <a href="/simba/views/uploads/<?php echo $evi['archivo']; ?>" target="_blank" class="btn btn-sm btn-info">Ver</a>
                                    <?php else: ?>
                                        Sin archivo
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $evi['resp']; ?></td>
                                <td class="d-flex gap-2">
                                    <form action="/simba/index.php" method="get" style="display:inline;">
                                        <input type="hidden" name="pg" value="1015">
                                        <input type="hidden" name="edit" value="<?php echo $evi['idevi']; ?>">
                                        <button type="submit" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </form>
                                    <form action="/simba/controllers/cevi.php" method="get" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta evidencia?');">
                                        <input type="hidden" name="delete" value="<?php echo $evi['idevi']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No hay evidencias registradas.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>