<?php
// models/CajaTurno.php
class CajaTurno {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
        try {
            $this->db->exec("ALTER TABLE caja_turnos ADD COLUMN monto_esperado DECIMAL(10,2) NULL");
            $this->db->exec("ALTER TABLE caja_turnos ADD COLUMN monto_real DECIMAL(10,2) NULL");
        } catch(PDOException $e) {}
    }

    public function getTurnoAbierto() {
        $stmt = $this->db->query("SELECT * FROM caja_turnos WHERE fecha_cierre IS NULL OR fecha_cierre = '0000-00-00 00:00:00' ORDER BY id DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVentasDelTurno($fecha_apertura) {
        $stmt = $this->db->prepare("SELECT SUM(total) FROM ventas WHERE fecha >= ?");
        $stmt->execute([$fecha_apertura]);
        return $stmt->fetchColumn() ?: 0;
    }

    // ✨ AQUÍ ESTÁ LA MAGIA NUEVA: Traemos el nombre del usuario ✨
    public function getHistorialTurnos() {
        $stmt = $this->db->query("
            SELECT c.*, u.nombre as nombre_usuario 
            FROM caja_turnos c 
            LEFT JOIN usuarios u ON c.id_usuario = u.id 
            ORDER BY c.fecha_apertura DESC 
            LIMIT 50
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function abrirCaja($id_usuario, $monto_inicial) {
        $stmt = $this->db->prepare("INSERT INTO caja_turnos (id_usuario, monto_inicial, fecha_apertura, estado) VALUES (?, ?, NOW(), 'Abierto')");
        return $stmt->execute([$id_usuario, $monto_inicial]);
    }

    public function cerrarCaja($id_turno, $monto_esperado, $monto_real) {
        $stmt = $this->db->prepare("UPDATE caja_turnos SET fecha_cierre = NOW(), estado = 'Cerrado', monto_esperado = ?, monto_real = ? WHERE id = ?");
        return $stmt->execute([$monto_esperado, $monto_real, $id_turno]);
    }
}
?>