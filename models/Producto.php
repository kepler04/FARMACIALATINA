    <?php
    // models/Producto.php
    class Producto {
        private $db;

        public function __construct($conexion) {
            $this->db = $conexion;
        }

        public function getProductos() {
            $stmt = $this->db->query("SELECT * FROM productos ORDER BY descripcion ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getPorId($id) {
            $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function registrar($codigo, $descripcion, $compra, $venta, $stock, $principio_activo, $laboratorio, $presentacion, $lote, $fecha_vencimiento, $requiere_receta, $unidades_por_caja, $unidades_por_blister, $precio_caja, $precio_blister, $precio_unidad, $stock_minimo, $categoria, $detalles) {
            try {
                $stmt = $this->db->prepare("INSERT INTO productos (codigo, descripcion, compra, venta, stock, principio_activo, laboratorio, presentacion, lote, fecha_vencimiento, requiere_receta, unidades_por_caja, unidades_por_blister, precio_caja, precio_blister, precio_unidad, stock_minimo, categoria, detalles) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                return $stmt->execute([$codigo, $descripcion, $compra, $venta, $stock, $principio_activo, $laboratorio, $presentacion, $lote, $fecha_vencimiento, $requiere_receta, $unidades_por_caja, $unidades_por_blister, $precio_caja, $precio_blister, $precio_unidad, $stock_minimo, $categoria, $detalles]);
            } catch (PDOException $e) { 
                // EL CHIVATO: Si la base de datos rechaza el guardado, imprimimos el error exacto
                die("<div style='background:#fee2e2; color:#dc2626; padding:20px; font-size:1.2rem; border: 2px solid #dc2626; margin: 20px;'><strong>🚨 EL SISTEMA DETECTÓ UN ERROR EXACTO EN LA BASE DE DATOS:</strong><br><br>" . $e->getMessage() . "<br><br>Copia este mensaje rojo y pásamelo en el chat para arreglarlo en 1 segundo.</div>");
            }
        }

        public function actualizar($id, $codigo, $descripcion, $compra, $venta, $stock, $principio_activo, $laboratorio, $presentacion, $lote, $fecha_vencimiento, $requiere_receta, $unidades_por_caja, $unidades_por_blister, $precio_caja, $precio_blister, $precio_unidad, $stock_minimo, $categoria, $detalles) {
            try {
                $stmt = $this->db->prepare("UPDATE productos SET codigo=?, descripcion=?, compra=?, venta=?, stock=?, principio_activo=?, laboratorio=?, presentacion=?, lote=?, fecha_vencimiento=?, requiere_receta=?, unidades_por_caja=?, unidades_por_blister=?, precio_caja=?, precio_blister=?, precio_unidad=?, stock_minimo=?, categoria=?, detalles=? WHERE id=?");
                return $stmt->execute([$codigo, $descripcion, $compra, $venta, $stock, $principio_activo, $laboratorio, $presentacion, $lote, $fecha_vencimiento, $requiere_receta, $unidades_por_caja, $unidades_por_blister, $precio_caja, $precio_blister, $precio_unidad, $stock_minimo, $categoria, $detalles, $id]);
            } catch (PDOException $e) { 
                die("<div style='background:#fee2e2; color:#dc2626; padding:20px; font-size:1.2rem; border: 2px solid #dc2626; margin: 20px;'><strong>🚨 ERROR SQL EXACTO AL ACTUALIZAR:</strong><br><br>" . $e->getMessage() . "</div>");
            }
        }

        public function eliminar($id) {
            try {
                $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
                return $stmt->execute([$id]);
            } catch (Exception $e) { 
                return false; 
            }
        }

        public function getProductosPorVencer($dias = 90) {
            $stmt = $this->db->prepare("
                SELECT *, DATEDIFF(fecha_vencimiento, CURDATE()) as dias_restantes
                FROM productos 
                WHERE fecha_vencimiento IS NOT NULL 
                AND fecha_vencimiento != '0000-00-00'
                AND DATEDIFF(fecha_vencimiento, CURDATE()) <= ?
                ORDER BY dias_restantes ASC
            ");
            $stmt->execute([$dias]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    ?>