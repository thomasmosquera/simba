<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require "libs/dom/vendor/autoload.php";

use Dompdf\Dompdf;

$dompdf = new Dompdf();

//$nombre_usuario = isset($_SESSION['nomusu']) ? strtoupper($_SESSION['nomusu']) : 'INVITADO';
//$email_usuario = $_SESSION['emausu'] ?? 'N/A';
//$telefono_usuario = $_SESSION['telusus'] ?? 'N/A';
//$direccion_usuario = $_SESSION['dirusu'] ?? 'N/A';

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
        h3.text-primary { color: #fd9d0dff; }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }
        .table thead { background: #e9ecef; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .mt-4 { margin-top: 1.5rem; }
        .mt-5 { margin-top: 3rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .total {
            font-size: 1.2rem;
            font-weight: bold;
            color: #fd9d0dff;
        }
    </style>
</head>
<body>
<div class="factura-box">
    <!-- Encabezado -->
    <div class="mb-4">
        <div style="float:left; width:50%;">
            <h3 class="text-primary">SIMBA</h3>
            <!--<p>Dirección: <?= $_SESSION['dirusu'] ?? 'N/A' ?></p>-->
            <!-- <p>Tel: <?= $_SESSION['telusus'] ?? 'N/A' ?></p>-->
            <p><strong>N. De Reserva:</strong> <?= $datOne['idres'] ?></p>
            <p><strong>Mascota:</strong> <?= $datOne['nommas'] ?></p>
            <p><strong>Fecha de Reserva:</strong> <?= $datOne['fecact'] ?></p>
            <p><strong>Cuidador:</strong> <?= $datOne['nomusu'] ?></p>
            <p><strong>Estado:</strong> <?= $datOne['estres'] ?></p>
        </div>
        <div style="float:right; width:50%; text-align:right;">
            <h5>Factura Nº: <?= $datOne['idres'] ?></h5>
            <p><strong>Fecha de emisión:</strong> <?= date("d/m/Y") ?></p>
        </div>
        <div style="clear:both;"></div>
    </div>

    <!-- Datos cliente -->
    <!-- <div class="mb-3"> -->
        <!-- <h5>Cliente</h5> -->
        <!-- <p><strong>Nombre:</strong> <?= $_SESSION['nomusu'] ?></p> -->
        <!-- <p><strong>Email:</strong> <?= $_SESSION['emausu'] ?></p> -->
        <!-- <p><strong>Teléfono:</strong> <?= $_SESSION['telusus'] ?></p> -->
    <!-- </div> -->

    <!-- Datos reserva -->
    <!-- <div class="mb-3">
        <!-- <h5>Reserva</h5> -->
        <!-- <p><strong>ID Reserva:</strong> <?= $datOne['idres'] ?></p> -->
        <!-- <p><strong>Mascota:</strong> <?= $datOne['nommas'] ?></p> -->
        <!-- <p><strong>Fecha de la reserva:</strong> <?= $datOne['fecact'] ?></p> -->
        <!-- <p><strong>Estado:</strong> <?= $datOne['estres'] ?></p> -->
    <!-- </div> -->

    <!-- Detalle servicios -->
    <table class="table">
        <thead>
            <tr>
                <th>Servicios</th>
                <th>Precio Unitario</th>
                <!-- <th>Cantidad</th> -->
                <!-- <th>Subtotal</th> -->
            </tr>
        </thead>
        <tbody>
            <?php 
            $subtotal = 0;
            foreach($datOne['servicios'] as $serv): 
                $precio = $serv['precio'] ?? 0;
                $cantidad = 1; // si siempre es 1, puedes ajustarlo
                $sub = $precio * $cantidad;
                $subtotal += $sub;
            ?>
            <tr>
                <td><?= $serv['nomser'] ?></td> 
                <td>$<?= number_format($precio, 0, ',', '.') ?></td> 
                <!-- <td><?= $cantidad ?></td> -->
                <!-- <td>$<?= number_format($sub, 0, ',', '.') ?></td> -->
            </tr>
            <?php endforeach; ?>
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
