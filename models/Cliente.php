<?php
// models/Cliente.php
class Cliente {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
        try {
            $this->db->exec("ALTER TABLE clientes ADD COLUMN puntos INT DEFAULT 0");
        } catch(PDOException $e) {}
    }

    public function registrar($nombre, $dni, $telefono, $direccion) {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre, dni, telefono, direccion, puntos) VALUES (?, ?, ?, ?, 0)");
        return $stmt->execute([$nombre, $dni, $telefono, $direccion]);
    }

    public function getClientes() {
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY puntos DESC, nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✨ NUEVA FUNCIÓN: Para editar los datos y los puntos
    public function actualizar($id, $nombre, $dni, $telefono, $direccion, $puntos) {
        $stmt = $this->db->prepare("UPDATE clientes SET nombre = ?, dni = ?, telefono = ?, direccion = ?, puntos = ? WHERE id = ?");
        return $stmt->execute([$nombre, $dni, $telefono, $direccion, $puntos, $id]);
    }
}
?>