<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../models/mini.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $usuario = $_POST['cedusu'] ?? '';
    $contrasena = $_POST['contusu'] ?? '';

    $modelo = new UsuarioModel();
    $userData = $modelo->verificarUsuario($usuario, $contrasena);

    if ($userData) { 
        $_SESSION['idusu'] = $userData['idusu']; 
        $_SESSION['cedusu'] = $usuario; 
        $_SESSION['tipo_perfil'] = $userData['tipo_perfil']; 

        header("Location: ../index.php?pg=dashboard");
        exit(); 
    } else { 
        $error = "Usuario o contraseña incorrectos"; 
        header("Location: ../index.php?pg=1001&error=" . urlencode($error)); 
        exit(); 
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedusu'])) {
    $cedusu = trim($_POST['cedusu']);
    $usuario = (new UsuarioModel())->buscarPorNombre($cedusu);

    if ($usuario) {
        // Aquí deberías enviar el correo real, pero para pruebas solo simula
        $mensaje = "La contraseña ha sido enviada al correo <b>{$usuario['emausu']}</b> registrado.";
        $tipo = "warning";
    } else {
        $mensaje = "Usuario no encontrado.";
        $tipo = "danger";
    }

    // Redirige con el mensaje como parámetro GET
    header("Location: index.php?pg=1002&mensaje=" . urlencode($mensaje) . "&tipo=" . $tipo);
    exit;
} else {
    header("Location: ../index.php?pg=1001");
    exit();
}
?>