<?php
require_once("controllers/cres.php");
?>

<div class="mt-4">
    <div class="card bg-secondary text-white shadow-lg p-3 w-85 mx-auto">
        <div class="card-body">
            <h3 class="card-title mb-4">
                <i class="fas fa-list me-2"></i> Reservas Registradas
            </h3>

            <div class="table-responsive">
                <?php if($datAll){ ?>
                    <table id="tablaReservas" class="table table-dark table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>No. Reserva</th>                                  
                                <th>Mascota</th>
                                <th>Fecha y hora</th>
                                <th>Servicios</th>
                                <th>Cuidador</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datAll as $dt){ 
                                $servicios = array_column($dt['servicios'], 'nomser');
                                $serviciosStr = implode(', ', $servicios);
                            ?>
                                <tr>
                                    <td><?= $dt['idres']; ?></td>
                                    <td><?= $dt['nommas']; ?></td>
                                    <td><?= $dt['fecact']; ?></td>
                                    <td><?= $serviciosStr; ?></td>
                                    <td><?= $dt['nomusu']; ?></td>
                                    <td><?= $dt['estres']; ?></td>
                                    <td style="text-align: right;">
                                        <a href="index.php?pg=2010&idres=<?= $dt['idres']; ?>&ope=edi" title="Editar">
                                            <i class="fa-solid fa-pen-to-square  btn btn-warning"></i>
                                        </a>
                                        <a href="index.php?pg=1010&ope=fac&idres=<?= $dt['idres'] ?>" target="_blank" title="Generar Factura">
                                            <i class="fa-solid fa-receipt  btn btn-warning"></i>
                                        </a>
                                        <a href="index.php?pg=2010&idres=<?= $dt['idres']; ?>&ope=eli" title="Eliminar" onclick="return eliminar();">
                                            <i class="fa-solid fa-trash-can  btn btn-warning"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="text-center">No hay reservas registradas.</div>
                <?php } ?>
            </div>

            <div class="mt-4 d-flex justify-content-start">
                <a href="index.php?pg=2010" class="btn btn-warning">
                    <i class="fas fa-plus"></i> Crear Reserva
                </a>
            </div>
        </div>
    </div>
</div>
