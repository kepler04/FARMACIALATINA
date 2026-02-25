<?php
// index.php - CONTROLADOR PRINCIPAL
session_start();

// 1. CARGA DE DEPENDENCIAS
require_once 'config/db.php';
require_once 'models/Usuario.php';
require_once 'models/Producto.php';
require_once 'models/Venta.php'; 
require_once 'models/Cliente.php'; 
require_once 'models/Categoria.php';
require_once 'models/Proveedor.php'; 
require_once 'models/Compra.php'; 
require_once 'models/CajaTurno.php'; 
require_once 'models/Kardex.php'; 
require_once 'models/Reporte.php'; 

// 2. INICIALIZACIÓN
$db = Database::connect();
$usuarioModel = new Usuario($db);
$productoModel = new Producto($db);
$ventaModel = new Venta($db);
$clienteModel = new Cliente($db);
$categoriaModel = new Categoria($db);
$proveedorModel = new Proveedor($db);
$compraModel = new Compra($db); 
$cajaModel = new CajaTurno($db); 
$kardexModel = new Kardex($db); 
$reporteModel = new Reporte($db); 

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// ====================================================================
// ZONA PÚBLICA (LOGIN Y LOGOUT)
// ====================================================================

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario']) && isset($_POST['password'])) {
    $user = $_POST['usuario'];
    $pass = $_POST['password'];
    
    $datosUsuario = $usuarioModel->login($user, $pass);
    
    if ($datosUsuario) {
        $_SESSION['user_id'] = $datosUsuario['id'];
        $_SESSION['user_nombre'] = $datosUsuario['nombre'];
        $_SESSION['user_rol'] = $datosUsuario['rol']; 
        header("Location: index.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
        require 'views/login.php';
        exit;
    }
}

if (!isset($_SESSION['user_id'])) {
    require 'views/login.php';
    exit;
}

// ====================================================================
// 🚨 MURO DE SEGURIDAD (ROLES Y PERMISOS) 🚨
// ====================================================================
$vista_solicitada = isset($_GET['view']) ? $_GET['view'] : 'dashboard';

// Si es VENDEDOR y quiere entrar a una vista prohibida, lo pateamos a la Caja
if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 'Vendedor') {
    $vistas_prohibidas = [
        'dashboard', 'productos', 'categorias', 'historial', 
        'proveedores', 'compras', 'usuarios', 'editar_producto', 'editar_categoria', 'vencimientos', 'kardex', 'reportes', 'salidas'
    ];
    
    if (in_array($vista_solicitada, $vistas_prohibidas)) {
        header("Location: index.php?view=caja");
        exit;
    }
}

// ==========================================
// --- MÓDULO DE SALIDAS Y MERMAS ---
// ==========================================
if (isset($_GET['view']) && $_GET['view'] == 'salidas') {
    $listaProductos = $productoModel->getProductos();
    $historialMermas = $kardexModel->getHistorialSalidasMermas(); 
    require 'views/salidas.php';
    exit;
}

// Procesar el registro de una nueva salida
if (isset($_GET['action']) && $_GET['action'] == 'procesar_salida') {
    $id_producto = $_POST['id_producto'];
    $cantidad = (int)$_POST['cantidad'];
    $motivo = $_POST['motivo'];
    $detalles = $_POST['detalles'] ?? '';

    $stmtStock = $db->prepare("SELECT stock FROM productos WHERE id = ?");
    $stmtStock->execute([$id_producto]);
    $stock_actual = $stmtStock->fetchColumn();

    if ($cantidad > 0 && $cantidad <= $stock_actual) {
        $stmt = $db->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$cantidad, $id_producto]);
        $nuevo_stock = $stock_actual - $cantidad;
        $kardexModel->registrarMovimiento($id_producto, 'Salida', $cantidad, $nuevo_stock, "MERMA/AJUSTE: " . $motivo . " - " . $detalles);
        echo "<script>alert('✅ Salida registrada correctamente. El stock ha sido actualizado.'); window.location='index.php?view=salidas';</script>";
    } else {
        echo "<script>alert('❌ Error: Estás intentando sacar más unidades de las que hay en stock.'); window.location='index.php?view=salidas';</script>";
    }
    exit;
}

