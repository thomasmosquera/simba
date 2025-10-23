<?php
require_once __DIR__ . '/../models/conexion.php';

class Evidencia {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Insertar evidencia
    public function insertar($idres, $tipevi, $arcevi, $desevi, $fecevi, $resp) {
        $sql = "INSERT INTO evidencia (idres, tipevi, arcevi, desevi, fecevi, resp)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idres, $tipevi, $arcevi, $desevi, $fecevi, $resp]);
    }

    // Listar reservas disponibles
    public function listarReservas() {
        $sql = "SELECT r.idres, m.nommas AS nommasc, u.nomusu, u.apeusu
                FROM reserva r
                JOIN mascota m ON r.idmas = m.idmas
                JOIN usuario u ON r.idusu = u.idusu";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener responsable por idreser
    public function obtenerResponsablePorReserva($idreser) {
        $sql = "SELECT u.nomusu, u.apeusu
                FROM reserva r
                JOIN usuario u ON r.idusu = u.idusu
                WHERE r.idres = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idreser]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar evidencias
    public function listarEvidencias() {
        $sql = "SELECT e.idevi, e.fecevi, m.nommas AS nommasc, u.nomusu, u.apeusu, e.arcevi AS archivo, e.desevi, e.resp, e.tipevi
                FROM evidencia e
                JOIN reserva r ON e.idres = r.idres
                JOIN mascota m ON r.idmas = m.idmas
                JOIN usuario u ON r.idusu = u.idusu
                ORDER BY e.fecevi DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar evidencia
    public function eliminar($idevi) {
        $sql = "DELETE FROM evidencia WHERE idevi = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idevi]);
    }

    // Actualizar evidencia
    public function actualizar($idevi, $idres, $tipevi, $arcevi, $desevi, $fecevi, $resp) {
        $sql = "UPDATE evidencia SET idres = ?, tipevi = ?, arcevi = ?, desevi = ?, fecevi = ?, resp = ? WHERE idevi = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idres, $tipevi, $arcevi, $desevi, $fecevi, $resp, $idevi]);
    }
}
?>
