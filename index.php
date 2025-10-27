<?php
define('BASE_PATH', __DIR__ . '/');

// Inicia la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pg = $_GET['pg'] ?? 1001;

$mensaje_exito = $_GET['mensaje'] ?? null;
$mensaje_error = $_GET['error'] ?? null;

// --- MAPA DE VISTAS ---
$rutas_paginas = [
    1001 => 'vini.php', 1002 => 'volv.php', 1003 => 'vrec.php', 1004 => 'vrep.php',
    1006 => 'vreg.php', 1007 => 'ccsesion.php', 1008 => 'vper.php', 1009 => 'vmas.php',
    1010 => 'vres.php', 1011 => 'vser.php', 1015 => 'vevi.php', 1016 => 'vpag.php',
    2010 => 'vres2.php', 'dashboard' => 'vdashboard.php', 'admin_page' => 'vadmin.php',
    'client_page' => 'vclient.php', 'caregiver_page' => 'vcaregiver.php'
];

$ruta_actual_vista = $rutas_paginas[$pg] ?? null;

$paginas_publicas = [1001, 1002, 1003, 1007];

if (!isset($_SESSION['idusu']) && !in_array($pg, $paginas_publicas)) {
    header("Location: index.php?pg=1001&error=" . urlencode("Debes iniciar sesión para acceder."));
    exit();
}

if (isset($_SESSION['idusu']) && in_array($pg, [1001, 1002, 1003])) {
    header("Location: index.php?pg=dashboard");
    exit();
}

if (isset($_SESSION['idusu']) && !in_array($pg, $paginas_publicas)) {
    $paginas_permitidas_por_perfil = [
        'ADMINISTRADOR' => ['dashboard', 1004, 1006, 1008, 1009, 1010, 2010, 1011, 1015, 1016],
        'CLIENTE' => ['dashboard', 1004, 1009, 1010, 2010],
        'CUIDADOR' => ['dashboard', 1004, 1010, 2010, 1015],
    ];

    $perfil_sesion = strtoupper($_SESSION['tipo_perfil'] ?? '');
    if ($pg !== 'dashboard' && $perfil_sesion && isset($paginas_permitidas_por_perfil[$perfil_sesion])) {
        if (!in_array($pg, $paginas_permitidas_por_perfil[$perfil_sesion])) {
            header("Location: index.php?pg=dashboard&error=" . urlencode("No tienes permisos para acceder a esta página."));
            exit();
        }
    } elseif ($pg !== 'dashboard') {
        header("Location: index.php?pg=dashboard&error=" . urlencode("Perfil no reconocido o sin permisos asignados."));
        exit();
    }
}

// FUNCION IMPORTANTE DE RESERVAS
if (in_array($pg, [1010, 2010])) {
    require_once BASE_PATH . 'controllers/cres.php';
}

// CIERRE DE SESIÓN
if ($pg == 1007) {
    require_once BASE_PATH . 'controllers/ccsesion.php';
    exit();
}

// PÁGINA INVÁLIDA

if ($ruta_actual_vista === null) {
    if (isset($_SESSION['idusu'])) {
        header("Location: index.php?pg=dashboard&error=" . urlencode("La página solicitada no existe o no tienes acceso."));
    } else {
        header("Location: index.php?pg=1001&error=" . urlencode("La página solicitada no existe."));
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMBA</title>
    <link rel="shortcut icon" href="img/iconosimba.ico" type="image/x-icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">

    <style>
        .alert-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 500px;
            z-index: 1050;
            padding: 0 15px;
        }
        html, body { overflow-x: hidden; overflow-y: auto; width: 100%; margin: 0; padding: 0; }
        * { box-sizing: border-box; }
    </style>
</head>
<body class="bg-dark">

    <img src="img/logo1.png" alt="Simba Background" class="bg-lion-bottom-right">

    <?php 
       if (isset($_SESSION['idusu']) && !in_array($pg, [1001, 1002, 1003, 1007])) {
        require_once BASE_PATH . 'views/navbar.php';
    }
    ?>

    <div class="alert-container">
        <?php if ($mensaje_exito): ?>
            <div id="successAlert" class="alert alert-success text-center"><?= htmlspecialchars($mensaje_exito) ?></div>
        <?php endif; ?>
        <?php if ($mensaje_error): ?>
            <div id="errorAlert" class="alert alert-danger text-center"><?= htmlspecialchars($mensaje_error) ?></div>
        <?php endif; ?>
    </div>

    <div class="container-fluid main-app-container">
        <?php
        // MÓDULOS CON CONTROLADOR PROPIO
        // Gestión de perfiles
        if ($pg == 1008) {
            require_once BASE_PATH . 'models/conexion.php';
            require_once BASE_PATH . 'models/mper.php';
            require_once BASE_PATH . 'controllers/cper.php';

            try {
                $conn = new conexion(); 
                $mper = new Mper($conn);
                $cper = new Cper($mper);
                $cper->run();
            } catch (Exception $e) {
                $mensaje_error = "Error al cargar perfiles: " . $e->getMessage();
                $pg = 'dashboard';
                $ruta_actual_vista = $rutas_paginas[$pg];
            }
        }

        // Gestión de páginas
        elseif ($pg == 1016) {
            require_once BASE_PATH . 'views/vpag.php';
        }

        // Recuperación de contraseña
        elseif (in_array($pg, [1002, 1003])) {
            require_once BASE_PATH . 'controllers/colv.php';
        }

        // CARGA DE VISTAS NORMALES
        elseif ($ruta_actual_vista && file_exists(BASE_PATH . 'views/' . $ruta_actual_vista)) {
            require_once BASE_PATH . 'views/' . $ruta_actual_vista;
        } else {
            echo "<h2 class='text-center text-white mt-5'>Página no disponible. Contacta al soporte.</h2>";
        }
        ?>
    </div>

    <!-- Modal de cierre de sesión -->
    <div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-warning">
                <div class="modal-header border-bottom border-warning">
                    <h5 class="modal-title" id="confirmLogoutModalLabel">
                        <i class="fa-solid fa-circle-exclamation text-warning me-2"></i> Confirmar Cierre de Sesión
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">¿Estás seguro de que quieres cerrar tu sesión actual?</div>
                <div class="modal-footer border-top border-warning">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Cancelar
                    </button>
                    <a href="index.php?pg=1007" class="btn btn-warning">
                        <i class="fa-solid fa-check me-1"></i> Sí, cerrar sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="scripts/bootstrap.bundle.min.js"></script>
    <script>
        function hideAlert(alertId) {
            const alertElement = document.getElementById(alertId);
            if (alertElement) {
                const bsAlert = new bootstrap.Alert(alertElement);
                setTimeout(() => bsAlert.close(), 2000);
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            hideAlert('successAlert');
            hideAlert('errorAlert');
        });
    </script>
</body>
</html>
