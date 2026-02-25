<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kardex / Auditoría - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .panel-filtro { background: white; padding: 25px; border-radius: 8px; border-top: 5px solid #0f172a; box-shadow: 0 4px 10px rgba(0,0,0,0.03); margin-bottom: 25px; }
        .panel-tabla { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        
        .tabla-kardex { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabla-kardex th { background: #f8fafc; padding: 15px; text-align: left; font-size: 0.95rem; color: #475569; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; }
        .tabla-kardex td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; vertical-align: middle; }
        
        .badge-tipo { padding: 5px 12px; border-radius: 6px; font-weight: bold; font-size: 0.9rem; }
        .tipo-entrada { background: #dcfce7; color: #16a34a; }
        .tipo-salida { background: #fee2e2; color: #dc2626; }
        
        .stock-resaltado { font-weight: 900; color: #0f172a; font-size: 1.2rem; }
        
        .select2-container .select2-selection--single { height: 50px !important; display: flex; align-items: center; font-size: 1.1rem; }
    </style>
</head>
<body>
<div class="wrapper">
    <aside class="sidebar">
        <div class="sidebar-header"><i class="fa-solid fa-briefcase-medical"></i><span>BOTICA LATINA</span></div>
        <ul class="sidebar-menu">
            <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 'Administrador'): ?>
                <li><a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
            <?php endif; ?>
            
            <li><a href="index.php?view=caja"><i class="fa-solid fa-cash-register"></i> Punto de Venta</a></li>
            <li><a href="index.php?view=arqueo"><i class="fa-solid fa-vault"></i> Caja / Arqueo</a></li>
            <li><a href="index.php?view=clientes"><i class="fa-solid fa-users"></i> Clientes</a></li>

            <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 'Administrador'): ?>
<li><a href="index.php?view=productos"><i class="fa-solid fa-box-open"></i> Productos</a></li>
                <li class="active"><a href="index.php?view=kardex"><i class="fa-solid fa-clipboard-list"></i> Kardex / Auditoría</a></li>
                
                <li><a href="index.php?view=vencimientos"><i class="fa-solid fa-triangle-exclamation"></i> Vencimientos</a></li>
                <li><a href="index.php?view=reportes"><i class="fa-solid fa-chart-pie"></i> Reportes / Finanzas</a></li>
                <li><a href="index.php?view=proveedores"><i class="fa-solid fa-truck-field"></i> Proveedores</a></li>
                <li><a href="index.php?view=compras"><i class="fa-solid fa-truck-ramp-box"></i> Entradas</a></li>
                <li><a href="index.php?view=salidas"><i class="fa-solid fa-right-from-bracket"></i> Salidas / Mermas</a></li>
                <li><a href="index.php?view=historial"><i class="fa-solid fa-file-invoice-dollar"></i> Historial</a></li>
                <li><a href="index.php?view=usuarios"><i class="fa-solid fa-user-shield"></i> Usuarios</a></li>
            <?php endif; ?>
            
            <li style="margin-top: 40px;"><a href="index.php?action=logout" style="color: #fecaca;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            <div class="user-info">
                <span>Auditoría de Inventario</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; margin-top:0; font-size: 2.2rem; margin-bottom: 25px;"><i class="fa-solid fa-file-shield" style="color:#dc2626;"></i> Kardex de Movimientos</h2>
            
            <div class="panel-filtro">
                <form method="POST" action="index.php?view=kardex" style="display: flex; gap: 20px; align-items: flex-end;">
                    <div style="flex: 1;">
                        <label style="display:block; font-weight:bold; margin-bottom:8px; font-size: 1.1rem;">Selecciona un Producto:</label>
                        <select name="id_producto" id="buscador_productos" class="form-control" required>
                            <option value=""></option>
                            <?php foreach($listaProductos as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= (isset($id_buscado) && $id_buscado == $p['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['descripcion']) ?> (Stock Actual: <?= $p['stock'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" style="background:#0f172a; color:white; border:none; padding: 15px 30px; border-radius: 6px; font-weight:bold; cursor:pointer; font-size: 1.1rem;">
                        <i class="fa-solid fa-magnifying-glass"></i> VER MOVIMIENTOS
                    </button>
                </form>
            </div>

            <div class="panel-tabla">
                <?php if($producto_seleccionado): ?>
                    <h3 style="margin-top:0; color:#1e293b; margin-bottom: 20px; font-size: 1.5rem;">Historial: <?= htmlspecialchars($producto_seleccionado['descripcion']) ?></h3>
                <?php endif; ?>

                <table class="tabla-kardex">
                    <thead>
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Movimiento</th>
                            <th>Cantidad</th>
                            <th>Stock Resultante</th>
                            <th>Descripción / Referencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($movimientos)): ?>
                            <tr><td colspan="5" style="text-align:center; padding: 60px; color:#94a3b8; font-size: 1.2rem;">Busca un producto para ver su trazabilidad.</td></tr>
                        <?php else: ?>
                            <?php foreach($movimientos as $m): 
                                $es_salida = ($m['tipo_movimiento'] == 'Salida');
                            ?>
                                <tr>
                                    <td style="color:#64748b; font-size: 1rem;"><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></td>
                                    <td>
                                        <span class="badge-tipo <?= $es_salida ? 'tipo-salida' : 'tipo-entrada' ?>">
                                            <i class="fa-solid <?= $es_salida ? 'fa-arrow-trend-down' : 'fa-arrow-trend-up' ?>"></i>
                                            <?= $m['tipo_movimiento'] ?>
                                        </span>
                                    </td>
                                    <td style="font-weight:bold; font-size: 1.1rem; color: <?= $es_salida ? '#dc2626' : '#16a34a' ?>;">
                                        <?= $es_salida ? '-' : '+' ?> <?= $m['cantidad'] ?>
                                    </td>
                                    <td class="stock-resaltado"><?= $m['stock_resultante'] ?></td>
                                    <td style="color:#475569;"><?= htmlspecialchars($m['descripcion']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#buscador_productos').select2({ placeholder: "Escribe nombre del medicamento...", allowClear: true, width: '100%' });
    });
</script>
</body>
</html>