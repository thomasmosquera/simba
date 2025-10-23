<?php

$pagina = $pagina ?? null; 
$is_editing = $is_editing ?? ($pagina !== null && isset($pagina['idpag']));
$form_action = $form_action ?? ($is_editing ? 'update' : 'store'); 
$title = $title ?? ($is_editing ? 'Editar Página' : 'Crear Nueva Página');
$submit_text = $submit_text ?? ($is_editing ? 'Actualizar Página' : 'Crear Página');

// Valores por defecto para el formulario
$idpag_val = $idpag_val ?? ($pagina['idpag'] ?? '');
$nompag_val = $nompag_val ?? ($pagina['nompag'] ?? '');
$rutpag_val = $rutpag_val ?? ($pagina['rutpag'] ?? '');
$mospag_val = $mospag_val ?? ($pagina['mospag'] ?? 1); 
?>

<div class="container mt-4">
    <h2 class="text-white mb-4"><?= $title ?></h2>

    <div class="card bg-dark text-white border-warning">
        <div class="card-body">
            <form action="index.php?pg=1016&action=<?= htmlspecialchars($form_action) ?>" method="POST">
                
                <?php if ($is_editing): ?>
                    <input type="hidden" name="idpag" value="<?= htmlspecialchars($idpag_val) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="nompag" class="form-label">Nombre de la Página:</label>
                    <input type="text" 
                           class="form-control bg-dark text-white border-warning" 
                           id="nompag" 
                           name="nompag" 
                           value="<?= htmlspecialchars($nompag_val) ?>" 
                           required>
                </div>

                <div class="mb-3">
                    <label for="rutpag" class="form-label">Ruta de Archivo (Ej: vlist.php):</label>
                    <input type="text" 
                           class="form-control bg-dark text-white border-warning" 
                           id="rutpag" 
                           name="rutpag" 
                           value="<?= htmlspecialchars($rutpag_val) ?>" 
                           required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="mospag" 
                           name="mospag" 
                           value="1" 
                           <?= $mospag_val == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="mospag">Mostrar en Navegación</label>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i><?= $submit_text ?>
                    </button>
                    <a href="index.php?pg=1016" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>