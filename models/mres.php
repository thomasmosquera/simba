<?php
require_once "conexion.php";

class mres {
    private $idres;
    private $idmas;
    private $idusu;
    private $fecact;
    private $estres;

    // Setters y getters
    function getidres() { return $this->idres; }
    function setidres($idres) { $this->idres = $idres; }

    function getidmas() { return $this->idmas; }
    function setidmas($idmas) { $this->idmas = $idmas; }

    function getidusu() { return $this->idusu; }
    function setidusu($idusu) { $this->idusu = $idusu; }

    function getfecact() { return $this->fecact; }
    function setfecact($fecact) { $this->fecact = $fecact; }

    function getestres() { return $this->estres; }
    function setestres($estres) { $this->estres = $estres; }

    // Obtener una reserva
    public function getOne() {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            $sql = "SELECT r.idres, r.fecact, r.estres,
                        u.idusu, u.nomusu, u.emausu, u.dirusu, u.telusus,
                        m.idmas, m.nommas
                    FROM reserva r
                    INNER JOIN usuario u ON r.idusu = u.idusu
                    INNER JOIN mascota m ON r.idmas = m.idmas
                    WHERE r.idres = :idres";    
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idres', $this->idres);
            $stmt->execute();
            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

            if($reserva){
                // Obtener servicios asociados
                $reserva['servicios'] = $this->getServicios();
            }

            return $reserva;
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    // Obtener todos los servicios de una reserva
    public function getServicios() {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            $sql = "SELECT s.idser, s.nomser, s.preser FROM serres rs
                    INNER JOIN servicio s ON rs.idser = s.idser
                    WHERE rs.idres=:idres";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idres', $this->idres);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error al obtener servicios: " . $e->getMessage();
            return [];
        }
    }

    // Guardar reserva
    public function save() {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            $sql = "INSERT INTO reserva (idmas, idusu, fecact, estres) 
                    VALUES (:idmas, :idusu, :fecact, :estres)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idmas', $this->idmas);
            $stmt->bindParam(':idusu', $this->idusu);
            $stmt->bindParam(':fecact', $this->fecact);
            $stmt->bindParam(':estres', $this->estres);
            $stmt->execute();

            $this->idres = $conexion->lastInsertId(); // para usar en servicios
        } catch (Exception $e) {
            echo "Error al guardar reserva: " . $e->getMessage();
        }
    }

    // Guardar servicios
    public function saveServicios($servicios = []) {
        try {
            if(empty($servicios)) return;
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            foreach($servicios as $idser){
                $sql = "INSERT INTO serres (idres, idser) VALUES (:idres, :idser)";
                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(':idres', $this->idres);
                $stmt->bindParam(':idser', $idser);
                $stmt->execute();
            }
        } catch (Exception $e) {
            echo "Error al guardar servicios: " . $e->getMessage();
        }
    }

    // Editar reserva
    public function edit() {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            $sql = "UPDATE reserva SET idmas=:idmas, idusu=:idusu, fecact=:fecact, estres=:estres
                    WHERE idres=:idres";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idres', $this->idres);
            $stmt->bindParam(':idmas', $this->idmas);
            $stmt->bindParam(':idusu', $this->idusu);
            $stmt->bindParam(':fecact', $this->fecact);
            $stmt->bindParam(':estres', $this->estres);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error al editar reserva: " . $e->getMessage();
        }
    }

    // Editar servicios
    public function editServicios($servicios = []) {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            // Borrar los antiguos
            $sqlDel = "DELETE FROM serres WHERE idres=:idres";
            $stmtDel = $conexion->prepare($sqlDel);
            $stmtDel->bindParam(':idres', $this->idres);
            $stmtDel->execute();

            // Guardar los nuevos
            $this->saveServicios($servicios);
        } catch (Exception $e) {
            echo "Error al editar servicios: " . $e->getMessage();
        }
    }

    // Eliminar reserva
    public function del() {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            // Borrar servicios primero
            $sqlDel = "DELETE FROM serres WHERE idres=:idres";
            $stmtDel = $conexion->prepare($sqlDel);
            $stmtDel->bindParam(':idres', $this->idres);
            $stmtDel->execute();

            // Borrar reserva
            $sql = "DELETE FROM reserva WHERE idres=:idres";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idres', $this->idres);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error al eliminar reserva: " . $e->getMessage();
        }
    }

    // Obtener todas las reservas con servicios concatenados
    public function getAll() {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            // Obtener reservas
            $sql = "SELECT r.idres, r.fecact, r.estres,
                           m.nommas,
                           u.nomusu
                    FROM reserva r
                    INNER JOIN mascota m ON r.idmas = m.idmas
                    INNER JOIN usuario u ON r.idusu = u.idusu";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Obtener servicios de cada reserva
            foreach($reservas as &$reserva){
                $this->setidres($reserva['idres']);
                $servs = $this->getServicios();
                $reserva['servicios'] = $servs;
            }

            return $reservas;

        } catch (Exception $e) {
            echo "Error al obtener reservas: " . $e->getMessage();
            return [];
        }
    }

    public function getUsuariosPerfil3() {
    try {
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();

        $sql = "SELECT idusu, nomusu 
                FROM usuario
                WHERE idper = 2";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}
    
}
?>
