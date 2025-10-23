<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../models/mevi.php';

$pdo = (new conexion())->get_conexion();
$evi = new Evidencia($pdo);

// Crear evidencia
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['update'])) {
    $idres     = $_POST["idreser"];
    $desevi    = $_POST["descripcion"];
    $fecevi    = $_POST["fechevi"];
    $resp      = $_POST["responsable"];

    // Subida de archivo
    $arcevi = "";
    if (!empty($_FILES["archivo"]["name"])) {
        $ruta = "../views/uploads/";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $arcevi = basename($_FILES["archivo"]["name"]);
        move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta . $arcevi);
    }

    $tipevi   = $_POST["tipo"];
    if ($evi->insertar($idres, $tipevi, $arcevi, $desevi, $fecevi, $resp)) {
        echo "<script>alert('✅ Evidencia guardada con éxito'); window.location='/simba/index.php?pg=1015';</script>";
    } else {
        echo "<script>alert('❌ Error al guardar evidencia'); window.location='/simba/index.php?pg=1015';</script>";
    }
    exit;
}

// Eliminar evidencia
if (isset($_GET['delete'])) {
    $idevi = intval($_GET['delete']);
    if ($evi->eliminar($idevi)) {
        echo "<script>alert('✅ Evidencia eliminada'); window.location='/simba/index.php?pg=1015';</script>";
    } else {
        echo "<script>alert('❌ Error al eliminar'); window.location='/simba/index.php?pg=1015';</script>";
    }
    exit;
}

// Actualizar evidencia
if (isset($_POST['update'])) {
    $idevi    = $_POST["idevi"];
    $idres    = $_POST["idreser"];
    $desevi   = $_POST["descripcion"];
    $fecevi   = $_POST["fechevi"];
    $resp     = $_POST["responsable"];

    // Subida de archivo (opcional)
    $arcevi = $_POST["archivo_actual"] ?? "";
    if (!empty($_FILES["archivo"]["name"])) {
        $ruta = "../views/uploads/";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $arcevi = basename($_FILES["archivo"]["name"]);
        move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta . $arcevi);
    } else {
        // Si no se sube archivo nuevo, mantener el actual
        $stmt = $pdo->prepare("SELECT arcevi FROM evidencia WHERE idevi = ?");
        $stmt->execute([$idevi]);
        $arcevi = $stmt->fetchColumn();
    }

    $tipevi   = $_POST["tipo"];
    if ($evi->actualizar($idevi, $idres, $tipevi, $arcevi, $desevi, $fecevi, $resp)) {
        echo "<script>alert('✅ Evidencia actualizada'); window.location='/simba/index.php?pg=1015';</script>";
    } else {
        echo "<script>alert('❌ Error al actualizar'); window.location='/simba/index.php?pg=1015';</script>";
    }
    exit;
}
?>
