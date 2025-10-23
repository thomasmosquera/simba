<?php
require_once("models/mres.php");
require_once("models/mser.php");
//require_once("models/mmas.php");
require_once("models/mreg.php");
require_once("models/conexion.php"); 

//traer nombre de mascotas
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();

    $sql = "SELECT * FROM mascota";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $datMas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
$mres = new mres();
$mser = new mser();
//$mmas = new mmas();
$mreg = new mreg();

$idres = isset($_POST['idres']) ? $_POST['idres'] : (isset($_GET['idres']) ? $_GET['idres'] : NULL);
$idusu = isset($_POST['idusu']) ? $_POST['idusu'] : NULL;
$idmas = isset($_POST['idmas']) ? $_POST['idmas'] : NULL;
$idser = isset($_POST['idser']) ? $_POST['idser'] : []; // ahora es array
$fecact = isset($_POST['fecact']) ? $_POST['fecact'] : NULL;
$estres  = isset($_POST['estres']) ? $_POST['estres'] : NULL;

$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;
$datOne = NULL;

$mres->setidres($idres);
if($ope=="save"){
    $mres->setidmas($idmas);
    $mres->setidusu($idusu);
    $mres->setfecact($fecact);
    $mres->setestres($estres);

    if(!$idres){
        $mres->save();
        $mres->saveServicios($idser);
    } else {
        $mres->edit();
        $mres->editServicios($idser);
    }
}
$pgActual = $_GET['pg'] ?? '1010';

if($ope=="eli" && $idres) {
    $mres->del();
    header("Location: index.php?pg=1010"); // redirige a la lista
    exit;
}
if($ope=="edi" && $idres) $datOne = $mres->getOne();

if ($ope == "fac" && $idres) {
    $mres->setidres($idres);
    $datOne = $mres->getOne(); // ya incluye los servicios
    require("views/vfac.php");
    exit;
}

$datSer = $mser->getAll();
$datAll = $mres->getAll();
//$datMas = $mmas->getAll();
$datReg = $mreg->getAll();
$datUsu = $mres->getUsuariosPerfil3();
?>
