<?php
// models/Reporte.php
class Reporte {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    // 1. Calcula: Ingresos, Costos y la Ganancia Neta Real
    public function getResumenFinanciero($fecha_inicio, $fecha_fin) {
        $sql = "SELECT 
                    SUM(d.subtotal) as ingresos_totales,
                    SUM(p.compra * d.unidades_reales) as costos_totales,
                    SUM(d.subtotal - (p.compra * d.unidades_reales)) as ganancia_neta
                FROM ventas v
                JOIN detalle_ventas d ON v.id = d.id_venta
                JOIN productos p ON d.id_producto = p.id
                WHERE DATE(v.fecha) >= ? AND DATE(v.fecha) <= ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha_inicio, $fecha_fin]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si no hay ventas, devolvemos 0 para evitar errores
        return [
            'ingresos_totales' => $resultado['ingresos_totales'] ?: 0,
            'costos_totales' => $resultado['costos_totales'] ?: 0,
            'ganancia_neta' => $resultado['ganancia_neta'] ?: 0
        ];
    }

    // 2. Ranking de los 10 productos que más dinero dejan
    public function getProductosMasVendidos($fecha_inicio, $fecha_fin) {
        $sql = "SELECT 
                    p.descripcion, 
                    p.laboratorio,
                    SUM(d.unidades_reales) as total_unidades,
                    SUM(d.subtotal) as total_recaudado
                FROM detalle_ventas d
                JOIN ventas v ON d.id_venta = v.id
                JOIN productos p ON d.id_producto = p.id
                WHERE DATE(v.fecha) >= ? AND DATE(v.fecha) <= ?
                GROUP BY d.id_producto
                ORDER BY total_recaudado DESC 
                LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha_inicio, $fecha_fin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>