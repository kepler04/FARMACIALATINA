<?php
// models/Venta.php
class Venta {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function registrarVenta($carrito, $total, $cliente_nombre, $cliente_dni) {
        try {
            // Empezamos una "Transacción". Si algo falla, no se guarda nada a medias.
            $this->db->beginTransaction();

            // 1. Guardar la Cabecera de la Venta
            $stmt = $this->db->prepare("INSERT INTO ventas (total, cliente_nombre, cliente_dni) VALUES (?, ?, ?)");
            $stmt->execute([$total, $cliente_nombre, $cliente_dni]);
            $id_venta = $this->db->lastInsertId();

            // 2. Guardar los productos dentro del Ticket
            foreach ($carrito as $item) {
                $subtotal = $item['precio_venta'] * $item['cantidad'];
                $unidades_reales = isset($item['unidades_reales']) ? $item['unidades_reales'] : $item['cantidad'];

                $stmt2 = $this->db->prepare("INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio, subtotal, unidades_reales) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt2->execute([$id_venta, $item['id'], $item['cantidad'], $item['precio_venta'], $subtotal, $unidades_reales]);
            }

            // Confirmar que todo salió bien
            $this->db->commit();
            return $id_venta;

        } catch (PDOException $e) {
            // Si hay error, revertimos todo y mostramos el "Chivato"
            $this->db->rollBack();
            die("<div style='background:#fee2e2; color:#dc2626; padding:20px; font-size:1.2rem; border: 2px solid #dc2626; margin: 20px;'><strong>🚨 EL SISTEMA DETECTÓ UN ERROR EXACTO EN LA BASE DE DATOS AL VENDER:</strong><br><br>" . $e->getMessage() . "<br><br>Copia este mensaje rojo y pásamelo en el chat.</div>");
        }
    }

    public function getVenta($id) {
        $stmt = $this->db->prepare("SELECT * FROM ventas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetalles($id_venta) {
        $stmt = $this->db->prepare("SELECT d.*, p.descripcion FROM detalle_ventas d JOIN productos p ON d.id_producto = p.id WHERE d.id_venta = ?");
        $stmt->execute([$id_venta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistorial() {
        $stmt = $this->db->query("SELECT * FROM ventas ORDER BY fecha DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarVenta($id) {
        try {
            // Borra automáticamente los detalles gracias al ON DELETE CASCADE
            $stmt = $this->db->prepare("DELETE FROM ventas WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>