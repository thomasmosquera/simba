<?php
require_once "../models/mmas.php";
$modelo = new mmas();
$mascotas = $modelo->listarMascotas();
?>
<div class="container mt-5">
    <div class="alert alert-success text-center">
        ✅ ¡Mascota registrada con éxito!
    </div>

    <h2 class="text-warning text-center">Listado de Mascotas Registradas</h2>
    <table class="table table-bordered table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Sexo</th>
                <th>Tipo</th>
                <th>Edad</th>
                <th>Peso</th>
                <th>Raza</th>
                <th>Tamaño</th>
                <th>Vacunas</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $mascotas->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['idmasc'] ?></td>
                    <td><?= $row['nommasc'] ?></td>
                    <td><?= ucfirst($row['sexo']) ?></td>
                    <td><?= ucfirst($row['tipomasc']) ?></td>
                    <td><?= $row['edad'] ?> años</td>
                    <td><?= $row['peso'] ?> kg</td>
                    <td><?= $row['raza'] ?></td>
                    <td><?= $row['tamano'] ?> cm</td>
                    <td><?= $row['vacunas'] ?></td>
                    <td>
                        <img src="<?= $row['imagen'] ?>" width="60" height="60" class="rounded">
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
