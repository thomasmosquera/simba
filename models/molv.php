<?php
class RecuperarModelo {
    private $db;

    public function __construct() {
        $this->db = new mysqli('localhost', 'root', '', 'simba');

        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function obtenerUsuarioPorCorreo($correo) {
        $stmt = $this->db->prepare("SELECT nomusu FROM usuario WHERE emausu = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // 👇 Este método debe estar dentro de la clase
    public function actualizarContrasenaTemporal($correo, $hash) {
        $stmt = $this->db->prepare("UPDATE usuario SET contusu = ? WHERE emausu = ?");
        $stmt->bind_param("ss", $hash, $correo);
        return $stmt->execute();
    }
}
