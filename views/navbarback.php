<?php
$menu_items = [
    'vdashboard.php' => ['text' => 'Inicio', 'icon' => 'fas fa-home'],
    'vreg.php'       => ['text' => 'Registro', 'icon' => 'fas fa-user-circle'],
    'vrep.php'   => ['text' => 'Reportes', 'icon' => 'fas fa-history me-1'],
    'vper.php'       => ['text' => 'Perfiles', 'icon' => 'fas fa-id-card-alt'],
    'vmas.php'       => ['text' => 'Mascotas', 'icon' => 'fas fa-paw'],
    'vser.php'       => ['text' => 'Servicios', 'icon' => 'fas fa-tasks'],
    'vres.php'       => ['text' => 'Reservas', 'icon' => 'fa-solid fa-paw'],
    'vevi.php'       => ['text' => 'Evidencia', 'icon' => 'fa-solid fa-window-restore'],
    'client_page'    => ['text' => 'Mis Reservas', 'icon' => 'fas fa-calendar-alt'],
    'caregiver_page' => ['text' => 'Mis Tareas', 'icon' => 'fas fa-clipboard-list']
];

$page_id_map = [
    'vdashboard.php' => 'dashboard',
    'vrep.php'       => 1004,
    'vreg.php'       => 1006,
    'vper.php'       => 1008,
    'vmas.php'       => 1009,
    'vres.php'       => 1010, 
    'vser.php'       => 1011,
    'vevi.php'       => 1015,
    'admin_page'     => 'admin_page',
    'client_page'    => 'client_page',
    'caregiver_page' => 'caregiver_page'
];

$usuario_logueado = isset($_SESSION['idusu']);
$nombre_usuario = isset($_SESSION['nomusu']) ? strtoupper($_SESSION['nomusu']) : 'Invitado';
$tipo_perfil = isset($_SESSION['tipo_perfil']) ? strtoupper($_SESSION['tipo_perfil']) : 'NO LOGUEADO';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
    <div class="container-fluid px-4 py-3">
        <a class="navbar-brand" href="index.php?pg=<?= $usuario_logueado ? 'dashboard' : '1001' ?>">
            <img src="img/logo1.png" alt="Logo SIMBA" class="logo-simba-nav me-2" style="height: 35px; opacity: 0.7;">
            <span style="font-size: 1.5rem; font-weight: bold; color: #ffc107;">SIMBA</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link text-warning">
                        <i class="fas fa-user-circle me-1"></i> Hola, <?= $nombre_usuario ?> (<?= $tipo_perfil ?>)
                    </span>
                </li>

                <?php if ($usuario_logueado): ?>
                    <?php foreach ($menu_items as $ruta_archivo => $details):
                        $pg_id = $page_id_map[$ruta_archivo] ?? null;
                        if ($pg_id !== null):
                            $mostrar_enlace = false;
                            switch ($ruta_archivo) {
                                case 'admin_page':
                                case 'vlistusu.php':
                                case 'vper.php':
                                    if ($tipo_perfil == 'ADMIN') $mostrar_enlace = true;
                                    break;
                                case 'client_page':
                                    if ($tipo_perfil == 'CLIENTE') $mostrar_enlace = true;
                                    break;
                                case 'caregiver_page':
                                    if ($tipo_perfil == 'EMPLEADO') $mostrar_enlace = true;
                                    break;
                                default:
                                    $mostrar_enlace = true;
                                    break;
                            }
                            if ($mostrar_enlace):
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?pg=<?= $pg_id ?>">
                                        <i class="<?= $details['icon'] ?> me-1"></i> <?= $details['text'] ?>
                                    </a>
                                </li>
                                <?php
                            endif;
                        endif;
                    endforeach;
                    ?>
                    <li class="nav-item">
                        <button type="button" class="nav-link btn btn-link text-white" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal" style="text-decoration: none;">
                            <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                        </button>
                    </li>
                <?php else: ?>
                    <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>