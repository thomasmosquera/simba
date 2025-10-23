<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/mini.php';       // UsuarioModel (no se modifica)
require_once __DIR__ . '/../models/conexion.php';   // clase conexion (usa PDO)

$userData = null;

// Si en sesión está la cédula, usar buscarPorNombre de mini
if (!empty($_SESSION['cedusu'])) {
    $um = new UsuarioModel();
    $userData = $um->buscarPorNombre($_SESSION['cedusu']);
}

// Si no hay cedula pero sí idusu, obtener datos directo desde la BD
if (!$userData && !empty($_SESSION['idusu'])) {
    try {
        $modelo = new conexion();
        $pdo = $modelo->get_conexion();
        $stmt = $pdo->prepare("SELECT idusu, cedusu, nomusu, emausu, telusus, dirusu FROM usuario WHERE idusu = :idusu LIMIT 1");
        $stmt->execute([':idusu' => $_SESSION['idusu']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (Exception $e) {
        $userData = null;
    }
}

// Si obtuviste datos, poblar $_SESSION y variables seguras para la vista
if ($userData) {
    $_SESSION['idusu']   = $userData['idusu']   ?? $_SESSION['idusu'];
    $_SESSION['cedusu']  = $userData['cedusu']  ?? $_SESSION['cedusu'];
    $_SESSION['nomusu']  = $userData['nomusu']  ?? $_SESSION['nomusu'];
    $_SESSION['emausu']  = $userData['emausu']  ?? $_SESSION['emausu'];
    $_SESSION['telusus'] = $userData['telusus'] ?? $_SESSION['telusus'];
    $_SESSION['dirusu']  = $userData['dirusu']  ?? $_SESSION['dirusu'];
}

$nombre_usuario    = $_SESSION['nomusu']  ?? 'No disponible';
$email_usuario     = $_SESSION['emausu']  ?? 'No disponible';
$telefono_usuario  = $_SESSION['telusus'] ?? 'No disponible';
$direccion_usuario = $_SESSION['dirusu']  ?? 'No disponible';

require "libs/dom/vendor/autoload.php";

use Dompdf\Dompdf;

$dompdf = new Dompdf();

// Verificar sesión y datos
if (!isset($_SESSION['idusu'])) {
    // Redirigir si no hay sesión
    header('Location: index.php?pg=login');
    exit;
}

ob_start();
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <style>
        body {
            background: #f8f9fa;
            padding: 30px;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .factura-box {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        h3.text-primary { color: #ffc107; }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .mt-4 { margin-top: 1.5rem; }
        .mt-5 { margin-top: 3rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .total {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ffc107;
        }
        h5 {
            color: #ffc107;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="factura-box">
    <!-- Encabezado -->
    <div class="mb-4">
        <div style="float:left; width:50%;">
            <h3 class="text-primary" style="font-size:20px;">SIMBA</h3>
        </div>
        <div style="float:right; width:50%; text-align:right;">
            <h5>Factura Nº: <?= $datOne['idres'] ?></h5>
            <p><strong>Fecha de emisión:</strong> <?= date("d/m/Y") ?></p>
        </div>
        <div style="clear:both;"></div>
    </div>

    <!-- Datos cliente y reserva en la MISMA tabla -->
    <table class="table">
        <tbody>
            <tr>
                <th>Nombre del cliente:</th>
                <td><?= htmlspecialchars($nombre_usuario) ?></td>
                <th>N. Reserva:</th>
                <td><?= htmlspecialchars($datOne['idres'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Correo del cliente:</th>
                <td><?= htmlspecialchars($email_usuario) ?></td>
                <th>Cuidador asignado:</th>
                <td><?= htmlspecialchars($datOne['nomusu'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Teléfono del cliente:</th>
                <td><?= htmlspecialchars($telefono_usuario) ?></td>
                <th>Mascota:</th>
                <td><?= htmlspecialchars($datOne['nommas'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Dirección del cliente:</th>
                <td><?= htmlspecialchars($direccion_usuario) ?></td>
                <th>Fecha y hora de la reserva:</th>
                <td><?= htmlspecialchars($datOne['fecact'] ?? '-') ?></td>
            </tr>
            <tr>
                <th></th>
                <td></td>
                <th>Estado:</th>
                <td><?= htmlspecialchars($datOne['estres'] ?? '-') ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Detalle servicios -->
    <?php
    // Calcular subtotal a partir de la variable 'preser' de cada servicio
    $subtotal = 0.0;
    if (!empty($datOne['servicios']) && is_array($datOne['servicios'])) {
        foreach ($datOne['servicios'] as $servicio) {
            // asegurar que preser exista y sea numérico
            $precio = isset($servicio['preser']) ? floatval($servicio['preser']) : 0.0;
            $subtotal += $precio;
        }
    }
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>Servicios</th>
                <th class="text-end">Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($datOne['servicios']) && is_array($datOne['servicios'])): ?>
                <?php foreach ($datOne['servicios'] as $servicio): ?>
                <tr>
                    <td><?= htmlspecialchars($servicio['nomser'] ?? '-') ?></td>
                    <td class="text-end">$<?= number_format(floatval($servicio['preser'] ?? 0), 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">No hay servicios registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Resumen -->
    <?php
    $iva = $subtotal * 0.19;
    $total = $subtotal + $iva;
    ?>
    <div class="text-end mt-4">
        <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 0, ',', '.') ?></p>
        <p><strong>IVA (19%):</strong> $<?= number_format($iva, 0, ',', '.') ?></p>
        <p class="total">Total a pagar: $<?= number_format($total, 0, ',', '.') ?></p>
    </div>

    <!-- Pie de factura -->
    <div class="mt-5 text-center">
        <p><small>Factura generada electrónicamente - No requiere firma</small></p>
    </div>
</div>
</body>
</html>
<?php
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait'); // portrait mejor que landscape para factura
$dompdf->render();
$dompdf->stream("factura.pdf", ["Attachment" => false]);
?>
