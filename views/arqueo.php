<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Caja / Arqueo - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .layout-arqueo { display: flex; flex-direction: column; gap: 30px; }
        
        .mini-dashboard { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .mini-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; border-left: 6px solid #e2e8f0; }
        .card-blue { border-left-color: #3b82f6; }
        .card-green { border-left-color: #22c55e; }
        .card-yellow { border-left-color: #f59e0b; }
        .card-red { border-left-color: #dc2626; }
        .mini-card-info h3 { font-size: 2.2rem; color: #0f172a; margin: 0 0 5px 0; font-weight: bold; }
        .mini-card-info p { color: #64748b; font-size: 1rem; margin: 0; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .mini-card-icon { font-size: 3.5rem; color: #f1f5f9; }

        .panel-accion { background: white; padding: 40px; border-radius: 8px; border-top: 5px solid #3b82f6; box-shadow: 0 4px 10px rgba(0,0,0,0.03); max-width: 600px; margin: 0 auto; text-align: center; width: 100%; }
        .panel-accion.cerrar { border-top-color: #f59e0b; }
        .panel-accion h3 { font-size: 1.8rem; color: #0f172a; margin-bottom: 10px; }
        .panel-accion p { color: #64748b; font-size: 1.1rem; margin-bottom: 25px; }
        
        .panel-historial { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .tabla-arqueo { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabla-arqueo th { background: #f8fafc; padding: 15px; text-align: left; font-size: 1rem; color: #475569; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; }
        .tabla-arqueo td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; vertical-align: middle; }
        
        .badge-estado { padding: 5px 10px; border-radius: 6px; font-weight: bold; font-size: 0.9rem; }
        .estado-abierto { background: #dcfce7; color: #16a34a; }
        .estado-cerrado { background: #f1f5f9; color: #64748b; }
        
        .descuadre-positivo { color: #16a34a; font-weight: bold; }
        .descuadre-negativo { color: #dc2626; font-weight: bold; }
        .descuadre-perfecto { color: #64748b; }
        
        .form-control-gigante { width: 100%; padding: 20px; font-size: 2rem; text-align: center; border: 2px solid #cbd5e1; border-radius: 8px; font-weight: bold; color: #0f172a; outline: none; transition: 0.3s; margin-bottom: 20px;}
        .form-control-gigante:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        
        .btn-accion { padding: 18px 30px; font-size: 1.25rem; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; width: 100%; color: white; transition: 0.2s;}
        .btn-abrir { background: #3b82f6; } .btn-abrir:hover { background: #2563eb; }
        .btn-cerrar { background: #f59e0b; } .btn-cerrar:hover { background: #d97706; }
        
        /* ✨ ESTILOS PARA EL SÚPER BOTÓN DE VENTA ✨ */
        .btn-vender-rapido { display: block; width: 100%; padding: 25px; background: #10b981; color: white; text-align: center; text-decoration: none; font-size: 1.6rem; font-weight: bold; border-radius: 8px; transition: 0.2s; box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3); margin-bottom: 35px; border: 2px solid #059669; }
        .btn-vender-rapido:hover { background: #059669; transform: translateY(-2px); }
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
            <li class="active"><a href="index.php?view=arqueo"><i class="fa-solid fa-vault"></i> Caja / Arqueo</a></li>
            <li><a href="index.php?view=clientes"><i class="fa-solid fa-users"></i> Clientes</a></li>

            <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 'Administrador'): ?>
                <li><a href="index.php?view=productos"><i class="fa-solid fa-box-open"></i> Productos</a></li>
                <li><a href="index.php?view=kardex"><i class="fa-solid fa-clipboard-list"></i> Kardex / Auditoría</a></li>
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
                <?php if($turnoAbierto): ?>
                    <span style="color:#16a34a; font-weight:bold;"><i class="fa-solid fa-lock-open"></i> Turno Abierto</span>
                <?php else: ?>
                    <span style="color:#dc2626; font-weight:bold;"><i class="fa-solid fa-lock"></i> Turno Cerrado</span>
                <?php endif; ?>
                <span style="margin-left: 15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.5rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; margin-top:0; font-size: 2.2rem; margin-bottom: 25px;"><i class="fa-solid fa-vault" style="color:#dc2626;"></i> Gestión de Caja / Arqueo</h2>
            
            <div class="mini-dashboard">
                <div class="mini-card card-blue">
                    <div class="mini-card-info">
                        <h3><?= isset($tickets_hoy) ? $tickets_hoy : 0 ?></h3>
                        <p>Tickets Emitidos Hoy</p>
                    </div>
                    <i class="fa-solid fa-receipt mini-card-icon"></i>
                </div>
                
                <div class="mini-card card-green">
                    <div class="mini-card-info">
                        <h3 style="color:#16a34a;">$ <?= number_format(isset($ingresos_hoy) ? $ingresos_hoy : 0, 2) ?></h3>
                        <p>Ingresos Totales del Día</p>
                    </div>
                    <i class="fa-solid fa-hand-holding-dollar mini-card-icon" style="color:#dcfce7;"></i>
                </div>
                
                <div class="mini-card <?= $turnoAbierto ? 'card-green' : 'card-red' ?>">
                    <div class="mini-card-info">
                        <h3 style="color: <?= $turnoAbierto ? '#16a34a' : '#dc2626' ?>"><?= $turnoAbierto ? 'ABIERTA' : 'CERRADA' ?></h3>
                        <p>Estado de la Caja</p>
                    </div>
                    <i class="fa-solid <?= $turnoAbierto ? 'fa-lock-open' : 'fa-lock' ?> mini-card-icon" style="color: <?= $turnoAbierto ? '#dcfce7' : '#fee2e2' ?>;"></i>
                </div>
            </div>

            <div class="layout-arqueo">
                <?php if(!$turnoAbierto): ?>
                    <div class="panel-accion">
                        <i class="fa-solid fa-cash-register" style="font-size: 4rem; color: #3b82f6; margin-bottom: 15px;"></i>
                        <h3>Apertura de Caja Registradora</h3>
                        <p>Ingresa el monto de "sencillo" (base) con el que estás empezando tu turno hoy.</p>
                        
                        <form method="POST" action="index.php?action=abrir_caja">
                            <input type="number" step="0.01" name="monto_inicial" class="form-control-gigante" placeholder="0.00" required>
                            <button type="submit" class="btn-accion btn-abrir"><i class="fa-solid fa-unlock"></i> ABRIR TURNO DE CAJA</button>
                        </form>
                    </div>

                <?php else: ?>
                    <?php 
                        $monto_inicial = $turnoAbierto['monto_inicial'];
                        $monto_esperado = $monto_inicial + $ventasDelTurno;
                    ?>
                    <div class="panel-accion cerrar">
                        
                        <a href="index.php?view=caja" class="btn-vender-rapido">
                            <i class="fa-solid fa-cart-shopping" style="margin-right: 10px;"></i> ¡IR A VENDER AHORA!
                        </a>

                        <hr style="border: none; border-top: 2px dashed #cbd5e1; margin-bottom: 30px;">

                        <i class="fa-solid fa-scale-balanced" style="font-size: 3rem; color: #f59e0b; margin-bottom: 15px;"></i>
                        <h3 style="font-size: 1.5rem;">Cierre de Caja y Arqueo</h3>
                        <p style="font-size: 1rem;">Declara cuánto dinero físico tienes en el cajón ahora mismo.</p>
                        
                        <div style="display:flex; justify-content: space-around; background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <div>
                                <span style="display:block; color:#64748b; font-size:0.9rem; text-transform:uppercase; font-weight:bold;">Monto Inicial</span>
                                <span style="font-size: 1.4rem; font-weight:bold;">$ <?= number_format($monto_inicial, 2) ?></span>
                            </div>
                            <div>
                                <span style="display:block; color:#64748b; font-size:0.9rem; text-transform:uppercase; font-weight:bold;">Ventas del Turno</span>
                                <span style="font-size: 1.4rem; font-weight:bold; color: #16a34a;">+ $ <?= number_format($ventasDelTurno, 2) ?></span>
                            </div>
                        </div>

                        <form method="POST" action="index.php?action=cerrar_caja">
                            <input type="hidden" name="id_turno" value="<?= $turnoAbierto['id'] ?>">
                            <input type="hidden" name="monto_esperado" value="<?= $monto_esperado ?>">
                            
                            <label style="font-weight:bold; color:#0f172a; display:block; text-align:left; margin-bottom:10px; font-size: 1.1rem;">Efectivo Físico en Cajón ($):</label>
                            <input type="number" step="0.01" name="monto_real" class="form-control-gigante" placeholder="0.00" required>
                            
                            <button type="submit" class="btn-accion btn-cerrar" onclick="return confirm('¿Estás seguro de cerrar el turno de caja ahora?');"><i class="fa-solid fa-lock"></i> CERRAR TURNO Y ARQUEAR</button>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="panel-historial">
                    <h3 style="margin-top:0; font-size: 1.5rem; margin-bottom: 20px; color:#0f172a;"><i class="fa-solid fa-clock-rotate-left"></i> Historial de Últimos Turnos</h3>
                    
                    <div style="overflow-x: auto;">
                        <table class="tabla-arqueo">
                            <thead>
                                <tr>
                                    <th>Apertura</th>
                                    <th>Cierre</th>
                                    <th>Cajero / Usuario</th>
                                    <th>Monto Inicial</th>
                                    <th>Ventas</th>
                                    <th>Esperado</th>
                                    <th>Dinero Real</th>
                                    <th>Descuadre</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($historialTurnos)): ?>
                                    <tr><td colspan="9" style="text-align:center; padding: 40px; color:#94a3b8;">No hay turnos registrados en el sistema.</td></tr>
                                <?php else: ?>
                                    <?php foreach($historialTurnos as $t): 
                                        $monto_esperado = $t['monto_esperado'] ?? $t['monto_inicial'];
                                        $monto_real = $t['monto_real'] ?? 0;
                                        
                                        $ventas = isset($t['ventas_turno']) ? $t['ventas_turno'] : ($monto_esperado - $t['monto_inicial']);
                                        $descuadre = $monto_real - $monto_esperado;
                                        
                                        $clase_descuadre = 'descuadre-perfecto';
                                        if ($descuadre > 0) $clase_descuadre = 'descuadre-positivo';
                                        if ($descuadre < 0) $clase_descuadre = 'descuadre-negativo';
                                        
                                        $esta_cerrado = !empty($t['fecha_cierre']) && $t['fecha_cierre'] != '0000-00-00 00:00:00';
                                        $nombre_cajero = $t['nombre_usuario'] ?? 'Admin / Sistema';
                                    ?>
                                        <tr>
                                            <td style="font-size:0.95rem; color:#475569;"><?= date('d/m/Y H:i', strtotime($t['fecha_apertura'])) ?></td>
                                            <td style="font-size:0.95rem; color:#475569;">
                                                <?= $esta_cerrado ? date('d/m/Y H:i', strtotime($t['fecha_cierre'])) : '--' ?>
                                            </td>
                                            <td style="font-weight:bold; color:#0f172a;">
                                                <i class="fa-solid fa-user-tie" style="color:#94a3b8; margin-right:5px;"></i> <?= htmlspecialchars($nombre_cajero) ?>
                                            </td>
                                            <td style="font-weight:bold;">$ <?= number_format($t['monto_inicial'], 2) ?></td>
                                            <td style="color:#16a34a; font-weight:bold;">$ <?= number_format($ventas, 2) ?></td>
                                            <td style="font-weight:bold;">$ <?= number_format($monto_esperado, 2) ?></td>
                                            <td style="font-weight:900; color:#0f172a;">
                                                <?= $esta_cerrado ? '$ ' . number_format($monto_real, 2) : '--' ?>
                                            </td>
                                            <td class="<?= $clase_descuadre ?>">
                                                <?php 
                                                    if ($esta_cerrado) {
                                                        echo ($descuadre > 0 ? '+' : '') . '$ ' . number_format($descuadre, 2);
                                                    } else {
                                                        echo '--';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(!$esta_cerrado): ?>
                                                    <span class="badge-estado estado-abierto"><i class="fa-solid fa-door-open"></i> Abierto</span>
                                                <?php else: ?>
                                                    <span class="badge-estado estado-cerrado"><i class="fa-solid fa-door-closed"></i> Cerrado</span>
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
        </div>
    </div>
</div>
</body>
</html>