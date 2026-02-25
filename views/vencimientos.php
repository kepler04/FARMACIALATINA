<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Vencimientos - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Ajustes específicos para que la tabla sea GIGANTE y visible */
        .tabla-vencimientos { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .tabla-vencimientos th { background-color: #1e293b; color: white; padding: 18px; text-align: left; font-size: 1.1rem; text-transform: uppercase; }
        .tabla-vencimientos td { padding: 18px; border-bottom: 1px solid #e2e8f0; font-size: 1.2rem; vertical-align: middle; }
        
        .btn-oferta { background: #ea580c; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-size: 1rem; font-weight: bold; display: inline-block; transition: 0.2s; }
        .btn-oferta:hover { background: #c2410c; transform: scale(1.05); }

        .badge-vencido { background: #dc2626; color: white; padding: 8px 12px; border-radius: 6px; font-weight: 900; }
        .badge-critico { background: #ea580c; color: white; padding: 8px 12px; border-radius: 6px; font-weight: bold; }
        .badge-proximo { background: #ca8a04; color: white; padding: 8px 12px; border-radius: 6px; font-weight: bold; }
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
                <li><a href="index.php?view=kardex"><i class="fa-solid fa-clipboard-list"></i> Kardex / Auditoría</a></li>
                
                <li class="active"><a href="index.php?view=vencimientos"><i class="fa-solid fa-triangle-exclamation"></i> Vencimientos</a></li>
                
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
                <span>Alertas Sanitarias</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; font-size: 2.2rem; margin-top:0;"><i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;"></i> Control de Vencimientos (90 Días)</h2>
            <p style="color: #64748b; font-size: 1.2rem; margin-bottom: 25px;">Monitorea los productos próximos a caducar para gestionar ofertas o devoluciones.</p>

            <table class="tabla-vencimientos">
                <thead>
                    <tr>
                        <th>Medicamento</th>
                        <th>Lote</th>
                        <th>Caducidad</th>
                        <th>Estado / Tiempo</th>
                        <th style="text-align: center;">Stock</th>
                        <th>Acción Sugerida</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($listaVencimientos)): ?>
                        <tr><td colspan="6" style="text-align:center; padding: 60px; color: #94a3b8; font-size: 1.3rem;">¡Excelente! No hay productos próximos a vencer.</td></tr>
                    <?php else: ?>
                        <?php foreach($listaVencimientos as $p): 
                            $dias = $p['dias_restantes'];
                            $color_fila = ''; $estado_html = ''; $accion_texto = '';

                            if ($dias < 0) {
                                $color_fila = '#fee2e2'; // Rojo suave
                                $estado_html = '<span class="badge-vencido"><i class="fa-solid fa-skull-crossbones"></i> VENCIDO (Hace '.abs($dias).' días)</span>';
                                $accion_texto = 'RETIRAR DE ESTANTE';
                            } elseif ($dias <= 30) {
                                $color_fila = '#ffedd5'; // Naranja suave
                                $estado_html = '<span class="badge-critico"><i class="fa-solid fa-fire-flame-curved"></i> CRÍTICO: '.$dias.' días</span>';
                                $accion_texto = 'REMATAR / OFERTA';
                            } else {
                                $color_fila = '#fef9c3'; // Amarillo suave
                                $estado_html = '<span class="badge-proximo"><i class="fa-solid fa-hourglass-half"></i> Faltan '.$dias.' días</span>';
                                $accion_texto = 'VENDER PRIMERO';
                            }
                        ?>
                        <tr style="background-color: <?= $color_fila ?>;">
                            <td>
                                <strong style="color: #0f172a;"><?= htmlspecialchars($p['descripcion']) ?></strong><br>
                                <small style="color: #64748b; font-weight: bold;"><?= htmlspecialchars($p['principio_activo'] ?? 'Medicamento') ?></small>
                            </td>
                            <td style="font-weight: bold; color: #475569;"><?= htmlspecialchars($p['lote'] ?: 'S/L') ?></td>
                            <td style="font-weight: 900; color: #1e293b;"><?= date('d/m/Y', strtotime($p['fecha_vencimiento'])) ?></td>
                            <td><?= $estado_html ?></td>
                            <td style="font-size: 1.3rem; font-weight: 900; text-align: center; color: #0f172a;"><?= $p['stock'] ?></td>
                            <td>
                                <?php if($dias >= 0): ?>
                                    <a href="index.php?view=editar_producto&id=<?= $p['id'] ?>" class="btn-oferta"><i class="fa-solid fa-pen-to-square"></i> <?= $accion_texto ?></a>
                                <?php else: ?>
                                    <strong style="color: #dc2626; font-size: 1rem;"><i class="fa-solid fa-ban"></i> <?= $accion_texto ?></strong>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>