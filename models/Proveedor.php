<?php
// models/Proveedor.php
class Proveedor {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getProveedores() {
        $stmt = $this->db->query("SELECT * FROM proveedores ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($nombre, $ruc, $telefono, $direccion) {
        try {
            $stmt = $this->db->prepare("INSERT INTO proveedores (nombre, ruc, telefono, direccion) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$nombre, $ruc, $telefono, $direccion]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM proveedores WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>