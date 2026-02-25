<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ventas - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .historial-container { padding: 30px; }
        .card-table { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header-title { color: #1e293b; font-size: 1.8rem; margin-top: 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 25px; font-weight: 700; display:flex; align-items:center; gap: 15px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #dc2626; color: white; padding: 18px; text-align: left; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.5px; }
        th:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
        th:last-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
        td { padding: 18px; border-bottom: 1px solid #e2e8f0; color: #334155; font-size: 1.1rem; }
        tr:hover td { background-color: #f8fafc; }
        
        .badge-total { font-weight: 800; color: #166534; background: #f0fdf4; padding: 8px 15px; border-radius: 6px; border: 1px solid #bbf7d0; display: inline-block; font-size: 1.1rem; }
        
        .acciones-td { display: flex; gap: 12px; justify-content: center; }
        .btn-ver { background: #3b82f6; color: white; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 0.95rem; font-weight: bold; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-ver:hover { background: #2563eb; transform: translateY(-2px); }
        
        .btn-eliminar { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; text-decoration: none; padding: 10px 15px; border-radius: 6px; font-size: 1rem; transition: 0.2s; display: inline-flex; align-items: center; }
        .btn-eliminar:hover { background: #fee2e2; color: #dc2626; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-briefcase-medical"></i><span>BOTICA LATINA</span>
        </div>
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
                <li><a href="index.php?view=reportes"><i class="fa-solid fa-chart-pie"></i> Reportes / Finanzas</a></li>
                <li><a href="index.php?view=proveedores"><i class="fa-solid fa-truck-field"></i> Proveedores</a></li>
                <li><a href="index.php?view=compras"><i class="fa-solid fa-truck-ramp-box"></i> Entradas</a></li>
                <li><a href="index.php?view=salidas"><i class="fa-solid fa-right-from-bracket"></i> Salidas / Mermas</a></li>
                
                <li class="active"><a href="index.php?view=historial"><i class="fa-solid fa-file-invoice-dollar"></i> Historial</a></li>
                
                <li><a href="index.php?view=usuarios"><i class="fa-solid fa-user-shield"></i> Usuarios</a></li>
            <?php endif; ?>
            
            <li style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="index.php?action=logout" style="color: #fecaca;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </aside>

    <div class="main-content" style="background-color: #f1f5f9;">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            <div class="user-info">
                <span>Registro Maestro de Ventas</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="font-size: 1.8rem; color:#dc2626;"></i>
            </div>
        </header>

        <div class="historial-container">
            
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'eliminado'): ?>
                <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
                    <i class="fa-solid fa-circle-check"></i> La venta ha sido eliminada del historial permanentemente.
                </div>
            <?php endif; ?>

            <div class="card-table">
                <h2 class="header-title"><i class="fa-solid fa-clock-rotate-left" style="color: #dc2626;"></i> Historial de Ventas Completadas</h2>
                
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>N° Ticket</th>
                                <th>Fecha y Hora</th>
                                <th>Cliente</th>
                                <th>Total Pagado</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Usamos $listaVentas que es la variable que viene del nuevo index.php maestro
                            if(empty($listaVentas)): 
                            ?>
                                <tr><td colspan="5" style="text-align:center; padding: 60px; color: #64748b; font-size: 1.2rem;">No hay ventas registradas en el sistema.</td></tr>
                            <?php else: ?>
                                <?php foreach($listaVentas as $v): ?>
                                <tr>
                                    <td style="font-weight: bold; color: #0f172a; font-family: monospace;">#<?php echo str_pad($v['id'], 6, "0", STR_PAD_LEFT); ?></td>
                                    <td><?php echo date("d/m/Y - h:i A", strtotime($v['fecha'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($v['nombre_cliente'] ?? 'Público General'); ?></strong></td>
                                    <td><span class="badge-total">$ <?php echo number_format($v['total'], 2); ?></span></td>
                                    
                                    <td class="acciones-td">
                                        <a href="index.php?view=boleta&id=<?php echo $v['id']; ?>" class="btn-ver" title="Ver Boleta" target="_blank">
                                            <i class="fa-solid fa-eye"></i> Ver Ticket
                                        </a>
                                        
                                        <a href="index.php?action=eliminar_venta&id=<?php echo $v['id']; ?>" class="btn-eliminar" title="Eliminar Venta" onclick="return confirm('🚨 ¿ESTÁS SEGURO? Esta acción borrará la venta del historial para siempre.');">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
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

</body>
</html>