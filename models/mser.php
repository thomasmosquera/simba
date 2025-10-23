<?php
include_once(__DIR__ . "/../models/conexion.php");

class Mser {
    //Atributos
    private $idser;
    private $nomser;
    private $preser;
    private $descser;

    // GET
    function getIdser(){ 
        return $this->idser; 
    }
    function getNomser(){ 
        return $this->nomser; 
    }
    function getPreser(){ 
        return $this->preser; 
    }
    function getDescser(){ 
        return $this->descser; 
    }

    // SET
    function setIdser($idser){ 
        $this->idser = $idser; 
    }
    function setNomser($nomser){ 
        $this->nomser = $nomser; 
    }
    function setPreser($preser){ 
        $this->preser = $preser; 
    }
    function setDescser($descser){ 
        $this->descser = $descser; 
    }

    //Metodos Generales
    //GETALL
    public function getAll(){
        try {
            $sql = "SELECT idser, nomser, preser, descser FROM servicio";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->execute();
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            echo "Error en getAll: ".$e->getMessage();
        }
    }

    //GETONE
    public function getOne() {
        try {
            $sql = "SELECT idser, nomser, preser, descser FROM servicio WHERE idser=:idser";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(':idser', $this->idser);
            $res->execute();
            return $res->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error en getOne: " . $e->getMessage();
            return null;
        }
    }

    //REGISTRO
    public function resg(){
        try {
            $sql = "INSERT INTO servicio (nomser, preser, descser)
                    VALUES (:nomser, :preser, :descser)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(":nomser", $this->nomser);
            $res->bindParam(":preser", $this->preser);
            $res->bindParam(":descser", $this->descser);
            $res->execute();
        } catch (Exception $e) {
            echo "Error en Registro: " . $e->getMessage();
            return false;
        }
    }

    //ACTUALIZAR
    public function actu(){
        try {
            $sql = "UPDATE servicio SET 
                    nomser = :nomser, preser = :preser, descser = :descser
                    WHERE idser = :idser";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(":nomser", $this->nomser);
            $res->bindParam(":preser", $this->preser);
            $res->bindParam(":descser", $this->descser);
            $res->bindParam(":idser", $this->idser);
            $res->execute();
        } catch (Exception $e) {
            echo "Error en Actualización: " . $e->getMessage();
            return false;
        }
    }

    //ELIMINAR
    public function elim(){
        try {
            $sql = "DELETE FROM servicio WHERE idser = :idser";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(":idser", $this->idser);
            $res->execute();
        } catch (Exception $e) {
            echo "Error en Eliminar: " . $e->getMessage();
            return false;
        }
    }

    //DUPLICADO PARA PRIMER REGISTRO
    public function dupli($nomser){
        try {
            $sql = "SELECT COUNT(*) AS total FROM servicio WHERE nomser = :nomser";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(":nomser", $nomser);
            $res->execute();
            $row = $res->fetch(PDO::FETCH_ASSOC);
            return $row['total'] > 0;
        } catch (Exception $e) {
            echo "Error en duplicado: " . $e->getMessage();
            return false;
        }
    }

    //DUPLICADO PARA EDITAR
    public function dupliactu($nomser, $idser){
        try {
            $sql = "SELECT COUNT(*) AS total FROM servicio WHERE nomser = :nomser AND idser != :idser";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $res = $conexion->prepare($sql);
            $res->bindParam(":nomser", $nomser);
            $res->bindParam(":idser", $idser);
            $res->execute();
            $row = $res->fetch(PDO::FETCH_ASSOC);
            return $row['total'] > 0;
        } catch (Exception $e) {
            echo "Error en duplicado edición: " . $e->getMessage();
            return false;
        }
    }
}
?>
