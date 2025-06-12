<?php
require_once __DIR__ . '/../../includes/conexion.php';

class InsigniaModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function obtenerInsigniasPorEstudiante($id_estudiante) {
        $sql = "SELECT i.* 
                FROM insignias i
                JOIN insignias_estudiantes ie ON i.id_insignia = ie.id_insignia
                WHERE ie.id_usuario = ?
                ORDER BY i.id_insignia DESC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_estudiante]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerInsigniasPorEstudiante: " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerPorUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT i.* FROM insignias i
            JOIN insignias_estudiantes ie ON i.id_insignia = ie.id_insignia
            WHERE ie.id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll();
    }
    
    private function determinarTablaInsignias() {
        // Verificamos qué tabla existe
        $tablas = ['estudiante_insignia', 'insignias_estudiantes', 'usuario_insignia'];
        
        foreach ($tablas as $tabla) {
            try {
                $stmt = $this->pdo->query("SELECT 1 FROM {$tabla} LIMIT 1");
                return $tabla;
            } catch (PDOException $e) {
                continue;
            }
        }
        
        // Si ninguna existe, usamos la primera como predeterminada
        return 'estudiante_insignia';
    }
    
    private function obtenerColumnasTabla($tabla) {
        try {
            $stmt = $this->pdo->query("DESCRIBE {$tabla}");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $columns;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function obtenerInsigniasDisponibles() {
        try {
            $sql = "SELECT * FROM insignias ORDER BY nombre";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerInsigniasDisponibles: " . $e->getMessage());
            return [];
        }
    }
    
    public function verificarYOtorgarInsignias($id_estudiante) {
        try {
            $this->verificarPrimeraEntrega($id_estudiante);
            $this->verificarBuenPromedio($id_estudiante);
            $this->verificarEntregasPuntuales($id_estudiante);
            return true;
        } catch (Exception $e) {
            error_log("Error en verificarYOtorgarInsignias: " . $e->getMessage());
            return false;
        }
    }
    
    private function verificarPrimeraEntrega($id_estudiante) {
        $tableName = $this->determinarTablaInsignias();
        $columns = $this->obtenerColumnasTabla($tableName);
        $columnaEstudiante = in_array('id_estudiante', $columns) ? 'id_estudiante' : 'id_usuario';
        
        try {
            $sql = "SELECT COUNT(*) FROM {$tableName} 
                    WHERE {$columnaEstudiante} = ? AND id_insignia = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_estudiante]);
            
            if ($stmt->fetchColumn() == 0) {
                $sql = "SELECT COUNT(*) FROM entregas WHERE id_estudiante = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$id_estudiante]);
                
                if ($stmt->fetchColumn() > 0) {
                    $this->otorgarInsignia($id_estudiante, 1);
                }
            }
        } catch (PDOException $e) {
            error_log("Error en verificarPrimeraEntrega: " . $e->getMessage());
        }
    }
    
    private function verificarBuenPromedio($id_estudiante) {
        try {
            $sql = "SELECT AVG(n.nota) as promedio
                    FROM notas n
                    JOIN entregas e ON n.id_entrega = e.id_entrega
                    WHERE e.id_estudiante = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_estudiante]);
            $resultado = $stmt->fetch();
            
            if ($resultado && $resultado['promedio'] >= 8.5) {
                $tableName = $this->determinarTablaInsignias();
                $columns = $this->obtenerColumnasTabla($tableName);
                $columnaEstudiante = in_array('id_estudiante', $columns) ? 'id_estudiante' : 'id_usuario';
                
                $sql = "SELECT COUNT(*) FROM {$tableName} 
                        WHERE {$columnaEstudiante} = ? AND id_insignia = 2";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$id_estudiante]);
                
                if ($stmt->fetchColumn() == 0) {
                    $this->otorgarInsignia($id_estudiante, 2);
                }
            }
        } catch (PDOException $e) {
            error_log("Error en verificarBuenPromedio: " . $e->getMessage());
        }
    }
    
    private function verificarEntregasPuntuales($id_estudiante) {
        try {
            $sql = "SELECT COUNT(*) as entregas_puntuales
                    FROM entregas e
                    JOIN actividades a ON e.id_actividad = a.id_actividad
                    WHERE e.id_estudiante = ? AND e.fecha_entrega <= a.fecha_limite";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_estudiante]);
            $resultado = $stmt->fetch();
            
            if ($resultado && $resultado['entregas_puntuales'] >= 10) {
                $tableName = $this->determinarTablaInsignias();
                $columns = $this->obtenerColumnasTabla($tableName);
                $columnaEstudiante = in_array('id_estudiante', $columns) ? 'id_estudiante' : 'id_usuario';
                
                $sql = "SELECT COUNT(*) FROM {$tableName} 
                        WHERE {$columnaEstudiante} = ? AND id_insignia = 3";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$id_estudiante]);
                
                if ($stmt->fetchColumn() == 0) {
                    $this->otorgarInsignia($id_estudiante, 3);
                }
            }
        } catch (PDOException $e) {
            error_log("Error en verificarEntregasPuntuales: " . $e->getMessage());
        }
    }
    
     private function otorgarInsignia($id_estudiante, $id_insignia) {
        $sql = "INSERT INTO insignias_estudiantes (id_usuario, id_insignia) 
                VALUES (?, ?)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_estudiante, $id_insignia]);
        } catch (PDOException $e) {
            error_log("Error en otorgarInsignia: " . $e->getMessage());
            return false;
        }
    }
}
?>