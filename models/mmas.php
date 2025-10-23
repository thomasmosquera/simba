<?php
require_once "conexion.php";

class Mascota {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    /**
     * Inserta una nueva mascota con todos los datos e imágenes
     */
    public function registrar($idusu, $nommas, $sexmas, $pesomas, $razamas, $edadmas, $tipomas, $tammas, $cuidmas, $vacmas, $carmas, $fotmas) {
        try {
            $sql = "INSERT INTO mascota (
                        idusu, nommas, sexmas, pesomas, razamas, edadmas,
                        tipomas, tammas, cuidmas, vacmas, carmas, fotmas
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $idusu,
                $nommas,
                $sexmas,
                $pesomas,
                $razamas,
                $edadmas,
                $tipomas,
                $tammas,
                $cuidmas,
                $vacmas,
                $carmas,
                $fotmas
            ]);
        } catch (PDOException $e) {
            // Muestra el error (solo en desarrollo, en producción se debe loguear)
            error_log("Error al registrar mascota: " . $e->getMessage());
            // Mantengo el "echo" por si es el comportamiento esperado en tu entorno
            echo "Error al registrar mascota: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Actualiza una mascota existente con todos los datos e imágenes
     */
    public function actualizar($idmas, $idusu, $nommas, $sexmas, $pesomas, $razamas, $edadmas, $tipomas, $tammas, $cuidmas, $vacmas, $carmas, $fotmas) {
        try {
            $sql = "UPDATE mascota SET
                        idusu = ?, nommas = ?, sexmas = ?, pesomas = ?, razamas = ?, edadmas = ?,
                        tipomas = ?, tammas = ?, cuidmas = ?, vacmas = ?, carmas = ?, fotmas = ?
                    WHERE idmas = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $idusu,
                $nommas,
                $sexmas,
                $pesomas,
                $razamas,
                $edadmas,
                $tipomas,
                $tammas,
                $cuidmas,
                $vacmas,
                $carmas,
                $fotmas,
                $idmas // Parámetro para la cláusula WHERE
            ]);
        } catch (PDOException $e) {
            // Muestra el error (solo en desarrollo)
            error_log("Error al actualizar mascota: " . $e->getMessage());
            echo "Error al actualizar mascota: " . $e->getMessage();
            return false;
        }
    }


    /**
     * Lista todas las mascotas registradas con el nombre del usuario (MODIFICADO)
     */
    public function listarMascotas() {
        $sql = "SELECT m.idmas, m.idusu, m.nommas, m.sexmas, m.pesomas, m.razamas, m.edadmas,
                       m.tipomas, m.tammas, m.cuidmas, m.vacmas, m.carmas, m.fotmas,
                       u.nomusu 
                FROM mascota m
                JOIN usuario u ON m.idusu = u.idusu
                ORDER BY m.idmas DESC";
        
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar mascotas: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Busca una mascota por su ID
     */
    public function buscarMascotaPorId($idmas) {
        $sql = "SELECT idmas, idusu, nommas, sexmas, pesomas, razamas, edadmas,
                       tipomas, tammas, cuidmas, vacmas, carmas, fotmas
                FROM mascota WHERE idmas = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$idmas]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar mascota: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Elimina una mascota por su ID
     */
    public function eliminar($idmas) {
         try {
            $sql = "DELETE FROM mascota WHERE idmas = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$idmas]);
        } catch (PDOException $e) {
            error_log("Error al eliminar mascota: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Nuevo método: Lista todos los usuarios (para el dropdown en la vista)
     */
    public function listarUsuarios() {
        // ASUMO que tienes una tabla 'usuario' con al menos 'idusu' y 'nomusu'
        $sql = "SELECT idusu, nomusu FROM usuario ORDER BY nomusu ASC";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar usuarios: " . $e->getMessage());
            return [];
        }
    }
}
?>
