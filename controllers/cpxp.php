<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/../');
}
require_once BASE_PATH . 'models/mpxp.php';

class Cpxp {
    private $mpxp;

    public function __construct() {
        $this->mpxp = new Mpxp();
    }
    public function obtenerMenuPorPerfil() {
        if (!isset($_SESSION['tipo_perfil'])) {
            return [];
        }
        $tipo_perfil = $_SESSION['tipo_perfil'];
        return $this->mpxp->obtenerPaginasPorPerfil($tipo_perfil);
    }
    public function verificarPermiso($ruta_solicitada) {
        if (!isset($_SESSION['idusu'])) { 
            header("Location: index.php?pg=1001&error=" . urlencode("Debes iniciar sesión para acceder."));
            exit();
        }

        $paginas_permitidas = $this->obtenerMenuPorPerfil();

        if (!in_array($ruta_solicitada, $paginas_permitidas)) {
            header("Location: index.php?pg=dashboard&error=" . urlencode("No tienes permiso para acceder a esta página."));
            exit();
        }
    }
}
?>