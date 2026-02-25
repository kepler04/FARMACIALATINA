<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Financieros - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .finanzas-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; margin-bottom: 30px; }
        .card-finanza { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; border-bottom: 6px solid #e2e8f0; transition: 0.3s; }
        .card-finanza:hover { transform: translateY(-5px); }
        .card-ingresos { border-bottom-color: #3b82f6; }
        .card-costos { border-bottom-color: #f59e0b; }
        .card-utilidad { border-bottom-color: #22c55e; background: #f0fdf4; }
        
        .card-finanza h3 { margin: 0; color: #64748b; font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; }
        .card-finanza i { font-size: 2rem; margin-bottom: 15px; display: block; }
        .card-finanza .monto { font-size: 2.8rem; font-weight: 900; color: #0f172a; margin: 15px 0; }
        
        .filtro-fechas { background: white; padding: 25px; border-radius: 8px; display: flex; gap: 20px; align-items: flex-end; box-shadow: 0 4px 6px rgba(0,0,0,0.03); margin-bottom: 30px; border: 1px solid #e2e8f0; }
        
        .tabla-top { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .tabla-top th { background-color: #1e293b; color: white; padding: 18px; text-align: left; font-size: 1.1rem; text-transform: uppercase; }
        .tabla-top td { padding: 18px; border-bottom: 1px solid #f1f5f9; font-size: 1.2rem; vertical-align: middle; }
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
                <li><a href="index.php?view=vencimientos"><i class="fa-solid fa-triangle-exclamation"></i> Vencimientos</a></li>
                
                <li class="active"><a href="index.php?view=reportes"><i class="fa-solid fa-chart-pie"></i> Reportes / Finanzas</a></li>
                
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
                <span>Análisis Financiero</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; font-size: 2.2rem; margin-top:0; margin-bottom: 25px;"><i class="fa-solid fa-chart-line" style="color:#3b82f6;"></i> Balance de Utilidades</h2>
            
            <form method="POST" action="index.php?view=reportes" class="filtro-fechas">
                <div style="flex: 1;">
                    <label style="font-weight: bold; color: #475569; display: block; font-size: 1.1rem; margin-bottom: 8px;">Fecha Inicial:</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?>" style="height: 50px; font-size: 1.1rem;" required>
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold; color: #475569; display: block; font-size: 1.1rem; margin-bottom: 8px;">Fecha Final:</label>
                    <input type="date" name="fecha_fin" class="form-control" value="<?= $fecha_fin ?>" style="height: 50px; font-size: 1.1rem;" required>
                </div>
                <button type="submit" style="padding: 0 35px; height: 50px; background: #0f172a; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 1.1rem;"><i class="fa-solid fa-filter"></i> ANALIZAR PERIODO</button>
            </form>

            <div class="finanzas-grid">
                <div class="card-finanza card-ingresos">
                    <i class="fa-solid fa-cash-register" style="color: #3b82f6;"></i>
                    <h3>Ventas Totales (Bruto)</h3>
                    <div class="monto">$ <?= number_format($resumen['ingresos_totales'], 2) ?></div>
                    <p style="color:#64748b; font-size: 1rem;">Total facturado en el periodo.</p>
                </div>
                
                <div class="card-finanza card-costos">
                    <i class="fa-solid fa-truck-ramp-box" style="color: #f59e0b;"></i>
                    <h3>Inversión en Mercadería</h3>
                    <div class="monto" style="color:#ea580c;">$ <?= number_format($resumen['costos_totales'], 2) ?></div>
                    <p style="color:#64748b; font-size: 1rem;">Costo de los productos vendidos.</p>
                </div>

                <div class="card-finanza card-utilidad">
                    <i class="fa-solid fa-sack-dollar" style="color: #16a34a;"></i>
                    <h3 style="color:#16a34a;">GANANCIA NETA ESTIMADA</h3>
                    <div class="monto" style="color:#16a34a;">$ <?= number_format($resumen['ganancia_neta'], 2) ?></div>
                    <p style="color:#16a34a; font-size: 1.1rem; font-weight:bold;">¡Plata limpia para el negocio!</p>
                </div>
            </div>

            <h3 style="color: #0f172a; font-size: 1.8rem; margin-top: 50px; margin-bottom: 20px;"><i class="fa-solid fa-trophy" style="color:#eab308;"></i> Top 10 Productos con Mayor Rentabilidad</h3>
            <table class="tabla-top">
                <thead>
                    <tr>
                        <th style="width: 70px; text-align: center;">#</th>
                        <th>Medicamento / Producto</th>
                        <th style="text-align:center;">Cant. Vendida</th>
                        <th style="text-align:right;">Ingreso Generado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($top_productos)): ?>
                        <tr><td colspan="4" style="text-align:center; padding: 50px; color: #94a3b8; font-size: 1.3rem;">No se encontraron ventas para el rango seleccionado.</td></tr>
                    <?php else: ?>
                        <?php $puesto = 1; foreach($top_productos as $p): ?>
                            <tr>
                                <td style="font-weight:900; color:#94a3b8; text-align: center; font-size: 1.3rem;"><?= $puesto++ ?></td>
                                <td>
                                    <strong style="color: #0f172a;"><?= htmlspecialchars($p['descripcion']) ?></strong><br>
                                    <small style="color: #64748b; font-weight: bold;"><?= htmlspecialchars($p['laboratorio'] ?? 'Medicamento') ?></small>
                                </td>
                                <td style="text-align:center; font-weight:bold; color: #1e293b;"><?= $p['total_unidades'] ?> unidades</td>
                                <td style="text-align:right; font-weight:900; color:#16a34a; font-size: 1.3rem;">$ <?= number_format($p['total_recaudado'], 2) ?></td>
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