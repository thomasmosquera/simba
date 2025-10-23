<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'models/molv.php';
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

class RecuperarControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new RecuperarModelo();
    }

    public function enviarCorreoRecuperacion($correo) {
        // 1. Verificar usuario
        $usuario = $this->modelo->obtenerUsuarioPorCorreo($correo);

        if (!$usuario) {
            echo '<div class="alert alert-warning" role="alert">⚠️ No se encontró un usuario con el correo: ' . htmlspecialchars($correo) . '</div>';
            return;
        }

        // 2. Generar clave temporal
        $claveTemporal = bin2hex(random_bytes(4)); // Ej: "a1b2c3d4"

        // 3. Guardar hash en la BD
        $hash = password_hash($claveTemporal, PASSWORD_BCRYPT);
        $this->modelo->actualizarContrasenaTemporal($correo, $hash);

        // 4. Enviar correo con la clave temporal
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'simbassoftware@gmail.com';
            $mail->Password = 'cvpalhdcrenkdrrn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('simbassoftware@gmail.com', 'SIMBA');
            $mail->addAddress($correo, $usuario['nomusu']);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Recuperación de contraseña SIMBA';
            $mail->Body = "
    <div style='font-family:Segoe UI,Arial,sans-serif;background:#f4f6fb;padding:32px 0;'>
        <div style='max-width:480px;margin:auto;background:#fff;border-radius:12px;box-shadow:0 2px 8px #0001;padding:32px 28px;'>
            <div style='text-align:center;'>
                <img src='https://i.imgur.com/YEDLMzV.jpeg' alt='Logo SIMBA' style='max-width:90px;margin-bottom:18px;border-radius:8px;'>
                <h2 style='color:#ff9800;margin:0 0 8px 0;font-weight:700;font-size:1.6em;'>Recuperación de contraseña</h2>
                <p style='color:#888;font-size:15px;margin:0 0 18px 0;'>Plataforma SIMBA</p>
            </div>
            <p style='color:#222;font-size:16px;line-height:1.6;margin-bottom:18px;'>
                Hola <strong style='color:#ff9800;'>".htmlspecialchars($usuario['nomusu'])."</strong>,<br>
                Recibimos una solicitud para restablecer la contraseña de tu cuenta en <b>SIMBA</b>.
            </p>
            <div style='background:#f8fafc;border:1px solid #ffe0b2;padding:18px 0;border-radius:8px;text-align:center;margin-bottom:22px;'>
                <span style='color:#888;font-size:15px;'>Tu contraseña temporal es:</span>
                <div style='font-size:2em;font-weight:700;color:#222;margin-top:8px;letter-spacing:2px;'>$claveTemporal</div>
            </div>
            <p style='color:#d84315;font-size:15px;font-weight:600;margin-bottom:18px;'>
                ⚠️ Por seguridad, cambia esta contraseña temporal al iniciar sesión.
            </p>
            <p style='color:#666;font-size:13px;margin-bottom:0;'>
                Si no solicitaste este cambio, ignora este mensaje o contacta con el soporte de SIMBA.
            </p>
            <hr style='border:none;border-top:1px solid #eee;margin:28px 0 12px 0;'>
            <p style='color:#bbb;font-size:12px;text-align:center;margin:0;'>
                Este mensaje fue generado automáticamente por el sistema SIMBA.<br>No respondas a este correo.
            </p>
        </div>
    </div>
";

            $mail->send();
            echo '<div class="alert alert-success" role="alert">✅ Nueva contraseña enviada correctamente a ' . htmlspecialchars($correo) . '</div>';
        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">❌ Error al enviar el correo: ' . htmlspecialchars($mail->ErrorInfo) . '</div>';
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pg']) && $_GET['pg'] == 1003 && isset($_GET['correo'])) {
    $controlador = new RecuperarControlador();
    $controlador->enviarCorreoRecuperacion($_GET['correo']);
}
