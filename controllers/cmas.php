<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../models/mmas.php";
session_start();

// Asegúrate de que el usuario esté logueado
if (!isset($_SESSION["idusu"])) {
    // Redirige a la vista de mascotas con error de sesión
    header("Location: ../index.php?pg=1009&status=sin_sesion"); 
    exit;
}

$mascota = new Mascota();

// 🔹 Eliminar mascota (USA $_POST para recibir la acción y el ID)
// La vista vmas.php ahora envía 'accion'='eliminar' y 'idmasc' por POST
if (isset($_POST["accion"]) && $_POST["accion"] === "eliminar" && isset($_POST["idmasc"])) {
    $idmasc = $_POST["idmasc"];
    
    // Verifica que el ID sea un número válido antes de intentar eliminar
    if (!empty($idmasc) && is_numeric($idmasc)) {
        if ($mascota->eliminar($idmasc)) {
            // Redirige a la vista de mascotas con estado de éxito
            header("Location: ../index.php?pg=1009&status=eliminado"); 
            exit;
        } else {
            // Redirige a la vista de mascotas con estado de error de eliminación
            header("Location: ../index.php?pg=1009&status=error_eliminar");
            exit;
        }
    } else {
        // Redirige si el ID es inválido
        header("Location: ../index.php?pg=1009&status=error_id_invalido");
        exit;
    }
}

// 🔹 Procesar el formulario de Registro o Actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["accion"] == "registrar" || $_POST["accion"] == "actualizar")) {
    
    // Recolectar los datos del formulario
    $accion     = $_POST["accion"];
    // 'idmasc' se usa para actualizar. También es el nombre que usamos para eliminar.
    $idmasc     = $_POST["idmasc"] ?? null; 
    $idusu      = $_POST["idusu"] ?? null;
    $nommasc    = $_POST["nommasc"] ?? null;
    $sexomasc   = $_POST["sexo"] ?? null;
    $pesomasc   = $_POST["peso"] ?? null;
    $razamasc   = $_POST["raza"] ?? null;
    $edadmasc   = $_POST["edad"] ?? null;
    $tipomasc   = $_POST["tipo"] ?? null;
    $tamanomasc = $_POST["tamanomasc"] ?? null;
    $cuiespmasc = $_POST["cuiespmasc"] ?? null;
    $vacumasc   = $_POST["vacumasc"] ?? null;

    // Obtener rutas actuales si no se suben archivos nuevos
    $fotomasc_actual = $_POST["fotomasc_actual"] ?? null;
    $carnetmasc_actual = $_POST["carnetmasc_actual"] ?? null;

    // Inicializar variables de archivo
    $fotomasc = $fotomasc_actual;
    $carnetmasc = $carnetmasc_actual;

    // --- Procesar Imagen de la Mascota ---
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $fotoNombre = basename($_FILES["imagen"]["name"]);
        // Define la ruta relativa desde el controlador (../uploads/fotos/)
        $fotomasc = "uploads/fotos/" . uniqid() . "_" . $fotoNombre;
        // Mueve el archivo a la carpeta 'uploads/fotos/' (sube un nivel para llegar a la raíz)
        if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], "../" . $fotomasc)) {
             // Manejo de error al mover archivo
             $fotomasc = $fotomasc_actual; // Mantener la actual si falla el movimiento
        } else {
             // Si el movimiento fue exitoso, intentar eliminar la foto anterior si existe
             if ($fotomasc_actual && file_exists("../" . $fotomasc_actual)) {
                 unlink("../" . $fotomasc_actual);
             }
        }
    }

    // --- Procesar Carnet de Vacunación ---
    if (isset($_FILES["carnetmasc"]) && $_FILES["carnetmasc"]["error"] == 0) {
        $carnetNombre = basename($_FILES["carnetmasc"]["name"]);
        // Define la ruta relativa desde el controlador (../uploads/carnets/)
        $carnetmasc = "uploads/carnets/" . uniqid() . "_" . $carnetNombre;
        // Mueve el archivo a la carpeta 'uploads/carnets/' (sube un nivel para llegar a la raíz)
        if (!move_uploaded_file($_FILES["carnetmasc"]["tmp_name"], "../" . $carnetmasc)) {
            // Manejo de error al mover archivo
            $carnetmasc = $carnetmasc_actual; // Mantener el actual si falla el movimiento
        } else {
             // Si el movimiento fue exitoso, intentar eliminar el carnet anterior si existe
             if ($carnetmasc_actual && file_exists("../" . $carnetmasc_actual)) {
                 unlink("../" . $carnetmasc_actual);
             }
        }
    }

    // Validación básica de campos requeridos
    if ($nommasc && $sexomasc && $pesomasc && $razamasc && $edadmasc && $tipomasc && $tamanomasc && $cuiespmasc && $vacumasc && $idusu) {
        
        if ($accion == "registrar") {
            // Llama a la función de registro
            $registrado = $mascota->registrar(
                $idusu, $nommasc, $sexomasc, $pesomasc, $razamasc, $edadmasc,
                $tipomasc, $tamanomasc, $cuiespmasc, $vacumasc, $carnetmasc, $fotomasc
            );

            if ($registrado) {
                header("Location: ../index.php?pg=1009&status=registrado");
                exit;
            } else {
                header("Location: ../index.php?pg=1009&status=error_registro");
                exit;
            }

        } else if ($accion == "actualizar" && $idmasc) {
            // Llama a la función de actualización
            $actualizado = $mascota->actualizar(
                $idmasc, $idusu, $nommasc, $sexomasc, $pesomasc, $razamasc, $edadmasc,
                $tipomasc, $tamanomasc, $cuiespmasc, $vacumasc, $carnetmasc, $fotomasc
            );

            if ($actualizado) {
                header("Location: ../index.php?pg=1009&status=actualizado");
                exit;
            } else {
                header("Location: ../index.php?pg=1009&status=error_actualizar");
                exit;
            }
        }
    } else {
        // Redirige si faltan campos
        header("Location: ../index.php?pg=1009&status=error_campos_incompletos");
        exit;
    }
} 
// Si la acción no es reconocida o no es POST para registro/actualización, y no eliminó, redirigir
else if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["accion"])) {
     header("Location: ../index.php?pg=1009&status=error_accion_desconocida");
     exit;
} else if ($_SERVER["REQUEST_METHOD"] != "POST") {
     // Si se accede directamente por GET sin parámetros
     header("Location: ../index.php?pg=1009");
     exit;
}
