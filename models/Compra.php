<?php
// models/Compra.php
class Compra {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function registrarCompra($id_proveedor, $factura, $total, $carrito_compras) {
        try {
            // 1. Guardar la compra general
            $stmt = $this->db->prepare("INSERT INTO compras (id_proveedor, factura, total) VALUES (?, ?, ?)");
            $stmt->execute([$id_proveedor, $factura, $total]);
            $id_compra = $this->db->lastInsertId();

            // 2. Guardar el detalle y AUMENTAR EL STOCK
            foreach ($carrito_compras as $item) {
                $stmt2 = $this->db->prepare("INSERT INTO detalle_compras (id_compra, id_producto, cantidad, precio_compra) VALUES (?, ?, ?, ?)");
                $stmt2->execute([$id_compra, $item['id'], $item['cantidad'], $item['costo']]);

                // ¡AQUÍ ESTÁ LA MAGIA! Sumamos el stock nuevo al inventario
                $stmt3 = $this->db->prepare("UPDATE productos SET stock = stock + ?, compra = ? WHERE id = ?");
                $stmt3->execute([$item['cantidad_total_unidades'], $item['costo'], $item['id']]);
            }
            
            return $id_compra;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getHistorialCompras() {
        $stmt = $this->db->query("
            SELECT c.*, p.nombre as proveedor_nombre 
            FROM compras c 
            LEFT JOIN proveedores p ON c.id_proveedor = p.id 
            ORDER BY c.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>