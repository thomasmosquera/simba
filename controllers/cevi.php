 <?php
require_once("models/mevi.php");

class cevi {
    private $modelo;

    public function __construct() {
        $this->modelo = new mevi();
    }

    public function mostrarVista() {
        $evidencias = $this->modelo->getEvidencias();
        $mascota = $this->modelo->getMascotas();

        // Generar HTML para las opciones del select
        $opcionesMascota = '';
        if (!empty($mascota)) {
            foreach ($mascota as $m) {
                $opcionesMascota .= '<option value="' . htmlspecialchars($m['idmasc']) . '">' . htmlspecialchars($m['idmasc']) . ' - ' . htmlspecialchars($m['nommasc']) . '</option>';
            }
        }

        // Generar HTML para las filas de la tabla de evidencias
        $filasEvidencias = '';
        if (!empty($evidencias)) {
            foreach ($evidencias as $evi) {
                $filasEvidencias .= '<tr>';
                $filasEvidencias .= '<td>' . htmlspecialchars($evi['idevid']) . '</td>';
                $filasEvidencias .= '<td>' . htmlspecialchars($evi['nommasc']) . '</td>';
                $filasEvidencias .= '<td>' . htmlspecialchars($evi['fechact']) . '</td>';
                $filasEvidencias .= '<td>' . htmlspecialchars($evi['descripcion']) . '</td>';
                $filasEvidencias .= '<td>' . htmlspecialchars($evi['fechevi']) . '</td>';
                $filasEvidencias .= '<td><a href="uploads/' . htmlspecialchars($evi['archivo']) . '" target="_blank" class="btn btn-sm btn-outline-light">Ver</a></td>';
                $filasEvidencias .= '</tr>';
            }
        } else {
            $filasEvidencias = '<tr><td colspan="7" class="text-center">No hay evidencias registradas.</td></tr>';
        }

        // Cargar la vista y reemplazar los marcadores
        $vista = file_get_contents("views/vevi.php");
        $vista = str_replace('<!-- Aquí el controlador debe insertar las opciones de mascotas -->', $opcionesMascota, $vista);
        $vista = str_replace('<!-- Aquí el controlador debe insertar las filas de la tabla de evidencias -->', $filasEvidencias, $vista);
        echo $vista;
    }
}

$controlador = new cevi();
$controlador->mostrarVista();
?>
