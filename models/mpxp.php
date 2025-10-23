<?php
class Mpxp {
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
    public function obtenerPaginasPorPerfil($tipo_perfil) {
        $paginas = [];
        $stmt = $this->conn->prepare("
            SELECT p.ruta
            FROM pagina p
            JOIN pxp px ON p.idpag = px.idpag
            JOIN perfil per ON px.idper = per.idper
            WHERE per.tipo = ?
        ");
        $stmt->bind_param("s", $tipo_perfil);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $paginas[] = $row['ruta'];
        }
        $stmt->close();
        return $paginas;
    }

    public function __destruct() {
        $this->conn->close();
    }
}
?>