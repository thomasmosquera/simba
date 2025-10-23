<?php
// Define la ruta base absoluta del proyecto
define('BASE_PATH', __DIR__ . '/');

// Inicia la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determina la página actual desde el parámetro 'pg', por defecto 1001 (login)
$pg = $_GET['pg'] ?? 1001;

// Mensajes opcionales desde la URL
$mensaje_exito = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : null;
$mensaje_error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;

// Mapa de rutas de vistas
$rutas_paginas = [
    1001 => 'vini.php',
    1002 => 'volv.php',
    1003 => 'vrec.php',
    1004 => 'vrep.php',
    1006 => 'vreg.php',
    1007 => 'ccsesion.php',
    1008 => 'vper.php',
    1009 => 'vmas.php',
    1010 => 'vres.php',
    1011 => 'vser.php',
    1015 => 'vevi.php',
    1016 => 'vpag.php', // NUEVA ENTRADA: Módulo de gestión de páginas
    2010 => 'vres2.php',
    'dashboard' => 'vdashboard.php',
    'admin_page' => 'vadmin.php',
    'client_page' => 'vclient.php',
    'caregiver_page' => 'vcaregiver.php'
];

// Obtiene la ruta real del archivo de vista
$ruta_actual_vista = $rutas_paginas[$pg] ?? null;

// --- INICIO DE LÓGICA DE CONTROL DE ACCESO ---
$paginas_publicas = [1001, 1002, 1003, 1007];

if (!isset($_SESSION['idusu']) && !in_array($pg, $paginas_publicas)) {
    header("Location: index.php?pg=1001&error=" . urlencode("Debes iniciar sesión para acceder."));
    exit();
}

if (isset($_SESSION['idusu']) && in_array($pg, [1001, 1002, 1003])) {
    header("Location: index.php?pg=dashboard");
    exit();
}

 if (in_array($pg, [1010, 2010])) {
            require_once BASE_PATH . 'controllers/cres.php';
    }
    if (isset($_SESSION['idusu']) && !in_array($pg, $paginas_publicas)) {
    $paginas_permitidas_por_perfil = [
        // Añadimos 2010 (vres2.php) a los mismos perfiles que tienen acceso a 1010 (vres.php)
        'ADMINISTRADOR' => ['dashboard', 1004, 1006, 1008, 1009, 1010, 2010, 1011, 1015, 1016], // 1008 y 1016
        'CLIENTE' => ['dashboard', 1004, 1009, 1010, 2010],
        'CUIDADOR' => ['dashboard', 1004, 1010, 2010, 1015],
    ];

    $perfil_sesion = strtoupper($_SESSION['tipo_perfil'] ?? '');
    
    if ($pg === 'dashboard') {
        // Acceso concedido al dashboard.
    } elseif ($perfil_sesion && isset($paginas_permitidas_por_perfil[$perfil_sesion])) {
        if (!in_array($pg, $paginas_permitidas_por_perfil[$perfil_sesion])) {
            header("Location: index.php?pg=dashboard&error=" . urlencode("No tienes permisos para acceder a esta página."));
            exit();
        }
    } else {
        header("Location: index.php?pg=dashboard&error=" . urlencode("Perfil no reconocido o sin permisos asignados."));
        exit();
    }
}
// --- FIN DE LÓGICA DE CONTROL DE ACCESO ---

// Cierre de sesión
if ($pg == 1007) {
    require_once BASE_PATH . 'controllers/ccsesion.php';
    exit();
}

// Página inválida
if ($ruta_actual_vista === null) {
    if (isset($_SESSION['idusu'])) {
        header("Location: index.php?pg=dashboard&error=" . urlencode("La página solicitada no existe o no tienes acceso."));
    } else {
        $pg = 1001;
        $ruta_actual_vista = $rutas_paginas[$pg];
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
</head>

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
        html, body {
            overflow-x: hidden;
            overflow-y: auto;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        * { box-sizing: border-box; }
        .container-fluid, .container {
            padding: 0 15px;
            margin: auto;
            max-width: 100%;
        }
        .d-flex.min-vh-100 {
            width: 100vw;
            box-sizing: border-box;
        }
    </style>
</head>
<body class="bg-dark">

    <img src="img/logo1.png" alt="Simba Background" class="bg-lion-bottom-right">

    <?php if (isset($_SESSION['idusu'])): ?>
        <?php require_once BASE_PATH . 'views/navbar.php'; ?>
    <?php endif; ?>

    <div class="alert-container">
        <?php if ($mensaje_exito): ?>
            <div id="successAlert" class="alert alert-success text-center"><?= $mensaje_exito ?></div>
        <?php endif; ?>

        <?php if ($mensaje_error): ?>
            <div id="errorAlert" class="alert alert-danger text-center"><?= $mensaje_error ?></div>
        <?php endif; ?>
    </div>

    <div class="container-fluid main-app-container">
        <?php
        // Controlador específico para gestión de perfiles (pg=1008)
        if ($pg == 1008) {
            require_once BASE_PATH . 'models/conexion.php';
            require_once BASE_PATH . 'models/mper.php';
            require_once BASE_PATH . 'controllers/cper.php';

            try {
                // USANDO $conn para ser compatible con archivos de compañeros
                $conn = new conexion(); 
                $mper = new Mper($conn);
                $cper = new Cper($mper);
                $cper->run();
                exit; 
            } catch (Exception $e) {
                $mensaje_error = "Error de sistema al cargar perfiles: " . $e->getMessage();
                $pg = isset($_SESSION['idusu']) ? 'dashboard' : 1001;
                $ruta_actual_vista = $rutas_paginas[$pg];
            }
        }

        // NUEVO: Controlador específico para gestión de páginas (pg=1016)
        if ($pg == 1016) {
            require_once BASE_PATH . 'models/conexion.php';
            require_once BASE_PATH . 'models/mpag.php';
            require_once BASE_PATH . 'controllers/cpag.php';

            try {
                // USANDO $conn, compatible con archivos de compañeros
                $conn = new conexion(); 
                $mpag = new Mpag($conn);
                $cpag = new Cpag($mpag);
                $cpag->run();
                exit; 
            } catch (Exception $e) {
                $mensaje_error = "Error de sistema al cargar la gestión de páginas: " . $e->getMessage();
                $pg = isset($_SESSION['idusu']) ? 'dashboard' : 1001;
                $ruta_actual_vista = $rutas_paginas[$pg];
            }
        }

        // Controlador para recuperación de contraseña
        if (in_array($pg, [1002, 1003])) {
            // Este controlador y sus modelos internos deben estar adaptados a la conexión PDO
            require_once BASE_PATH . 'controllers/colv.php';
        }

        // Controlador para gestión de reservas (pg=1010 y 2010)
        if (in_array($pg, [1010, 2010])) {
            require_once BASE_PATH . 'controllers/cres.php';
        }

        // Carga de la vista correspondiente (si no se ha hecho exit antes)
        if ($ruta_actual_vista && file_exists(BASE_PATH . 'views/' . $ruta_actual_vista)) {
            require_once BASE_PATH . 'views/' . $ruta_actual_vista;
        } else {
            echo "<h2 class='text-center text-white mt-5'>Página no disponible. Por favor, contacta al soporte si crees que esto es un error.</h2>";
        }
        ?>
    </div>

    <div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-warning">
                <div class="modal-header border-bottom border-warning">
                    <h5 class="modal-title" id="confirmLogoutModalLabel">
                        <i class="fa-solid fa-circle-exclamation text-warning me-2"></i> Confirmar Cierre de Sesión
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres cerrar tu sesión actual?
                </div>
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