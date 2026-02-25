<?php
// models/Producto.php
class Producto {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getProductos() {
        $stmt = $this->db->query("SELECT * FROM productos WHERE estado = 1 ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // REGISTRAR (Con 19 campos, incluyendo 'detalles')
    public function registrar($codigo, $descripcion, $compra, $venta, $stock, $principio, $lab, $pres, $lote, $vence, $receta, $u_caja, $u_blister, $p_caja, $p_blister, $p_unidad, $stock_minimo, $categoria, $detalles) {
        try {
            $sql = "INSERT INTO productos (codigo, descripcion, precio_compra, precio_venta, stock, estado, 
                    principio_activo, laboratorio, presentacion, lote, fecha_vencimiento, requiere_receta,
                    unidades_por_caja, unidades_por_blister, precio_caja, precio_blister, precio_unidad, stock_minimo, categoria, detalles) 
                    VALUES (?, ?, ?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $vence = !empty($vence) ? $vence : null; 
            return $stmt->execute([$codigo, $descripcion, $compra, $venta, $stock, $principio, $lab, $pres, $lote, $vence, $receta, $u_caja, $u_blister, $p_caja, $p_blister, $p_unidad, $stock_minimo, $categoria, $detalles]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR (Con 19 campos, incluyendo 'detalles')
    public function actualizar($id, $codigo, $descripcion, $compra, $venta, $stock, $principio, $lab, $pres, $lote, $vence, $receta, $u_caja, $u_blister, $p_caja, $p_blister, $p_unidad, $stock_minimo, $categoria, $detalles) {
        try {
            $sql = "UPDATE productos SET codigo=?, descripcion=?, precio_compra=?, precio_venta=?, stock=?, 
                    principio_activo=?, laboratorio=?, presentacion=?, lote=?, fecha_vencimiento=?, requiere_receta=?,
                    unidades_por_caja=?, unidades_por_blister=?, precio_caja=?, precio_blister=?, precio_unidad=?, stock_minimo=?, categoria=?, detalles=? 
                    WHERE id=?";
            $stmt = $this->db->prepare($sql);
            $vence = !empty($vence) ? $vence : null;
            return $stmt->execute([$codigo, $descripcion, $compra, $venta, $stock, $principio, $lab, $pres, $lote, $vence, $receta, $u_caja, $u_blister, $p_caja, $p_blister, $p_unidad, $stock_minimo, $categoria, $detalles, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $stmt1 = $this->db->prepare("DELETE FROM detalle_ventas WHERE id_producto = ?");
            $stmt1->execute([$id]);
            $stmt2 = $this->db->prepare("DELETE FROM productos WHERE id = ?");
            return $stmt2->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>