<?php
// ajax_buscar.php
require_once 'config/db.php';
$db = Database::connect();

$termino = $_GET['term'] ?? '';

if ($termino) {
    // Busca por Código O por Descripción
    $stmt = $db->prepare("SELECT * FROM productos WHERE codigo LIKE ? OR descripcion LIKE ? LIMIT 10");
    $stmt->execute(["%$termino%", "%$termino%"]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devolvemos el resultado en formato JSON (para que Javascript lo entienda)
    echo json_encode($productos);
}
?>