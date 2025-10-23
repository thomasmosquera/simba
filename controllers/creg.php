<?php
include(__DIR__ . "/../models/conexion.php");
include(__DIR__ . "/../models/mreg.php");
include(__DIR__ . "/../models/mper.php");
$conex = new conexion();
$mreg = new Mreg();
$mper = new Mper($conex); 

$idusu   = $_REQUEST['idusu']   ?? NULL;
$nomusu  = $_POST['nomusu']     ?? NULL;
$apeusu  = $_POST['apeusu']     ?? NULL;
$emausu  = $_POST['emausu']     ?? NULL;
$telusus = $_POST['telusus']    ?? NULL;
$dirusu  = $_POST['dirusu']     ?? NULL;
$contusu = $_POST['contusu']    ?? NULL;
$cedusu  = $_POST['cedusu']     ?? NULL;
$nomemer = $_POST['nomemer']    ?? NULL;
$telemer = $_POST['telemer']    ?? NULL;
$idper   = $_POST['idper']      ?? NULL;
$ope     = $_REQUEST['ope']     ?? NULL;

$registro_exitoso = false;

if ($ope == "SavE") {
    // Teléfono usuario: 10 dígitos
    if (!empty($telusus) && !preg_match('/^[0-9]{10}$/', $telusus)) {
        echo "<script>window.location.href='index.php?pg=1006&msg=El teléfono debe tener 10 dígitos';</script>";
        exit;
    }

    // Cédula: 8 a 10 dígitos
    if (!empty($cedusu) && !preg_match('/^[0-9]{8,10}$/', $cedusu)) {
        echo "<script>window.location.href='index.php?pg=1006&msg=La cédula debe tener entre 8 y 10 dígitos';</script>";
        exit;
    }

    // Contraseña: mínimo 8 caracteres, al menos 1 mayúscula y 1 número
    if (!empty($contusu) && !preg_match('/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/', $contusu)) {
        echo "<script>window.location.href='index.php?pg=1006&msg=La contraseña debe tener mínimo 8 caracteres, una mayúscula y un número';</script>";
        exit;
    }

    // Teléfono de emergencia: 10 dígitos
    if (!empty($telemer) && !preg_match('/^[0-9]{10}$/', $telemer)) {
        echo "<script>window.location.href='index.php?pg=1006&msg=El teléfono de emergencia debe tener 10 dígitos';</script>";
        exit;
    }
}

// Asignar valores al objeto
$mreg->setNomusu($nomusu);
$mreg->setApeusu($apeusu);
$mreg->setEmausu($emausu);
$mreg->setTelusus($telusus);
$mreg->setDirusu($dirusu);
$mreg->setContusu($contusu);
$mreg->setCedusu($cedusu);
$mreg->setIdper($idper);

if ($ope == "SavE") {
     $mreg->setIdusu($idusu);
    $mreg->setNomusu($nomusu);
    $mreg->setApeusu($apeusu);
    $mreg->setEmausu($emausu);
    $mreg->setTelusus($telusus);
    $mreg->setDirusu($dirusu);
    $mreg->setContusu($contusu);
    $mreg->setCedusu($cedusu);
    $mreg->setIdper($idper);
    if ($idusu) { 
        // --- ACTUALIZAR ---
        $registro_exitoso = $mreg->actu();
        if (!empty($nomemer) && !empty($telemer)) {
            $mreg->actuemer($idusu, $nomemer, $telemer);
        }
    } else {
        // --- NUEVO REGISTRO ---
        // Validar duplicados ANTES de registrar
        if ($mreg->duplicCorreoOCedula($emausu, $cedusu)) {
            echo "<script>
                window.location.href='index.php?pg=1006&msg=El correo o la cédula ya están registrados';
            </script>";
            exit;
        }

        $idInsertado = $mreg->regis();
        if ($idInsertado !== false) {
            $registro_exitoso = true;
            if (!empty($nomemer) && !empty($telemer)) {
                $mreg->regisemer($idInsertado, $nomemer, $telemer);
            }
        }
    }
}

$datos = null;
if($idusu && $ope=="edit"){
    $mreg->setIdusu($idusu);
    $datos = $mreg->getOne();
    $emer = $mreg->getemer($idusu);
    if($emer){
        $datos['nomemer'] = $emer['nomemer'];
        $datos['telemer'] = $emer['telemer'];
    }
}

if($ope=="del" && $idusu){
    $mreg->elim($idusu);
}

// Redirección con mensaje
if ($registro_exitoso) {
    echo "<script>window.location.href='http://localhost/prueba/index.php?pg=1006&msg=Usuario registrado correctamente';</script>";
    exit;
} else if($ope == "SavE") { 
    echo "<script>window.location.href='http://localhost/prueba/index.php?pg=1006&msg=Error al registrar usuario';</script>";
    exit;
}

$perfiles = $mreg->getPerfiles();
require_once "views/vreg.php";

$usuarios = $mreg->getAll();
?>