// ✨ NUEVO: Eliminar Salida y regresar el stock ✨
if (isset($_GET['action']) && $_GET['action'] == 'eliminar_salida') {
    $id_kardex = $_GET['id'];
    $exito = $kardexModel->anularSalidaManual($id_kardex);
    
    if ($exito) {
        echo "<script>alert('✅ Salida anulada con éxito. El producto ha regresado al inventario.'); window.location='index.php?view=salidas';</script>";
    } else {
        echo "<script>alert('❌ Error al anular la salida. Recuerda que las ventas se anulan desde la pantalla Historial.'); window.location='index.php?view=salidas';</script>";
    }
    exit;
}

// ==========================================
// --- MÓDULO DE FINANZAS Y REPORTES ---
// ==========================================
if (isset($_GET['view']) && $_GET['view'] == 'reportes') {
    $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : date('Y-m-01');
    $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : date('Y-m-d');

    $resumen = $reporteModel->getResumenFinanciero($fecha_inicio, $fecha_fin);
    $top_productos = $reporteModel->getProductosMasVendidos($fecha_inicio, $fecha_fin);

    require 'views/reportes.php';
    exit;
}

// ==========================================
// --- MÓDULO DE KARDEX (AUDITORÍA) ---
// ==========================================
if (isset($_GET['view']) && $_GET['view'] == 'kardex') {
    $listaProductos = $productoModel->getProductos();
    $movimientos = [];
    $producto_seleccionado = null;
    
    if(isset($_POST['id_producto']) && !empty($_POST['id_producto'])) {
        $id_buscado = $_POST['id_producto'];
        $movimientos = $kardexModel->getMovimientosPorProducto($id_buscado);
        $producto_seleccionado = $productoModel->getPorId($id_buscado);
    }
    require 'views/kardex.php';
    exit;
}

// ==========================================
// --- MÓDULO DE VENCIMIENTOS ---
// ==========================================
if (isset($_GET['view']) && $_GET['view'] == 'vencimientos') {
    $listaVencimientos = $productoModel->getProductosPorVencer(90); 
    require 'views/vencimientos.php';
    exit;
}


// ==========================================
// --- MÓDULO DE ARQUEO DE CAJA ---
// ==========================================
if (isset($_GET['view']) && $_GET['view'] == 'arqueo') {
    $turnoAbierto = $cajaModel->getTurnoAbierto();
    $ventasDelTurno = 0;
    if ($turnoAbierto) {
        $ventasDelTurno = $cajaModel->getVentasDelTurno($turnoAbierto['fecha_apertura']);
    }
    $historialTurnos = $cajaModel->getHistorialTurnos();

    // ✨ Calculamos las ventas exclusivas de HOY para el mini-dashboard
    $stmtVentasHoy = $db->query("SELECT COUNT(*) FROM ventas WHERE DATE(fecha) = CURDATE()");
    $tickets_hoy = $stmtVentasHoy->fetchColumn();

    $stmtIngresosHoy = $db->query("SELECT SUM(total) FROM ventas WHERE DATE(fecha) = CURDATE()");
    $ingresos_hoy = $stmtIngresosHoy->fetchColumn();
    if (!$ingresos_hoy) $ingresos_hoy = 0;

    require 'views/arqueo.php';
    exit;
}
if (isset($_GET['action']) && $_GET['action'] == 'abrir_caja') {
    $monto_inicial = $_POST['monto_inicial'];
    $cajaModel->abrirCaja($_SESSION['user_id'], $monto_inicial);
    header("Location: index.php?view=arqueo&msg=caja_abierta");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'cerrar_caja') {
    $id_turno = $_POST['id_turno'];
    $monto_esperado = $_POST['monto_esperado'];
    $monto_real = $_POST['monto_real']; 
    
    $cajaModel->cerrarCaja($id_turno, $monto_esperado, $monto_real);
    header("Location: index.php?view=arqueo&msg=caja_cerrada");
    exit;
}

// ====================================================================
// ZONA PRIVADA (SOLO USUARIOS LOGUEADOS)
// ====================================================================

// ==========================================
// --- MÓDULO DE INVENTARIO Y CATEGORÍAS ---
// ==========================================

