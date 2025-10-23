<?php
class Cper {
    private $mper;
    private $alerta = '';

    public function __construct(Mper $mper) {
        $this->mper = $mper;
    }

    // Punto de entrada principal: decide qué acción ejecutar
    public function run() {
        $ope = $_GET['ope'] ?? 'index';

        switch ($ope) {
            case 'crear':   $this->store(); break;
            case 'act':     $this->toggle(); break;
            case 'eDi':     $this->edit(); break; 
            case 'actedit': $this->update(); break;
            case 'eLi':     $this->delete(); break;
            default:        $this->index(); break;
        }
    }

    // Cargar vista principal de perfiles
    private function index() {
        $dtAll = $this->mper->getAllPerfiles(); 
        $alerta = $this->alerta; 
        $pg = $_GET['pg'] ?? 1008; 
        $mper = $this->mper;

        require BASE_PATH . 'views/vper.php'; 
    }

    // Crear un perfil
    private function store() {
        $nomper = $_POST['nombre'] ?? null; 
        $pgini = $_POST['pgini'] ?? null;

        if (empty($nomper) || empty($pgini)) {
            $this->alerta = 'El nombre del perfil y la página inicial son obligatorios.';
            $this->index();
            return;
        }

        try {
            if ($this->mper->createPerfil($nomper, $pgini)) {
                echo "<script>window.location.href='index.php?pg=1008&mensaje=" . urlencode('Perfil creado con éxito.') . "';</script>";
                exit;
            }
        } catch (Exception $e) {
            $this->alerta = 'Error al crear el perfil: ' . $e->getMessage();
        }

        $this->index(); 
    }

    // Activar/desactivar permisos de un perfil
    private function toggle() {
        $idpef = (int) ($_GET['idpef'] ?? 0);

        $campo = null;
        $valor = null;

        if (isset($_GET['insper'])) { $campo = 'insper'; $valor = $_GET['insper']; }
        else if (isset($_GET['updper'])) { $campo = 'updper'; $valor = $_GET['updper']; }
        else if (isset($_GET['delper'])) { $campo = 'delper'; $valor = $_GET['delper']; }
        
        if ($idpef > 0 && $campo && $valor !== null) {
            try {
                $this->mper->togglePermission($idpef, $campo, (int)$valor);
            } catch (Exception $e) {}
        }

        echo "<script>window.location.href='index.php?pg=1008';</script>";
        exit;
    }

    // Mostrar formulario de edición
    private function edit() {
        $idpef = (int) ($_GET['idpef'] ?? 0);
        $dtOne = null;
        
        if ($idpef > 0) {
            $dtOne = $this->mper->getOnePerfil($idpef);
            if (!$dtOne) $this->alerta = 'Perfil no encontrado.';
        } else {
            $this->alerta = 'ID de perfil no especificado.';
        }

        $dtAll = $this->mper->getAllPerfiles(); 
        $alerta = $this->alerta; 
        $pg = $_GET['pg'] ?? 1008; 
        $mper = $this->mper;

        require BASE_PATH . 'views/vper.php'; 
    }

    // Actualizar un perfil
    private function update() {
        $idpef = (int) ($_POST['idpef'] ?? 0);
        $nomper = $_POST['nombre'] ?? null;
        $pgini = $_POST['pgini'] ?? null;

        if ($idpef > 0 && !empty($nomper) && !empty($pgini)) {
            try {
                if ($this->mper->updatePerfil($idpef, $nomper, $pgini)) {
                    echo "<script>window.location.href='index.php?pg=1008&mensaje=" . urlencode('Perfil actualizado con éxito.') . "';</script>";
                    exit;
                } else {
                    $this->alerta = 'No se pudo actualizar el perfil. Verifique los datos.';
                }
            } catch (Exception $e) {
                $this->alerta = 'Error al actualizar el perfil: ' . $e->getMessage();
            }
        } else {
            $this->alerta = 'Faltan datos para la actualización.';
        }

        $this->edit(); 
    }

    // Eliminar un perfil
    private function delete() {
        $idpef = (int) ($_GET['idpef'] ?? 0);

        if ($idpef > 0) {
            $dtcon = $this->mper->getOneCon($idpef);
            
            if ($dtcon['con'] == 0) {
                try {
                    if ($this->mper->deletePerfil($idpef)) {
                        echo "<script>window.location.href='index.php?pg=1008&mensaje=" . urlencode('Perfil eliminado con éxito.') . "';</script>";
                        exit;
                    } else {
                        $this->alerta = 'No se pudo eliminar el perfil.';
                    }
                } catch (Exception $e) {
                    $this->alerta = 'Error al eliminar el perfil: ' . $e->getMessage();
                }
            } else {
                $this->alerta = 'No se puede eliminar el perfil porque tiene usuarios asociados.';
            }
        } else {
            $this->alerta = 'ID de perfil no especificado para eliminar.';
        }

        $this->index();
    }
}
