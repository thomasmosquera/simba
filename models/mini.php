<?php
class UsuarioModel {
    private $conn;

    public function __construct() {
        $host = "localhost";
        $db = "simba";
        $user = "root";
        $pass = "";

        $this->conn = new mysqli($host, $user, $pass, $db);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }
    public function verificarUsuario($usuario, $contrasena) {
    
        $stmt = $this->conn->prepare("SELECT u.idusu, u.contusu, p.nomper FROM usuario u JOIN perfil p ON u.idper = p.idper WHERE u.cedusu = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($contrasena, $row['contusu'])) {
                return ['idusu' => $row['idusu'], 'tipo_perfil' => $row['nomper']];
            }
        }
        return false;
    }

    public function buscarPorNombre($cedusu) {
        $sql = "SELECT * FROM usuario WHERE cedusu = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $cedusu);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        return $usuario;
    }

    public function __destruct() {
        $this->conn->close();
    }
}
?>