// ✨ AUTO-PARCHE: Crear tabla de categorías mágicamente si no existe ✨
try {
    $db->exec("CREATE TABLE IF NOT EXISTS categorias (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(100) UNIQUE)");
    $stmt = $db->query("SELECT COUNT(*) FROM categorias");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO categorias (nombre) VALUES ('Medicamento'), ('Insumo Médico'), ('Cuidado Personal'), ('Higiene'), ('Vitaminas')");
    }
} catch(PDOException $e) {}

// --- RUTAS DE CATEGORÍAS ---
if (isset($_GET['action']) && $_GET['action'] == 'agregar_categoria') {
    try {
        $stmt = $db->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        $stmt->execute([trim($_POST['nombre_categoria'])]);
    } catch(PDOException $e) {} // Si ya existe, la ignora sin error
    header("Location: index.php?view=productos");
    exit;
}
if (isset($_GET['action']) && $_GET['action'] == 'eliminar_categoria') {
    $stmt = $db->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: index.php?view=productos");
    exit;
}

// --- RUTAS DE PRODUCTOS ---
if (isset($_GET['action']) && $_GET['action'] == 'guardar_producto') {
    $receta = isset($_POST['requiere_receta']) ? 1 : 0;
    $lab = $_POST['laboratorio'] ?? ''; $pres = $_POST['presentacion'] ?? ''; $lote = $_POST['lote'] ?? '';
    $vence = !empty($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : null;
    $stock_min = $_POST['stock_minimo'] ?? 20; $categoria = $_POST['categoria'] ?? 'Medicamentos'; $detalles = $_POST['detalles'] ?? ''; 

    $exito = $productoModel->registrar(
        $_POST['codigo'], $_POST['descripcion'], $_POST['compra'] ?? 0, $_POST['venta'] ?? 0, $_POST['stock'] ?? 0,
        $_POST['principio_activo'] ?? '', $lab, $pres, $lote, $vence, $receta,
        $_POST['unidades_por_caja'] ?? 1, $_POST['unidades_por_blister'] ?? 1, 
        $_POST['precio_caja'] ?? 0, $_POST['precio_blister'] ?? 0, $_POST['precio_unidad'] ?? 0,
        $stock_min, $categoria, $detalles
    );
    if ($exito) header("Location: index.php?view=productos&msg=guardado"); else header("Location: index.php?view=productos&msg=error"); exit;
}

if (isset($_GET['view']) && $_GET['view'] == 'productos') {
    $listaProductos = $productoModel->getProductos(); 
    
    // Extraemos la lista dinámica de categorías para inyectarla en la vista
    $stmt = $db->query("SELECT * FROM categorias ORDER BY nombre ASC");
    $listaCategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    require 'views/productos.php'; 
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'eliminar_producto') {
    $productoModel->eliminar($_GET['id']); header("Location: index.php?view=productos&msg=eliminado"); exit;
}

if (isset($_GET['view']) && $_GET['view'] == 'editar_producto') {
    $producto = $productoModel->getPorId($_GET['id']); 
    
    // Extraemos la lista dinámica también para la pantalla de editar
    $stmt = $db->query("SELECT * FROM categorias ORDER BY nombre ASC");
    $listaCategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    require 'views/editar_producto.php'; 
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'actualizar_producto') {
    $receta = isset($_POST['requiere_receta']) ? 1 : 0;
    $lab = $_POST['laboratorio'] ?? ''; $pres = $_POST['presentacion'] ?? ''; $lote = $_POST['lote'] ?? '';
    $vence = !empty($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : null;
    $stock_min = $_POST['stock_minimo'] ?? 20; $categoria = $_POST['categoria'] ?? 'Medicamentos'; $detalles = $_POST['detalles'] ?? ''; 

    $exito = $productoModel->actualizar(
        $_POST['id'], $_POST['codigo'], $_POST['descripcion'], $_POST['compra'] ?? 0, $_POST['venta'] ?? 0, $_POST['stock'] ?? 0,
        $_POST['principio_activo'] ?? '', $lab, $pres, $lote, $vence, $receta,
        $_POST['unidades_por_caja'] ?? 1, $_POST['unidades_por_blister'] ?? 1, 
        $_POST['precio_caja'] ?? 0, $_POST['precio_blister'] ?? 0, $_POST['precio_unidad'] ?? 0,
        $stock_min, $categoria, $detalles
    );
    if ($exito) header("Location: index.php?view=productos&msg=actualizado"); else header("Location: index.php?view=productos&msg=error"); exit;
}


// --- GESTIÓN DE CAJA (POS) ---
if (isset($_GET['view']) && $_GET['view'] == 'caja') {
    $turnoAbierto = $cajaModel->getTurnoAbierto();
    if (!$turnoAbierto) { header("Location: index.php?view=arqueo&msg=requiere_apertura"); exit; }

    $listaProductos = $productoModel->getProductos(); $listaClientes = $clienteModel->getClientes(); require 'views/caja.php'; exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'agregar_al_carrito') {
    $id = $_POST['id']; $cantidad_solicitada = (int)$_POST['cantidad']; $descuento = (float)$_POST['descuento']; $tipo_venta = $_POST['tipo_venta']; 
    $stmt = $db->prepare("SELECT * FROM productos WHERE id = ?"); $stmt->execute([$id]); $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        $stock_real = $producto['stock']; $precio_item = 0; $unidades_a_descontar = 0; $etiqueta = '';

        if ($tipo_venta == 'caja') { $precio_item = $producto['precio_caja']; $unidades_a_descontar = $producto['unidades_por_caja'] * $cantidad_solicitada; $etiqueta = 'Caja'; } 
        elseif ($tipo_venta == 'blister') { $precio_item = $producto['precio_blister']; $unidades_a_descontar = $producto['unidades_por_blister'] * $cantidad_solicitada; $etiqueta = 'Blíster'; } 
        else { $precio_item = $producto['precio_unidad']; $unidades_a_descontar = 1 * $cantidad_solicitada; $etiqueta = 'Unidad'; }

        if ($precio_item <= 0) { echo "<script>alert('❌ No puedes vender una $etiqueta porque su precio está en $0.00.'); window.location='index.php?view=caja';</script>"; exit; }

        $unidades_en_carrito = 0;
        if (!empty($_SESSION['carrito'])) { foreach ($_SESSION['carrito'] as $item) { if ($item['id'] == $id) { $unidades_en_carrito += $item['unidades_reales']; } } }

        if (($unidades_en_carrito + $unidades_a_descontar) > $stock_real) { echo "<script>alert('❌ STOCK INSUFICIENTE.'); window.location='index.php?view=caja';</script>"; exit; }
        if ($descuento >= $precio_item) { echo "<script>alert('❌ El descuento no puede ser mayor o igual al precio ($precio_item).'); window.location='index.php?view=caja';</script>"; exit; }

        $item = [ 'id' => $producto['id'], 'codigo' => $producto['codigo'], 'descripcion' => $producto['descripcion'] . ' (x1 ' . $etiqueta . ')', 'precio_venta' => $precio_item, 'stock_max' => $producto['stock'], 'cantidad' => $cantidad_solicitada, 'descuento' => $descuento, 'unidades_reales' => $unidades_a_descontar ];
        $_SESSION['carrito'][] = $item;
    }
    header("Location: index.php?view=caja"); exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'eliminar_item') {
    $index = $_GET['index']; if(isset($_SESSION['carrito'][$index])) { unset($_SESSION['carrito'][$index]); $_SESSION['carrito'] = array_values($_SESSION['carrito']); }
    header("Location: index.php?view=caja"); exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'vaciar_carrito') { $_SESSION['carrito'] = []; header("Location: index.php?view=caja"); exit; }

if (isset($_GET['action']) && ($_GET['action'] == 'sumar_cantidad' || $_GET['action'] == 'restar_cantidad')) {
    $index = $_GET['index'];
    if (isset($_SESSION['carrito'][$index])) {
        $item_actual = $_SESSION['carrito'][$index]; $unidades_por_item = $item_actual['unidades_reales'] / $item_actual['cantidad']; 
        if($_GET['action'] == 'sumar_cantidad') {
            $id_buscado = $item_actual['id']; $total_unds = 0; foreach ($_SESSION['carrito'] as $c) { if ($c['id'] == $id_buscado) $total_unds += $c['unidades_reales']; }
            if (($total_unds + $unidades_por_item) <= $item_actual['stock_max']) { $_SESSION['carrito'][$index]['cantidad']++; $_SESSION['carrito'][$index]['unidades_reales'] += $unidades_por_item; } 
            else { echo "<script>alert('Stock máximo alcanzado.');</script>"; }
        } else {
            $_SESSION['carrito'][$index]['cantidad']--; $_SESSION['carrito'][$index]['unidades_reales'] -= $unidades_por_item;
            if($_SESSION['carrito'][$index]['cantidad'] < 1) { unset($_SESSION['carrito'][$index]); $_SESSION['carrito'] = array_values($_SESSION['carrito']); }
        }
    }
    header("Location: index.php?view=caja"); exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'finalizar_venta') {
    if (empty($_SESSION['carrito'])) { header("Location: index.php?view=caja"); exit; }

    $tipo_cliente = $_POST['tipo_cliente'] ?? 'existente';
    $total = 0; $carrito_para_bd = [];

    foreach ($_SESSION['carrito'] as $p) {
        $precio_con_descuento = $p['precio_venta'] - $p['descuento'];
        $total += $precio_con_descuento * $p['cantidad']; $p['precio_venta'] = $precio_con_descuento; $carrito_para_bd[] = $p;
    }
    
    $nombre_cliente = 'Público General'; 
    $dni_cliente = '';
    $id_cliente = 0;

    // ✨ MAGIA 1: Si se eligió registrar uno nuevo en la misma caja
    if ($tipo_cliente == 'nuevo') {
        $nombre_cliente = $_POST['nuevo_nombre'] ?? 'Sin Nombre';
        $dni_cliente = $_POST['nuevo_dni'] ?? '';
        $telefono = $_POST['nuevo_telefono'] ?? '';
        
        try {
            $stmt = $db->prepare("INSERT INTO clientes (nombre, dni, telefono, direccion, puntos) VALUES (?, ?, ?, '', 0)");
            if($stmt->execute([$nombre_cliente, $dni_cliente, $telefono])) {
                $id_cliente = $db->lastInsertId(); // Capturamos el ID del nuevo cliente
            }
        } catch(PDOException $e) {}
    } else {
        // ✨ MAGIA 2: Si es un cliente existente o público general
        $id_cliente = (int)($_POST['id_cliente'] ?? 0);
        if ($id_cliente > 0) {
            $cliente_data = $clienteModel->getPorId($id_cliente);
            if ($cliente_data) {
                $nombre_cliente = $cliente_data['nombre'];
                $dni_cliente = $cliente_data['dni'];
            }
        }
    }
    
    // Registramos la venta en la BD
    $id_venta = $ventaModel->registrarVenta($carrito_para_bd, $total, $nombre_cliente, $dni_cliente);

    if ($id_venta) {
        // ✨ MAGIA 3: SISTEMA DE PUNTOS AUTOMÁTICO (1 punto x cada dólar/sol gastado) ✨
        if ($id_cliente > 0) {
            $puntos_ganados = floor($total); // Redondea hacia abajo (ej. $15.80 = 15 pts)
            try {
                $stmtPts = $db->prepare("UPDATE clientes SET puntos = puntos + ? WHERE id = ?");
                $stmtPts->execute([$puntos_ganados, $id_cliente]);
            } catch(PDOException $e) {}
        }

        // Descontamos stock del Kardex
        foreach ($_SESSION['carrito'] as $p) {
            $cant_a_descontar = $p['unidades_reales']; 
            if ($cant_a_descontar > 0) {
                $stmt = $db->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?"); $stmt->execute([$cant_a_descontar, $p['id']]);
                $stmtStock = $db->prepare("SELECT stock FROM productos WHERE id = ?"); $stmtStock->execute([$p['id']]); $nuevo_stock = $stmtStock->fetchColumn();
                $kardexModel->registrarMovimiento($p['id'], 'Salida', $cant_a_descontar, $nuevo_stock, "Venta Ticket #" . str_pad($id_venta, 5, '0', STR_PAD_LEFT));
            }
        }
        $_SESSION['carrito'] = []; header("Location: index.php?view=boleta&id=" . $id_venta);
    } else { echo "<script>alert('❌ Error al procesar la venta'); window.location='index.php?view=caja';</script>"; }
    exit;
}

if (isset($_GET['view']) && $_GET['view'] == 'boleta') {
    $id_venta = $_GET['id']; $venta = $ventaModel->getVenta($id_venta); $detalles = $ventaModel->getDetalles($id_venta);
    if ($venta) require 'views/boleta.php'; else echo "Boleta no encontrada"; exit;
}

// ==========================================
// --- ZONA DE CLIENTES E HISTORIAL ---
// ==========================================

if (isset($_GET['view']) && $_GET['view'] == 'clientes') { $listaClientes = $clienteModel->getClientes(); require 'views/clientes.php'; exit; }
if (isset($_GET['action']) && $_GET['action'] == 'guardar_cliente') { $clienteModel->registrar($_POST['nombre'], $_POST['dni'], $_POST['telefono'], $_POST['direccion']); header("Location: index.php?view=clientes"); exit; }
if (isset($_GET['action']) && $_GET['action'] == 'eliminar_cliente') { $clienteModel->eliminar($_GET['id']); header("Location: index.php?view=clientes"); exit; }

// ✨ NUEVAS RUTAS PARA EDITAR CLIENTES ✨
if (isset($_GET['view']) && $_GET['view'] == 'editar_cliente') {
    $cliente = $clienteModel->getPorId($_GET['id']);
    require 'views/editar_cliente.php'; 
    exit;
}
if (isset($_GET['action']) && $_GET['action'] == 'actualizar_cliente') {
    $clienteModel->actualizar($_POST['id'], $_POST['nombre'], $_POST['dni'], $_POST['telefono'], $_POST['direccion'], $_POST['puntos']);
    echo "<script>alert('✅ Cliente actualizado correctamente'); window.location='index.php?view=clientes';</script>";
    exit;
}

// ✨ RUTA PARA VER EL HISTORIAL DE COMPRAS DEL CLIENTE ✨
if (isset($_GET['view']) && $_GET['view'] == 'historial_cliente') {
    $cliente = $clienteModel->getPorId($_GET['id']);
    $historialCompras = [];
    
    if ($cliente) {
        // Buscamos las ventas del cliente en la BD (escudo contra diferentes nombres de columnas)
        try {
            $stmt = $db->prepare("SELECT * FROM ventas WHERE nombre_cliente = ? OR cliente = ? ORDER BY fecha DESC LIMIT 50");
            $stmt->execute([$cliente['nombre'], $cliente['nombre']]);
            $historialCompras = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            try {
                $stmt = $db->prepare("SELECT * FROM ventas WHERE cliente_nombre = ? ORDER BY fecha DESC LIMIT 50");
                $stmt->execute([$cliente['nombre']]);
                $historialCompras = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e2) {
                $historialCompras = [];
            }
        }
    }
    require 'views/historial_cliente.php'; 
    exit;
}

// ==========================================
// --- ZONA DE PROVEEDORES ---
// ==========================================

if (isset($_GET['view']) && $_GET['view'] == 'proveedores') { $listaProveedores = $proveedorModel->getProveedores(); require 'views/proveedores.php'; exit; }
if (isset($_GET['action']) && $_GET['action'] == 'guardar_proveedor') { $proveedorModel->registrar($_POST['nombre'], $_POST['ruc'], $_POST['telefono'], $_POST['direccion']); header("Location: index.php?view=proveedores&msg=guardado"); exit; }
if (isset($_GET['action']) && $_GET['action'] == 'eliminar_proveedor') { $proveedorModel->eliminar($_GET['id']); header("Location: index.php?view=proveedores&msg=eliminado"); exit; }

// ==========================================
// --- MÓDULO DE COMPRAS (ENTRADAS) ---
// ==========================================
if (isset($_GET['view']) && $_GET['view'] == 'compras') { $listaProveedores = $proveedorModel->getProveedores(); $listaProductos = $productoModel->getProductos(); require 'views/compras.php'; exit; }

if (isset($_GET['action']) && $_GET['action'] == 'procesar_compra') {
    if(!isset($_POST['prod_ids']) || empty($_POST['prod_ids'])) { echo "<script>alert('❌ No has agregado ningún producto a la lista.'); window.location='index.php?view=compras';</script>"; exit; }

    $id_prov = $_POST['id_proveedor']; $num_factura = $_POST['factura'] ?: 'Sin Factura';
    $productos_ids = $_POST['prod_ids']; $cantidades = $_POST['cantidades']; $costos = $_POST['costos']; $total_compra = 0;
    
    foreach ($productos_ids as $key => $id) {
        $cant = $cantidades[$key]; $costo = $costos[$key]; $subtotal = $cant * $costo; $total_compra += $subtotal;

        $stmt = $db->prepare("UPDATE productos SET stock = stock + ?, compra = ? WHERE id = ?"); $stmt->execute([$cant, $costo, $id]);

        $stmtStock = $db->prepare("SELECT stock FROM productos WHERE id = ?"); $stmtStock->execute([$id]); $nuevo_stock = $stmtStock->fetchColumn();

        $kardexModel->registrarMovimiento($id, 'Entrada', $cant, $nuevo_stock, "Compra de Mercadería. Factura/Guía: " . $num_factura);
    }
    
    echo "<script>alert('✅ ¡Inventario y Kardex actualizados correctamente!'); window.location='index.php?view=productos';</script>"; exit;
}


// --- MÓDULO DE HISTORIAL DE VENTAS ---
if (isset($_GET['view']) && $_GET['view'] == 'historial') {
    // Obtenemos todas las ventas para la tabla
    $stmt = $db->query("SELECT * FROM ventas ORDER BY fecha DESC");
    $listaVentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    require 'views/historial.php'; 
    exit;
}

// ==========================================
// --- ZONA DE USUARIOS Y SEGURIDAD ---
// ==========================================
if (isset($_GET['view']) && $_GET['view'] == 'usuarios') { $listaUsuarios = $usuarioModel->getUsuarios(); require 'views/usuarios.php'; exit; }
if (isset($_GET['action']) && $_GET['action'] == 'guardar_usuario') { $usuarioModel->registrar($_POST['usuario'], $_POST['password'], $_POST['nombre'], $_POST['rol']); header("Location: index.php?view=usuarios&msg=guardado"); exit; }
if (isset($_GET['action']) && $_GET['action'] == 'eliminar_usuario') { $usuarioModel->eliminar($_GET['id']); header("Location: index.php?view=usuarios&msg=eliminado"); exit; }


// ====================================================================
// VISTA POR DEFECTO (DASHBOARD REAL)
// ====================================================================
if (!isset($_GET['view']) || $_GET['view'] == 'dashboard') {
    $stmt = $db->query("SELECT COUNT(*) FROM clientes"); $total_clientes = $stmt->fetchColumn();
    $stmt = $db->query("SELECT COUNT(*) FROM productos"); $total_productos = $stmt->fetchColumn();
    $stmt = $db->query("SELECT COUNT(*) FROM productos WHERE stock <= stock_minimo"); $bajo_stock = $stmt->fetchColumn();
    $stmt = $db->query("SELECT COUNT(*) FROM ventas WHERE DATE(fecha) = CURDATE()"); $ventas_hoy = $stmt->fetchColumn();
    $stmt = $db->query("SELECT SUM(total) FROM ventas WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())"); $ingresos_mes = $stmt->fetchColumn();
    if (!$ingresos_mes) $ingresos_mes = 0;
    $stmt = $db->query("SELECT COUNT(*) FROM usuarios"); $total_usuarios = $stmt->fetchColumn();
    $stmt = $db->query("SELECT COUNT(*) FROM proveedores"); $total_proveedores = $stmt->fetchColumn();
    $stmt = $db->query("SELECT COUNT(*) FROM productos WHERE fecha_vencimiento IS NOT NULL AND fecha_vencimiento != '0000-00-00' AND DATEDIFF(fecha_vencimiento, CURDATE()) <= 90"); $alertas_vencimiento = $stmt->fetchColumn();

    require 'views/dashboard.php'; exit;
}
?>