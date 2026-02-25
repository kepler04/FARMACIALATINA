<?php
// models/Kardex.php
class Kardex {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function registrarMovimiento($id_producto, $tipo, $cantidad, $stock_resultante, $descripcion) {
        try {
            $stmt = $this->db->prepare("INSERT INTO kardex (id_producto, tipo_movimiento, cantidad, stock_resultante, descripcion) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$id_producto, $tipo, $cantidad, $stock_resultante, $descripcion]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getMovimientosPorProducto($id_producto) {
        $stmt = $this->db->prepare("SELECT * FROM kardex WHERE id_producto = ? ORDER BY fecha DESC");
        $stmt->execute([$id_producto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistorialSalidasMermas() {
        $sql = "SELECT k.*, p.descripcion as nombre_producto 
                FROM kardex k 
                JOIN productos p ON k.id_producto = p.id 
                WHERE k.tipo_movimiento = 'Salida' 
                ORDER BY k.fecha DESC LIMIT 50";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✨ ESTA ES LA FUNCIÓN QUE NO ENCONTRABA EL SISTEMA ✨
    public function anularSalidaManual($id_kardex) {
        try {
            $this->db->beginTransaction();

            // 1. Buscamos qué producto fue y cuántas unidades salieron
            $stmt = $this->db->prepare("SELECT * FROM kardex WHERE id = ?");
            $stmt->execute([$id_kardex]);
            $mov = $stmt->fetch(PDO::FETCH_ASSOC);

            // 🚨 PROTECCIÓN: Solo permite borrar mermas. Las VENTAS se borran desde "Historial de Ventas".
            if ($mov && strpos($mov['descripcion'], 'Venta') === false) {
                
                // 2. Le regresamos la cantidad matemática al stock del producto
                $stmtStock = $this->db->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
                $stmtStock->execute([$mov['cantidad'], $mov['id_producto']]);

                // 3. Borramos la salida del historial Kardex
                $stmtDel = $this->db->prepare("DELETE FROM kardex WHERE id = ?");
                $stmtDel->execute([$id_kardex]);

                $this->db->commit();
                return true;
            }
            $this->db->rollBack();
            return false;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>