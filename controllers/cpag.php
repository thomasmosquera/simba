<?php
// ============================================================
// cpag.php — Controlador de Páginas (AJAX / SIMBA)
// ============================================================
error_reporting(0); // evitar que notices rompan salida
ob_start();

require_once("../models/mpag.php");

// Debug simple a archivo local (borrar en producción)
function cpag_log($msg) {
    try {
        $f = __DIR__ . '/cpag.debug.log';
        @file_put_contents($f, date('Y-m-d H:i:s') . ' ' . $msg . "\n", FILE_APPEND);
    } catch (_) {}
}

try {
    // Crea PDO directamente usando credenciales del modelo para evitar problemas de include relativos
    require_once("../models/data.php");
    $port = getenv('SIMBA_DB_PORT');
    if (!$port) { $port = '3306'; }

    $dsn1 = "mysql:host=127.0.0.1;port={$port};dbname={$db};charset=utf8";
    $dsn2 = "mysql:host={$host};port={$port};dbname={$db};charset=utf8";
    $lastErr = null;
    try {
        $conexion = new PDO($dsn1, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        cpag_log('INIT OK via 127.0.0.1:' . $port);
    } catch (Exception $e1) {
        $lastErr = $e1;
        cpag_log('INIT FAIL 127.0.0.1:' . $port . ' -> ' . $e1->getMessage());
        $conexion = new PDO($dsn2, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        cpag_log('INIT OK via host=' . $host . ' port=' . $port);
    }
    $modelo = new Mpag($conexion);
} catch (Exception $e) {
    @ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error de conexión: " . $e->getMessage()
    ]);
    cpag_log('INIT FAIL: ' . $e->getMessage());
    exit;
}

$accion = $_POST['accion'] ?? null;
cpag_log('ACCION=' . var_export($accion, true) . ' POST=' . json_encode($_POST));

if (!$accion) {
    @ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["estado" => "error", "mensaje" => "No se recibió ninguna acción."]);
    exit;
}

try {
    switch ($accion) {
        // 🔹 LISTAR (devuelve HTML)
        case 'listar':
            cpag_log('CASE listar');
            $datos = $modelo->listarPaginas();
            @ob_end_clean();
            header('Content-Type: text/html; charset=utf-8');
            foreach ($datos as $pagina) {
                $id   = (int)$pagina['idpag'];
                $nom  = $pagina['nompag'];
                $rut  = $pagina['rutpag'];
                $mos  = (int)$pagina['mospag'];
                // Usa json_encode para generar literales JS seguros
                $jsId  = json_encode($id);
                $jsNom = json_encode($nom);
                $jsRut = json_encode($rut);
                $jsMos = json_encode($mos);
                echo "<tr>
                        <td>{$id}</td>
                        <td>" . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($rut, ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . ($mos === 1 ? 'Sí' : 'No') . "</td>
                        <td>
                            <button class='btn btn-sm btn-outline-warning me-1'
                                onclick='editarPagina({$jsId}, {$jsNom}, {$jsRut}, {$jsMos})'>
                                <i class='fa-solid fa-pen'></i>
                            </button>
                            <button class='btn btn-sm btn-outline-danger' onclick='eliminarPagina({$id})'>
                                <i class='fa-solid fa-trash'></i>
                            </button>
                        </td>
                    </tr>";
            }
            if (empty($datos)) {
                echo "<tr><td colspan='5' class='text-center'>No hay páginas registradas.</td></tr>";
            }
            exit;

        // 🔹 INSERTAR (devuelve JSON)
        case 'insertar':
            cpag_log('CASE insertar (inicio)');
            $nompag = trim($_POST['nompag'] ?? '');
            $rutpag = trim($_POST['rutpag'] ?? '');
            $mospag = isset($_POST['mospag']) ? (int)$_POST['mospag'] : 0;

            @ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            if ($nompag === '' || $rutpag === '') {
                echo json_encode(["estado" => "error", "mensaje" => "Todos los campos son obligatorios."]);
                exit;
            }

            cpag_log('INSERT datos: nom=' . $nompag . ' rut=' . $rutpag . ' mos=' . $mospag);
            $modelo->insertarPagina($nompag, $rutpag, $mospag);
            cpag_log('INSERT ok');
            echo json_encode(["estado" => "ok", "mensaje" => "Página registrada correctamente."]);
            exit;

        // 🔹 ACTUALIZAR (devuelve JSON)
        case 'actualizar':
            cpag_log('CASE actualizar (inicio)');
            $idpag = isset($_POST['idpag']) ? (int)$_POST['idpag'] : null;
            $nompag = trim($_POST['nompag'] ?? '');
            $rutpag = trim($_POST['rutpag'] ?? '');
            $mospag = isset($_POST['mospag']) ? (int)$_POST['mospag'] : 0;

            @ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            if (!$idpag || $nompag === '' || $rutpag === '') {
                echo json_encode(["estado" => "error", "mensaje" => "Faltan datos para actualizar."]);
                exit;
            }

            cpag_log('UPDATE datos: id=' . $idpag . ' nom=' . $nompag . ' rut=' . $rutpag . ' mos=' . $mospag);
            $modelo->actualizarPagina($idpag, $nompag, $rutpag, $mospag);
            cpag_log('UPDATE ok');
            echo json_encode(["estado" => "ok", "mensaje" => "Página actualizada correctamente."]);
            exit;

        // 🔹 ELIMINAR (devuelve JSON)
        case 'eliminar':
            cpag_log('CASE eliminar (inicio)');
            $idpag = isset($_POST['idpag']) ? (int)$_POST['idpag'] : null;

            @ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            if (!$idpag) {
                echo json_encode(["estado" => "error", "mensaje" => "No se especificó la página a eliminar."]);
                exit;
            }

            cpag_log('DELETE id=' . $idpag);
            $modelo->eliminarPagina($idpag);
            cpag_log('DELETE ok');
            echo json_encode(["estado" => "ok", "mensaje" => "Página eliminada correctamente."]);
            exit;

        default:
            @ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            cpag_log('ACCION invalida');
            echo json_encode(["estado" => "error", "mensaje" => "Acción no válida."]);
            exit;
    }
} catch (Exception $e) {
    @ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["estado" => "error", "mensaje" => "Error del sistema: " . $e->getMessage()]);
    cpag_log('EXCEPTION: ' . $e->getMessage());
    exit;
}
?>
