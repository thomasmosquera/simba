<?php

include_once(__DIR__ . "/../models/conexion.php");

class Mreg{
    //Atributos
    private $idusu;
    private $nomusu;
    private $apeusu;
    private $emausu;
    private $telusus;
    private $dirusu;
    private $contusu;
    private $cedusu;
    private $idper;

    //GET
    function getIdusu(){ return $this->idusu; }
    function getNomusu(){ return $this->nomusu; }
    function getApeusu(){ return $this->apeusu; }
    function getEmausu(){ return $this->emausu; }
    function getTelusus(){ return $this->telusus; }
    function getDirusu(){ return $this->dirusu; }
    function getContusu(){ return $this->contusu; }
    function getCedusu(){ return $this->cedusu; }
    function getidper(){ return $this->idper; }

    //SET
    function setIdusu($idusu){ $this->idusu = $idusu; }
    function setNomusu($nomusu){ $this->nomusu = $nomusu; }
    function setApeusu($apeusu){ $this->apeusu = $apeusu; }
    function setEmausu($emausu){ $this->emausu = $emausu; }
    function setTelusus($telusus){ $this->telusus = $telusus; }
    function setDirusu($dirusu){ $this->dirusu = $dirusu; }
    function setContusu($contusu){ $this->contusu = $contusu; }
    function setCedusu($cedusu){ $this->cedusu = $cedusu; }
    function setidper($idper){ $this->idper = $idper; }

    //Metodos Generales
    public function getAll(){
        try{
            $sql = "SELECT u.idusu, u.nomusu, u.apeusu, u.emausu, 
                        u.telusus, u.dirusu, u.contusu, u.cedusu,
                        p.nomper AS tipo, e.nomemer, e.telemer
                    FROM usuario u
                    LEFT JOIN perfil p ON u.idper = p.idper
                    LEFT JOIN emergencia e ON u.idusu = e.idusu";
            $modelo = new conexion();
            $conexion=$modelo->get_conexion();
            $res=$conexion->prepare($sql);
            $res->execute();
            return $res->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo "Error en getAll: ".$e->getMessage();
        }
    }

    public function getOne(){
        try{
            $sql = "SELECT idusu, nomusu, apeusu, emausu, telusus, dirusu, contusu, cedusu 
                    FROM usuario WHERE idusu=:idusu";
            $modelo = new conexion();
            $conexion=$modelo->get_conexion();
            $res=$conexion->prepare($sql);
            $res->bindParam(':idusu', $this->idusu);
            $res->execute();
            return $res->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo "Error en getOne: ".$e->getMessage();
        }
    }

    public function regis(){
        try{
            // Validar duplicados
            if($this->duplicCorreoOCedula($this->emausu, $this->cedusu)){
                throw new Exception("El correo {$this->emausu} o la cédula {$this->cedusu} ya están registrados");
            }

            $passwordHash = password_hash($this->contusu, PASSWORD_DEFAULT); 
            $sql = "INSERT INTO usuario 
            (nomusu, apeusu, emausu, telusus, dirusu, contusu, cedusu, idper) 
            VALUES (:nomusu, :apeusu, :emausu, :telusus, :dirusu, :contusu, :cedusu, :idper)";
            
            $modelo = new conexion();
            $conexion=$modelo->get_conexion();
            $res=$conexion->prepare($sql);
            $res->bindParam(":nomusu",$this->nomusu);
            $res->bindParam(":apeusu",$this->apeusu);
            $res->bindParam(":emausu",$this->emausu);
            $res->bindParam(":telusus",$this->telusus);
            $res->bindParam(":dirusu",$this->dirusu);
            $res->bindParam(':contusu', $passwordHash);
            $res->bindParam(":cedusu",$this->cedusu);
            $res->bindParam(":idper",$this->idper);
            $res->execute();
            
            return $conexion->lastInsertId();
        }catch (Exception $e) {
            echo "".$e->getMessage();
            return false;
        }
    }

