<?php
require_once("controllers/cres.php");

$esEdicion = (isset($_GET['ope']) && $_GET['ope'] === 'edi');
$titulo = $esEdicion ? "Editar Reserva" : "Crear Una Reserva";
$textoBoton = $esEdicion ? "Guardar" : "Crear Reserva";

$datServiciosSeleccionados = [];
if($esEdicion && $datOne){
    $datServiciosSeleccionados = array_column($datOne['servicios'], 'idser');
}
?>

<div class="container-fluid px-4 py-5">
    <div class="card bg-secondary text-white shadow-lg p-3 w-85 mx-auto">
        <div class="card-body">
            <h3 class="card-title mb-4"><?= $titulo ?></h3>
            <form action="index.php?pg=1010&ope=save" method="post">
                
                <?php if($esEdicion && $datOne): ?>
                    <input type="hidden" name="idres" value="<?= $datOne['idres']; ?>">
                <?php endif; ?>

                <!-- Mascota -->
                <div class="mb-3">
                    <label for="idmas" class="form-label">Mascota</label>
                    <select id="idmas" name="idmas" class="form-select bg-dark text-light">
                        <?php foreach($datMas as $m): ?>
                            <option value="<?= $m['idmas']; ?>" <?= ($esEdicion && $datOne['idmas']==$m['idmas']) ? 'selected' : '' ?>>
                                <?= $m['nommas']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Cuidador -->
                <div class="mb-3">
                    <label for="idusu" class="form-label">Cuidador</label>
                    <select id="idusu" name="idusu" class="form-select bg-dark text-light">
                        <?php foreach($datUsu as $reg): ?>
                            <option value="<?= $reg['idusu']; ?>" <?= ($esEdicion && $datOne['idusu']==$reg['idusu']) ? 'selected' : '' ?>>
                                <?= $reg['nomusu']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha y hora</label>
                    <input type="datetime-local" id="fecha" name="fecact" class="form-control"
                        value="<?= $esEdicion ? date('Y-m-d\TH:i', strtotime($datOne['fecact'])) : '' ?>">
                </div>

                <!-- Servicios (multiple) -->
                <div class="mb-3">
                    <label for="idser" class="form-label">Servicios Disponibles</label>
                    <ul id="idser" class="list-group">
                        <?php if (!empty($datSer)): ?>
                            <?php foreach ($datSer as $ser): ?>
                                <li class="list-group-item bg-dark text-light">
                                    <input type="checkbox" 
                                        name="idser[]" 
                                        value="<?= $ser['idser']; ?>" 
                                        id="ser<?= $ser['idser']; ?>" 
                                        <?= in_array($ser['idser'], $datServiciosSeleccionados) ? 'checked' : '' ?> 
                                        class="form-check-input">
                                    <label for="ser<?= $ser['idser']; ?>" class="form-check-label">
                                        $<?= $ser['preser']; ?> -
                                        <?= $ser['nomser']; ?> 
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted fst-italic">
                                No hay ningún servicio disponible
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>


                <!-- Estado -->
                <div class="mb-3">
                    <label for="estres" class="form-label">Estado</label>
                    <select id="estres" name="estres" class="form-select bg-dark text-light">
                        <option value="Activo" <?= ($esEdicion && $datOne['estres']=="Activo") ? "selected" : "" ?>>Activo</option>
                        <option value="Inactivo" <?= ($esEdicion && $datOne['estres']=="Inactivo") ? "selected" : "" ?>>Inactivo</option>
                        <option value="Pendiente" <?= ($esEdicion && $datOne['estres']=="Pendiente") ? "selected" : "" ?>>Pendiente</option>
                    </select>
                </div>
                
                <a href="index.php?pg=1010" class="btn btn-warning">
                    <i class="fa-solid fa-arrow-left"></i> Volver</class>
                </a>
                <button type="submit" class="btn btn-warning"><?= $textoBoton ?></button>
            </form>
        </div>
    </div>
</div>
