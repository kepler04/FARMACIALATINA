<?php
// models/Categoria.php
class Categoria {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getCategorias() {
        $stmt = $this->db->query("SELECT * FROM categorias ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($nombre) {
        try {
            $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (?)");
            return $stmt->execute([$nombre]);
        } catch (Exception $e) {
            return false;
        }
    }

    // NUEVO: Obtener una sola categoría para editarla
    public function getPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // NUEVO: Guardar los cambios de la categoría
    public function actualizar($id, $nombre) {
        try {
            $stmt = $this->db->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
            return $stmt->execute([$nombre, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>