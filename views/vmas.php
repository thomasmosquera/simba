<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// La ruta es '/../models/mmas.php' asumiendo que este archivo está en 'views/' y se llama desde 'index.php'
require_once __DIR__ . '/../models/mmas.php'; 

$mascotaModel = new Mascota();
$mensaje = "";

// 1. Obtener la lista de todos los usuarios para el campo select
// Asumimos que esta función existe en mmas.php y trae idusu, nomusu
$usuarios = $mascotaModel->listarUsuarios(); 

// ID para editar (si llega por URL)
$editar = isset($_GET['editar']) ? intval($_GET['editar']) : 0;
$mascotaEditar = null;

// Obtener todas las mascotas (incluye el nombre del usuario, si la consulta en el modelo lo permite)
$mascotas = $mascotaModel->listarMascotas();

// Buscar mascota a editar
if ($editar > 0) {
    // Buscamos solo la información básica para el formulario de edición
    $mascotaEditar = $mascotaModel->buscarMascotaPorId($editar);
}
?>

<div class="container-fluid px-4 py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-warning">
            <?= $mascotaEditar ? 'Editar Mascota SIMBA' : 'Registro de Mascotas SIMBA' ?>
        </h1>
        <p class="lead">
            Bienvenido, 
            <span class="text-warning">
                <?= isset($_SESSION['nomusu']) ? strtoupper($_SESSION['nomusu']) : 'Invitado' ?>
            </span>. 
            <?= $mascotaEditar ? 'Modifica la información de la mascota.' : 'Aquí tienes el formulario de registro.' ?>
        </p>
    </div>

    <!-- Mensajes de estado (si llegan desde el controlador) -->
    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <?php 
                $status = htmlspecialchars($_GET['status']);
                if ($status === 'registrado') echo '¡Mascota registrada correctamente!';
                else if ($status === 'actualizado') echo 'Mascota actualizada correctamente.';
                else if ($status === 'eliminado') echo 'Mascota eliminada correctamente.';
                else if ($status === 'error') echo 'Ocurrió un error en la operación.';
                else if ($status === 'error_eliminar') echo 'Error al eliminar la mascota.';
                else if ($status === 'error_registro') echo 'Error al registrar la mascota.';
                else if ($status === 'error_actualizar') echo 'Error al actualizar la mascota.';
                else if ($status === 'error_campos_incompletos') echo 'Todos los campos son obligatorios.';
                else if ($status === 'sin_sesion') echo 'Debes iniciar sesión para realizar esta operación.';
                else echo 'Mensaje de estado: ' . $status;
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <!-- Formulario de Registro/Edición -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-secondary text-white shadow-lg p-4">
                <div class="card-body">
                    <!-- ! IMPORTANTE: Mantener enctype="multipart/form-data" -->
                    <form method="POST" action="controllers/cmas.php" enctype="multipart/form-data">
                        <input type="hidden" name="accion" value="<?= $mascotaEditar ? 'actualizar' : 'registrar' ?>">
                        <!-- En el controlador cmas.php, la acción espera 'idmasc' para actualizar/eliminar. -->
                        <input type="hidden" name="idmasc" value="<?= $mascotaEditar['idmas'] ?? '' ?>"> 
                        
                        <div class="row g-3">
                            
                            <!-- Campo: Enlace a Usuario (idusu) - RELACIÓN MANTENIDA -->
                            <div class="col-md-4">
                                <label for="idusu" class="form-label">Usuario Propietario</label>
                                <select class="form-select" id="idusu" name="idusu" required>
                                    <option value="" <?= !isset($mascotaEditar) ? 'selected' : '' ?>>Seleccionar Usuario</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= $usuario['idusu'] ?>"
                                            <?= (($mascotaEditar['idusu'] ?? '') == $usuario['idusu']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario['nomusu']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-4">
                                <label for="nommasc" class="form-label">Nombre de la Mascota</label>
                                <input type="text" class="form-control" id="nommasc" name="nommasc" 
                                    value="<?= htmlspecialchars($mascotaEditar['nommas'] ?? '') ?>" 
                                    placeholder="Ej: Max" required>
                            </div>
                            <!-- Sexo -->
                            <div class="col-md-4">
                                <label for="sexo" class="form-label">Sexo</label>
                                <select class="form-select" id="sexo" name="sexo" required>
                                    <option value="" <?= !isset($mascotaEditar) ? 'selected' : '' ?>>Seleccionar</option>
                                    <option value="Macho" <?= (($mascotaEditar['sexmas'] ?? '') == 'Macho') ? 'selected' : '' ?>>Macho</option>
                                    <option value="Hembra" <?= (($mascotaEditar['sexmas'] ?? '') == 'Hembra') ? 'selected' : '' ?>>Hembra</option>
                                </select>
                            </div>
                            <!-- Peso -->
                            <div class="col-md-4">
                                <label for="peso" class="form-label">Peso (kg)</label>
                                <input type="number" step="0.01" class="form-control" id="peso" name="peso" 
                                    value="<?= htmlspecialchars($mascotaEditar['pesomas'] ?? '') ?>" 
                                    placeholder="Ej: 5.2 (En Kilogramos)" required>
                            </div>
                            <!-- Raza -->
                            <div class="col-md-4">
                                <label for="raza" class="form-label">Raza</label>
                                <input type="text" class="form-control" id="raza" name="raza" 
                                    value="<?= htmlspecialchars($mascotaEditar['razamas'] ?? '') ?>" 
                                    placeholder="Ej: Poodle, Pastor Alemán" required>
                            </div>
                            <!-- Edad -->
                            <div class="col-md-4">
                                <label for="edad" class="form-label">Edad (años)</label>
                                <input type="number" class="form-control" id="edad" name="edad" 
                                    value="<?= htmlspecialchars($mascotaEditar['edadmas'] ?? '') ?>" 
                                    placeholder="Ej: 3 (En años)" required>
                            </div>
                            <!-- Tipo -->
                            <div class="col-md-4">
                                <label for="tipo" class="form-label">Tipo</label>
                                <input type="text" class="form-control" id="tipo" name="tipo" 
                                    value="<?= htmlspecialchars($mascotaEditar['tipomas'] ?? '') ?>" 
                                    placeholder="Ej: Perro, Gato, Ave" required>
                            </div>
                            <!-- Tamaño -->
                            <div class="col-md-4">
                                <label for="tamanomasc" class="form-label">Tamaño</label>
                                <input type="text" class="form-control" id="tamanomasc" name="tamanomasc" 
                                    value="<?= htmlspecialchars($mascotaEditar['tammas'] ?? '') ?>" 
                                    placeholder="Ej: Pequeño, Mediano, Grande" required>
                            </div>
                            <!-- Cuidados Especiales -->
                            <div class="col-md-4">
                                <label for="cuiespmasc" class="form-label">Cuidados Especiales</label>
                                <input type="text" class="form-control" id="cuiespmasc" name="cuiespmasc" 
                                    value="<?= htmlspecialchars($mascotaEditar['cuidmas'] ?? '') ?>" 
                                    placeholder="Ej: Alergia a granos, Medicación diaria" required>
                            </div>
                            <!-- Vacunas al Día -->
                            <div class="col-md-4">
                                <label for="vacumasc" class="form-label">Vacunas al Día</label>
                                <select class="form-select" id="vacumasc" name="vacumasc" required>
                                    <option value="" <?= !isset($mascotaEditar) ? 'selected' : '' ?>>Seleccionar</option>
                                    <option value="Si" <?= (($mascotaEditar['vacmas'] ?? '') == 'Si') ? 'selected' : '' ?>>Sí</option>
                                    <option value="No" <?= (($mascotaEditar['vacmas'] ?? '') == 'No') ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            
                            <!-- ---------------------------------------------------- -->
                            <!-- SECCIÓN DE SUBIDA DE ARCHIVOS (CON PLACEHOLDERS) -->
                            <!-- ---------------------------------------------------- -->
                            <div class="col-md-6">
                                <label for="imagen" class="form-label">Foto de la Mascota</label>
                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" onchange="previewImage('imagen', 'fotoPreview')">
                                <p class="text-light small mt-1">Sube una foto de perfil de tu mascota (JPG, PNG).</p>
                                
                                <div class="mt-2 text-center">
                                    <?php 
                                    $fotoPath = $mascotaEditar['fotmas'] ?? '';
                                    // Muestra el placeholder si no hay foto actual
                                    $fotoSrc = !empty($fotoPath) ? 'controllers/../' . htmlspecialchars($fotoPath) : 'https://placehold.co/80x80/000/fff?text=Foto';
                                    ?>
                                    <!-- La imagen se previsualiza. Usa Placeholder. -->
                                    <img id="fotoPreview" 
                                         src="<?= $fotoSrc ?>" 
                                         alt="Previsualización Foto" 
                                         class="img-thumbnail" 
                                         style="max-width: 100px; max-height: 100px; display: block; margin: 0 auto;">
                                </div>
                                <?php if ($fotoPath): ?>
                                    <!-- Conserva el path actual si no se sube uno nuevo -->
                                    <input type="hidden" name="fotomasc_actual" value="<?= htmlspecialchars($fotoPath) ?>">
                                    <p class="text-light small text-center mt-1">Foto actual: <?= basename($fotoPath) ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="carnetmasc" class="form-label">Carnet de Vacunación</label>
                                <input type="file" class="form-control" id="carnetmasc" name="carnetmasc" accept="image/*, application/pdf" onchange="previewImage('carnetmasc', 'carnetPreview')">
                                <p class="text-light small mt-1">Sube el carnet de vacunación (JPG, PNG, PDF).</p>
                                
                                <div class="mt-2 text-center">
                                    <?php 
                                    $carnetPath = $mascotaEditar['carmas'] ?? '';
                                    // Verifica si el archivo actual es una imagen para mostrarla. Si no, muestra el placeholder.
                                    $isCarnetImage = $carnetPath && (strpos($carnetPath, '.jpg') !== false || strpos($carnetPath, '.png') !== false || strpos($carnetPath, '.jpeg') !== false);
                                    
                                    if ($carnetPath && $isCarnetImage): 
                                        $carnetSrc = 'controllers/../' . htmlspecialchars($carnetPath); 
                                    else:
                                        // Usa placeholder si no hay carnet o si es PDF (no visible en img tag)
                                        $carnetSrc = 'https://placehold.co/80x80/000/fff?text=Carnet';
                                    endif;
                                    ?>
                                    <img id="carnetPreview" src="<?= $carnetSrc ?>" 
                                         alt="Previsualización Carnet" 
                                         class="img-thumbnail" 
                                         style="max-width: 100px; max-height: 100px; display: block; margin: 0 auto;">
                                    <?php if ($carnetPath && !$isCarnetImage): ?>
                                        <p class="text-light small mt-1"><i class="fas fa-file-pdf"></i> Archivo PDF subido</p>
                                    <?php endif; ?>
                                </div>
                                <?php if ($carnetPath): ?>
                                    <!-- Conserva el path actual si no se sube uno nuevo -->
                                    <input type="hidden" name="carnetmasc_actual" value="<?= htmlspecialchars($carnetPath) ?>">
                                    <p class="text-light small text-center mt-1">Carnet actual: <?= basename($carnetPath) ?></p>
                                <?php endif; ?>
                            </div>
                            <!-- ---------------------------------------------------- -->

                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-warning btn-lg shadow">
                                    <i class="fas fa-save"></i> <?= $mascotaEditar ? 'Actualizar Mascota' : 'Registrar Mascota' ?>
                                </button>
                                <?php if ($mascotaEditar): ?>
                                    <a href="index.php?pg=1009" class="btn btn-outline-light btn-lg shadow ms-3">
                                        <i class="fas fa-plus"></i> Nuevo Registro
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Listado de Mascotas (CON FOTO Y CARNET EN LA TABLA) -->
    <h2 class="text-center fw-bold text-dark mb-4">Mascotas Registradas</h2>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg p-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Foto</th> <!-- Columna de foto -->
                                    <th>Propietario</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Raza</th>
                                    <th>Carnet</th> <!-- Columna de carnet -->
                                    <th>Vacunas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($mascotas): ?>
                                    <?php foreach ($mascotas as $m): ?>
                                        <tr>
                                            <td class="align-middle"><?= $m['idmas'] ?></td>
                                            
                                            <!-- Columna Foto -->
                                            <td class="align-middle text-center">
                                                <?php 
                                                // La ruta necesita subir dos niveles (../controllers/../uploads...)
                                                $fotoSrcList = !empty($m['fotmas']) ? 'controllers/../' . htmlspecialchars($m['fotmas']) : 'https://placehold.co/50x50/eee/333?text=N/A';
                                                ?>
                                                <img src="<?= $fotoSrcList ?>" alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>

                                            <!-- Mostrar Nombre de Usuario (nomusu viene de la unión en el modelo, si está implementada) -->
                                            <td class="align-middle"><?= htmlspecialchars($m['nomusu'] ?? 'N/A') ?></td> 
                                            <td class="align-middle"><?= htmlspecialchars($m['nommas']) ?></td>
                                            <td class="align-middle"><?= htmlspecialchars($m['tipomas']) ?></td>
                                            <td class="align-middle"><?= htmlspecialchars($m['razamas']) ?></td>
                                            
                                            <!-- Columna Carnet -->
                                            <td class="align-middle text-center">
                                                <?php if (!empty($m['carmas'])): ?>
                                                    <?php
                                                        $carnetPathList = 'controllers/../' . htmlspecialchars($m['carmas']);
                                                        $carnetExt = strtolower(pathinfo($m['carmas'], PATHINFO_EXTENSION));
                                                        if (in_array($carnetExt, ['jpg', 'jpeg', 'png', 'gif'])):
                                                    ?>
                                                        <a href="<?= $carnetPathList ?>" target="_blank" title="Ver Carnet">
                                                            <i class="fas fa-image fa-2x text-info"></i>
                                                        </a>
                                                    <?php else: // Asume PDF u otro archivo no imagen ?>
                                                        <a href="<?= $carnetPathList ?>" target="_blank" title="Descargar Carnet">
                                                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="align-middle"><?= htmlspecialchars($m['vacmas']) ?></td>
                                            
                                            <!-- Acciones -->
                                            <td class="align-middle">
                                                <a href="index.php?pg=1009&editar=<?= $m['idmas'] ?>" 
                                                   class="btn btn-outline-warning btn-sm me-1">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <!-- FORMULARIO DE ELIMINACIÓN CON POST Y idmasc -->
                                                <form method="POST" action="controllers/cmas.php" style="display:inline-block;">
                                                    <input type="hidden" name="accion" value="eliminar">
                                                    <!-- Es CRUCIAL que se llame 'idmasc' para el controlador -->
                                                    <input type="hidden" name="idmasc" value="<?= $m['idmas'] ?>"> 
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                            onclick="return confirm('¿Seguro que deseas eliminar la mascota <?= htmlspecialchars($m['nommas']) ?>?');">
                                                        <i class="fas fa-trash-alt"></i> Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="9" class="text-muted text-center">No hay mascotas registradas.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts de JavaScript para la previsualización de imágenes (Necesario para los placeholders del formulario) -->
<script>
    // Función para previsualizar la imagen seleccionada
    function previewImage(input_id, preview_id) {
        const input = document.getElementById(input_id);
        const preview = document.getElementById(preview_id);
        
        // Define las fuentes placeholder
        const placeholderFoto = 'https://placehold.co/80x80/000/fff?text=Foto';
        const placeholderCarnet = 'https://placehold.co/80x80/000/fff?text=Carnet';
        const isFotoInput = input_id === 'imagen';

        // Determina la fuente placeholder a usar
        const defaultPlaceholder = isFotoInput ? placeholderFoto : placeholderCarnet;

        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Solo previsualiza si es una imagen
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                // Si no es imagen (ej: PDF), muestra el placeholder genérico
                preview.src = defaultPlaceholder; 
            }
        } else {
            // Si no hay archivos en el input, restaura el placeholder.
            // Nota: El src original se establece en PHP al cargar la página si hay una imagen.
            // Si el usuario limpia el campo de archivo, volvemos al placeholder por defecto si no hay ruta actual.
            const fotoActual = document.querySelector('input[name="fotomasc_actual"]')?.value;
            const carnetActual = document.querySelector('input[name="carnetmasc_actual"]')?.value;
            
            if (isFotoInput && !fotoActual) {
                preview.src = defaultPlaceholder;
            } else if (!isFotoInput && !carnetActual) {
                preview.src = defaultPlaceholder;
            }
            // Si hay una ruta actual, el preview se mantendrá.
        }
    }
</script>
