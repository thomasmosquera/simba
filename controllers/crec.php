<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['nomusu'] ?? '');

    if ($usuario === '') {
        header("Location: ../index.php?pg=1003&mensaje=El nombre de usuario es obligatorio.&tipo=warning");
        exit;
    }

    header("Location: ../index.php?pg=1003&mensaje=Si el usuario existe, se ha enviado un correo.&tipo=success");
    exit;
}
