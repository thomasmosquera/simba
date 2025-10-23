<?php
include(__DIR__ . "/../models/mser.php");
$mser = new Mser();

$idser  = $_REQUEST['idser'] ?? NULL;
$nomser = $_POST['nomser'] ?? NULL;
$preser = $_POST['preser'] ?? NULL;
$descser = $_POST['descser'] ?? NULL;
$ope = $_REQUEST['ope'] ?? NULL;

if ($ope == "SaVE") {
    $mser->setIdser($idser);
    $mser->setNomser($nomser);
    $mser->setPreser($preser);
    $mser->setDescser($descser);

    if ($idser) {
        if ($mser->dupliactu($nomser, $idser)) {
            echo "<script>window.location.href='index.php?pg=1011&msg=El servicio ya está registrado';</script>";
            exit;
        }

        $mser->actu();
        echo "<script>window.location.href='index.php?pg=1011&msg=El Servicio fue actualizado correctamente';</script>";
        exit;
    }else {
        if ($mser->dupli($nomser)) {
            echo "<script>window.location.href='index.php?pg=1011&msg=El nombre del servicio ya está registrado';</script>";
            exit;
        }

        $mser->resg();
        echo "<script>window.location.href='index.php?pg=1011&msg=El Servicio fue registrado correctamente';</script>";
        exit;
    }
}

if ($ope == "Eli" && $idser) {
    $mser->setIdser($idser);
    $mser->elim();
    echo "<script>window.location.href='index.php?pg=1011&msg=El Servicio fue eliminado correctamente';</script>";
    exit;
}

$servicios = $mser->getAll();
$servicioEditar = null;
if (isset($_GET['editar'])) {
    $mser->setIdser($_GET['editar']);
    $servicioEditar = $mser->getOne();
}
?>