    public function actu(){
        try{
            // obtener conexión (usa tu método existente)
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            // Si no hay idusu definido en el objeto, fallamos
            if (empty($this->idusu)) {
                throw new Exception("Falta idusu para actualizar.");
            }

            if (empty($this->contusu)) {
                // Actualizar sin tocar la contraseña
                $sql = "UPDATE usuario SET 
                            nomusu = :nomusu,
                            apeusu = :apeusu,
                            emausu = :emausu,
                            telusus = :telusus,
                            dirusu = :dirusu,
                            cedusu = :cedusu,
                            idper = :idper
                        WHERE idusu = :idusu";
                $res = $conexion->prepare($sql);
                $res->bindParam(":nomusu", $this->nomusu);
                $res->bindParam(":apeusu", $this->apeusu);
                $res->bindParam(":emausu", $this->emausu);
                $res->bindParam(":telusus", $this->telusus);
                $res->bindParam(":dirusu", $this->dirusu);
                $res->bindParam(":cedusu", $this->cedusu);
                $res->bindParam(":idper", $this->idper);
                $res->bindParam(":idusu", $this->idusu);
                $res->execute();
            } else {
                // Actualizar incluyendo nueva contraseña (hasheada)
                $hash = password_hash($this->contusu, PASSWORD_DEFAULT);
                $sql = "UPDATE usuario SET 
                            nomusu = :nomusu,
                            apeusu = :apeusu,
                            emausu = :emausu,
                            telusus = :telusus,
                            dirusu = :dirusu,
                            contusu = :contusu,
                            cedusu = :cedusu,
                            idper = :idper
                        WHERE idusu = :idusu";
                $res = $conexion->prepare($sql);
                $res->bindParam(":nomusu", $this->nomusu);
                $res->bindParam(":apeusu", $this->apeusu);
                $res->bindParam(":emausu", $this->emausu);
                $res->bindParam(":telusus", $this->telusus);
                $res->bindParam(":dirusu", $this->dirusu);
                $res->bindParam(":contusu", $hash);
                $res->bindParam(":cedusu", $this->cedusu);
                $res->bindParam(":idper", $this->idper);
                $res->bindParam(":idusu", $this->idusu);
                $res->execute();
            }

            return true;
        } catch (Exception $e) {
            // Mantén tu manejo de errores (puedes mostrar mensaje o log)
            echo "Error en actu: " . $e->getMessage();
            return false;
        }
    }

    public function elim($idusu){
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            $sqlCheck = "SELECT COUNT(*) AS total FROM evidencia WHERE idusu = :idusu";
            $check = $conexion->prepare($sqlCheck);
            $check->bindParam(":idusu", $idusu);
            $check->execute();
            $result = $check->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                echo "<script>
                    window.location.href='index.php?pg=1006&msg=No se puede eliminar el usuario porque tiene registros vinculados.';
                </script>";
                return false;
            }

            $sql1 = "DELETE FROM emergencia WHERE idusu = :idusu";
            $stmt1 = $conexion->prepare($sql1);
            $stmt1->bindParam(":idusu", $idusu);
            $stmt1->execute();

            $sql2 = "DELETE FROM usuario WHERE idusu = :idusu";
            $stmt2 = $conexion->prepare($sql2);
            $stmt2->bindParam(":idusu", $idusu);
            $stmt2->execute();

            echo "<script>
                window.location.href='index.php?pg=1006&msg=Usuario eliminado correctamente';
            </script>";
            return true;

        } catch (PDOException $e) {
            echo "<script>
                window.location.href='index.php?pg=1006&msg=Error al eliminar usuario';
            </script>";
            return false;
        }
    }

    // Emergencia
    public function regisemer($idusu, $nomemer, $telemer){
        try{
            $sql = "INSERT INTO emergencia (idusu, nomemer, telemer) 
                    VALUES (:idusu, :nomemer, :telemer)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(":idusu", $idusu);
            $res->bindParam(":nomemer", $nomemer);
            $res->bindParam(":telemer", $telemer);
            $res->execute();
        }catch(Exception $e){
            echo "Error en regisemer: ".$e->getMessage();
        }
    }

    public function actuemer($idusu, $nomemer, $telemer){
        try{
            $sql = "UPDATE emergencia SET nomemer=:nomemer, telemer=:telemer 
                    WHERE idusu=:idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(":idusu", $idusu);
            $res->bindParam(":nomemer", $nomemer);
            $res->bindParam(":telemer", $telemer);
            $res->execute();
        }catch(Exception $e){
            echo "Error en actuemer: ".$e->getMessage();
        }
    }

    public function getemer($idusu){
        try{
            $sql = "SELECT * FROM emergencia WHERE idusu=:idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(":idusu", $idusu);
            $res->execute();
            return $res->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo "Error en getemer: ".$e->getMessage();
        }
    }

    public function getPerfiles() {
        try {
            $sql = "SELECT idper, nomper FROM perfil";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->execute();
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error en getPerfiles: " . $e->getMessage();
        }
    }

    public function duplicCorreoOCedula($emausu, $cedusu){
        try {
            $sql = "SELECT 1 FROM usuario WHERE emausu = :emausu OR cedusu = :cedusu LIMIT 1";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(":emausu", $emausu);
            $res->bindParam(":cedusu", $cedusu);
            $res->execute();
            return $res->fetch() ? true : false;
        } catch(Exception $e) {
            echo "Error en duplicado: ".$e->getMessage();
            return false;
        }
    }

    public function getperf() {
        return $this->getAll();
    }
}
?>
