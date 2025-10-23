<?php
class Mper {
    private $db;

    public function __construct(conexion $conn) {
        $this->db = $conn->get_conexion(); 
    }

    /** Obtiene el siguiente ID disponible para un nuevo perfil */
    public function getNextId(): int {
        $stmt = $this->db->query("SELECT MAX(idper) AS max_id FROM perfil");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['max_id'] ?? 0) + 1; 
    }

    /** Crea un nuevo perfil */
    public function createPerfil(string $nomper, string $pgini): bool {
    $idper = $this->getNextId();
    $sql = "INSERT INTO perfil (idper, nomper, pgini, insper, updper, delper)
            VALUES (:idper, :nomper, :pgini, 0, 0, 0)";
    $stmt = $this->db->prepare($sql);

    try {
            return $stmt->execute([
                ':idper' => $idper,
                ':nomper' => $nomper,
                ':pgini' => (int)$pgini
            ]);
        } catch (PDOException $e) {
            // Código de error 1062 = entrada duplicada
            if ($e->errorInfo[1] == 1062) {
                throw new Exception("El nombre del perfil ya existe. Usa otro nombre.");
            } else {
                throw $e;
            }
        }
    }


    /** Obtiene todos los perfiles */
    public function getAllPerfiles(): array {
        $sql = "SELECT p.idper AS idpef, p.nomper AS nompef, p.pgini, 
                       p.insper, p.updper, p.delper, pa.nompag
                FROM perfil p
                LEFT JOIN pagina pa ON p.pgini = pa.idpag
                ORDER BY p.idper ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Obtiene un perfil específico por ID */
    public function getOnePerfil(int $idpef): ?array {
        $sql = "SELECT p.idper AS idpef, p.nomper AS nompef, p.pgini
                FROM perfil p
                WHERE p.idper = :idpef";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idpef' => $idpef]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null; 
    }

    /** Actualiza un perfil */
    public function updatePerfil(int $idpef, string $nomper, string $pgini): bool {
    $sql = "UPDATE perfil SET nomper = :nomper, pgini = :pgini WHERE idper = :idpef";
    $stmt = $this->db->prepare($sql);

    try {
            return $stmt->execute([
                ':nomper' => $nomper,
                ':pgini' => (int)$pgini,
                ':idpef' => $idpef
            ]);
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Error por duplicado al editar
                throw new Exception("Ya existe otro perfil con ese nombre. Usa uno diferente.");
            } else {
                throw $e;
            }
        }
    }

    /** Activa o desactiva un permiso (insper, updper, delper) */
    public function togglePermission(int $idpef, string $campo, int $valorUrl): bool {
        $allowedFields = ['insper', 'updper', 'delper'];
        if (!in_array($campo, $allowedFields)) {
            throw new InvalidArgumentException("Campo de permiso inválido: $campo"); 
        }
        $finalValor = ($valorUrl == 2) ? 0 : 1; 
        $sql = "UPDATE perfil SET $campo = :valor WHERE idper = :idpef";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':valor' => $finalValor,
            ':idpef' => $idpef
        ]);
    }
    
    /** Cuenta cuántos usuarios están asociados a un perfil */
    public function getOneCon(int $idpef): array {
        $sql = "SELECT COUNT(*) AS con FROM usuario WHERE idper = :idpef"; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idpef' => $idpef]);
        $count = (int)$stmt->fetch(PDO::FETCH_ASSOC)['con'];
        return ['con' => $count]; 
    }

    /** Elimina un perfil */
    public function deletePerfil(int $idpef): bool {
        $sql = "DELETE FROM perfil WHERE idper = :idpef";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':idpef' => $idpef]);
    }
}
