<?php
// listmas.php
require_once "../models/mmas.php";

// Crear objeto del modelo
$mascota = new Mascota();
$mascotas = $mascota->listarMascotas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Mascotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <h1 class="mb-4 text-center text-primary">📋 Lista de Mascotas Registradas</h1>

        <?php if (!empty($mascotas)): ?>
            <div class="table-responsive shadow-lg rounded-4">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Sexo</th>
                            <th>Peso</th>
                            <th>Raza</th>
                            <th>Edad</th>
                            <th>Tipo</th>
                            <th>Tamaño</th>
                            <th>Cuidado</th>
                            <th>Vacunas</th>
                            <th>Foto</th>
                            <th>Carnet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mascotas as $m): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['idmas']) ?></td>
                                <td><?= htmlspecialchars($m['nommas']) ?></td>
                                <td><?= htmlspecialchars($m['sexmas']) ?></td>
                                <td><?= htmlspecialchars($m['pesomas']) ?> kg</td>
                                <td><?= htmlspecialchars($m['razamas']) ?></td>
                                <td><?= htmlspecialchars($m['edadmas']) ?> años</td>
                                <td><?= htmlspecialchars($m['tipomas']) ?></td>
                                <td><?= htmlspecialchars($m['tammas']) ?></td>
                                <td><?= htmlspecialchars($m['cuidmas']) ?></td>
                                <td><?= htmlspecialchars($m['vacmas']) ?></td>
                                <td>
                                    <?php if (!empty($m['fotmas'])): ?>
                                        <img src="../<?= $m['fotmas'] ?>" width="80" class="rounded shadow">
                                    <?php else: ?>
                                        <span class="text-muted">Sin foto</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($m['carmas'])): ?>
                                        <img src="../<?= $m['carmas'] ?>" width="80" class="rounded shadow">
                                    <?php else: ?>
                                        <span class="text-muted">Sin carnet</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center rounded-4 shadow">
                ⚠️ No hay mascotas registradas todavía.
            </div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="vmas.php" class="btn btn-primary btn-lg me-2">➕ Registrar otra mascota</a>
            <a href="confirmacion.php" class="btn btn-secondary btn-lg">⬅️ Volver a confirmación</a>
        </div>
    </div>

</body>
</html>
