<?php
class Mpag {
    private $conexion;

    // Constructor que recibe la conexión PDO
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    //  Listar todas las páginas
    public function listarPaginas() {
        $sql = "SELECT idpag, nompag, rutpag, mospag FROM pagina ORDER BY idpag DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Insertar nueva página
    public function insertarPagina($nompag, $rutpag, $mospag) {
        $sql = "INSERT INTO pagina (nompag, rutpag, mospag) VALUES (:nompag, :rutpag, :mospag)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nompag', $nompag);
        $stmt->bindParam(':rutpag', $rutpag);
        $stmt->bindParam(':mospag', $mospag, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al insertar página: ' . ($err[2] ?? 'desconocido'));
        }
    }
    //  Actualizar una página existente
    public function actualizarPagina($idpag, $nompag, $rutpag, $mospag) {
        $sql = "UPDATE pagina 
                SET nompag = :nompag, rutpag = :rutpag, mospag = :mospag
                WHERE idpag = :idpag";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idpag', $idpag, PDO::PARAM_INT);
        $stmt->bindParam(':nompag', $nompag);
        $stmt->bindParam(':rutpag', $rutpag);
        $stmt->bindParam(':mospag', $mospag, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al actualizar página: ' . ($err[2] ?? 'desconocido'));
        }
    }
    //  Eliminar página por ID

    public function eliminarPagina($idpag) {
        $sql = "DELETE FROM pagina WHERE idpag = :idpag";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idpag', $idpag, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al eliminar página: ' . ($err[2] ?? 'desconocido'));
        }
    }

    //  Buscar una página por ID (opcional)
    public function obtenerPaginaPorId($idpag) {
        $sql = "SELECT * FROM pagina WHERE idpag = :idpag";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idpag', $idpag, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
