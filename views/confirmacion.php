<?php
session_start();
// Asegúrate de que el usuario esté logueado
if (!isset($_SESSION["idusu"])) {
    header("Location: vmas.php?status=sin_usuario");
    exit;
}

$nombreMascota = $_GET["nombre"] ?? "la mascota";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body text-center p-5">
                <h2 class="text-success mb-4">✅ Registro exitoso</h2>
                <p class="fs-5">La mascota <strong><?php echo htmlspecialchars($nombreMascota); ?></strong> ha sido registrada correctamente.</p>
                <hr>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="vmas.php" class="btn btn-primary">➕ Registrar otra mascota</a>
                    <a href="listmas.php" class="btn btn-outline-success">📋 Ver todas las mascotas</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
