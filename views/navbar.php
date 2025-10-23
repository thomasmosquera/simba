 <?php
  // Define BASE_PATH si no está definido (para conexiones a BD)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/../'); // Ajusta la ruta según la ubicación de tu index.php
}

// Ítems estáticos del menú
$menu_items = [
    'dashboard'  => ['text' => 'Inicio',    'icon' => 'fas fa-home'],
    'vreg.php'   => ['text' => 'Registro',  'icon' => 'fas fa-user-circle'],
    'vrep.php'   => ['text' => 'Reportes',  'icon' => 'fas fa-history me-1'],
    'vper.php'   => ['text' => 'Perfiles',  'icon' => 'fas fa-id-card-alt'],
    'vmas.php'   => ['text' => 'Mascotas',  'icon' => 'fas fa-paw'],
    'vser.php'   => ['text' => 'Servicios', 'icon' => 'fas fa-tasks'],
    'vres.php'   => ['text' => 'Reservas',  'icon' => 'fa-solid fa-paw'],
    'vevi.php'   => ['text' => 'Evidencia', 'icon' => 'fa-solid fa-window-restore'],
    'vpag.php'   => ['text' => 'Gestión Páginas', 'icon' => 'fas fa-file-alt'],
];

// Map estático de páginas
$page_id_map = [
    'dashboard'  => 'dashboard',
    'vrep.php'   => 1004,
    'vreg.php'   => 1006,
    'vper.php'   => 1008,
    'vmas.php'   => 1009,
    'vres.php'   => 1010,
    'vser.php'   => 1011,
    'vevi.php'   => 1015,
    'vpag.php'   => 1016,
];

// Datos de sesión
$usuario_logueado = isset($_SESSION['idusu']);
$nombre_usuario   = isset($_SESSION['nomusu']) ? strtoupper($_SESSION['nomusu']) : 'INVITADO';
$tipo_perfil      = isset($_SESSION['tipo_perfil']) ? strtoupper($_SESSION['tipo_perfil']) : 'NO LOGUEADO';

// Si está logueado pero sin nombre, consultamos BD
if ($usuario_logueado && ($nombre_usuario === 'INVITADO' || empty($nombre_usuario))) {
    $mysqliTmp = @new mysqli("localhost", "root", "", "simba");
    if ($mysqliTmp && !$mysqliTmp->connect_error) {
        if ($stmtU = $mysqliTmp->prepare("SELECT UPPER(nomusu) AS nom FROM usuario WHERE idusu = ? LIMIT 1")) {
            $stmtU->bind_param("i", $_SESSION['idusu']);
            $stmtU->execute();
            $resU = $stmtU->get_result();
            if ($rowU = $resU->fetch_assoc()) {
                $nombre_usuario = $rowU['nom'] ?: 'INVITADO';
            }
            $stmtU->close();
        }
        $mysqliTmp->close();
    }
}

// Mapeo de íconos
$icon_map_db = [
    'dashboard' => 'fas fa-home',
    1006 => 'fas fa-user-circle',
    1004 => 'fas fa-history me-1',
    1008 => 'fas fa-id-card-alt',
    1009 => 'fas fa-paw',
    1011 => 'fas fa-tasks',
    1010 => 'fa-solid fa-paw',
    1015 => 'fa-solid fa-window-restore',
    1016 => 'fas fa-file-alt'
];
$default_icon = 'fas fa-circle';

// Si el usuario tiene perfil, consultamos páginas permitidas
if ($usuario_logueado && $tipo_perfil !== 'NO LOGUEADO') {
    $mysqli = @new mysqli("localhost", "root", "", "simba");
    if ($mysqli && !$mysqli->connect_error) {
        $idper = null;

        // Buscar idper según el nombre del perfil
        if ($stmt = $mysqli->prepare("SELECT idper FROM perfil WHERE UPPER(nomper) = ? LIMIT 1")) {
            $stmt->bind_param("s", $tipo_perfil);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $idper = (int)$row['idper'];
            }
            $stmt->close();
        }

        // Si encontramos el perfil, obtenemos sus páginas
        if ($idper) {
            $sql = "SELECT p.idpag, p.nompag, p.rutpag, p.mospag
                    FROM pagina p
                    INNER JOIN pxp x ON x.idpag = p.idpag
                    WHERE x.idper = ? AND (p.mospag = 1 OR p.mospag IS NULL)
                    ORDER BY p.nompag ASC"; // <- corregido, solo ordena por nompag

            if ($stmt2 = $mysqli->prepare($sql)) {
                $stmt2->bind_param("i", $idper);
                $stmt2->execute();
                $res2 = $stmt2->get_result();

                while ($pag = $res2->fetch_assoc()) {
                    $key  = (string)$pag['rutpag'];
                    $text = $pag['nompag'] ?? 'Página';
                    $icon = $icon_map_db[$pag['idpag']] ?? $default_icon;

                    // Mapear idpag real
                    $page_id_map[$key] = (string)$pag['idpag'];
                    $menu_items[$key]  = ['text' => $text, 'icon' => $icon];
                }
                $stmt2->close();
            }
        }
        $mysqli->close();
    }
}
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
    <div class="container-fluid px-4 py-3">
        <a class="navbar-brand" href="index.php?pg=<?= $usuario_logueado ? 'dashboard' : '1001' ?>">
            <img src="img/logo1.png" alt="Logo SIMBA" class="logo-simba-nav me-2" style="height: 35px; opacity: 0.7;">
            <span style="font-size: 1.5rem; font-weight: bold; color: #ffc107;">SIMBA</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <!-- Usuario logueado -->
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

                            // Visibilidad según rol
                            if ($tipo_perfil == 'ADMINISTRADOR') {
                                $mostrar_enlace = true;
                            } elseif ($tipo_perfil == 'CLIENTE' && in_array($ruta_archivo, ['dashboard', 'vmas.php', 'vres.php'])) {
                                $mostrar_enlace = true;
                            } elseif ($tipo_perfil == 'CUIDADOR' && in_array($ruta_archivo, ['dashboard', 'vevi.php', 'vres.php'])) {
                                $mostrar_enlace = true;
                            }

                            // Perfiles y gestión páginas solo ADMIN
                            if (($pg_id == 1008 || $pg_id == 1016) && $tipo_perfil != 'ADMINISTRADOR') {
                                $mostrar_enlace = false;
                            }

                            if ($mostrar_enlace): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?pg=<?= $pg_id ?>">
                                        <i class="<?= $details['icon'] ?> me-1"></i> <?= $details['text'] ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endif;
                    endforeach; ?>

                    <!-- Botón logout -->
                    <li class="nav-item">
                        <button type="button" class="nav-link btn btn-link text-white" 
                                data-bs-toggle="modal" data-bs-target="#confirmLogoutModal" 
                                style="text-decoration: none;">
                            <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                        </button>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>