<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Cliente - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .perfil-header { background: white; padding: 30px; border-radius: 8px; border-top: 5px solid #10b981; box-shadow: 0 4px 10px rgba(0,0,0,0.03); margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .perfil-info h2 { margin: 0; color: #0f172a; font-size: 2rem; display: flex; align-items: center; gap: 15px; }
        .perfil-info p { color: #64748b; font-size: 1.1rem; margin: 10px 0 0 0; }
        
        .badge-puntos-gigante { background: #fef08a; color: #a16207; padding: 15px 25px; border-radius: 30px; font-weight: 900; font-size: 1.5rem; border: 2px solid #fde047; display: inline-flex; align-items: center; gap: 10px; }
        
        .panel-tabla { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .tabla-moderna { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabla-moderna th { background: #f8fafc; padding: 15px; text-align: left; font-size: 0.95rem; color: #475569; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; }
        .tabla-moderna td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; vertical-align: middle; color: #1e293b; }
        
        .btn-ver-boleta { background: #3b82f6; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.95rem; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-ver-boleta:hover { background: #2563eb; }
        
        .btn-volver { display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-weight: bold; font-size: 1.1rem; transition: 0.2s; margin-bottom: 20px; }
        .btn-volver:hover { color: #0f172a; }
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
            <li class="active"><a href="index.php?view=clientes"><i class="fa-solid fa-users"></i> Clientes</a></li>
            <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 'Administrador'): ?>
                <li><a href="index.php?view=productos"><i class="fa-solid fa-box-open"></i> Productos</a></li>
                <li><a href="index.php?view=kardex"><i class="fa-solid fa-clipboard-list"></i> Kardex / Auditoría</a></li>
                <li><a href="index.php?view=reportes"><i class="fa-solid fa-chart-pie"></i> Reportes / Finanzas</a></li>
                <li><a href="index.php?view=historial"><i class="fa-solid fa-file-invoice-dollar"></i> Historial</a></li>
            <?php endif; ?>
            <li style="margin-top: 40px;"><a href="index.php?action=logout" style="color: #fecaca;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            <div class="user-info">
                <span>Perfil del Cliente</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <a href="index.php?view=clientes" class="btn-volver"><i class="fa-solid fa-arrow-left"></i> Volver al Directorio</a>
            
            <div class="perfil-header">
                <div class="perfil-info">
                    <h2><i class="fa-solid fa-circle-user" style="color:#cbd5e1; font-size: 2.5rem;"></i> <?= htmlspecialchars($cliente['nombre']) ?></h2>
                    <p><i class="fa-solid fa-id-card"></i> DNI: <strong><?= htmlspecialchars($cliente['dni'] ?: 'No registrado') ?></strong> &nbsp; | &nbsp; <i class="fa-solid fa-phone"></i> Tel: <strong><?= htmlspecialchars($cliente['telefono'] ?: 'No registrado') ?></strong></p>
                </div>
                <div>
                    <div class="badge-puntos-gigante">
                        <i class="fa-solid fa-star"></i> <?= htmlspecialchars($cliente['puntos'] ?? 0) ?> Puntos
                    </div>
                </div>
            </div>

            <div class="panel-tabla">
                <h3 style="margin-top:0; font-size: 1.5rem; color:#0f172a; margin-bottom: 20px;"><i class="fa-solid fa-clock-rotate-left" style="color:#10b981;"></i> Historial de Compras Recientes</h3>
                
                <table class="tabla-moderna">
                    <thead>
                        <tr>
                            <th>Nº Ticket / Boleta</th>
                            <th>Fecha y Hora</th>
                            <th style="text-align: right;">Total Pagado</th>
                            <th style="text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($historialCompras)): ?>
                            <tr><td colspan="4" style="text-align:center; padding: 40px; color:#94a3b8; font-size: 1.1rem;">Este cliente aún no tiene compras registradas o se hicieron como Público General.</td></tr>
                        <?php else: ?>
                            <?php foreach($historialCompras as $venta): ?>
                            <tr>
                                <td style="font-weight:bold; color:#475569;"># <?= str_pad($venta['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td><?= date('d/m/Y - h:i A', strtotime($venta['fecha'])) ?></td>
                                <td style="text-align: right; font-weight: bold; font-size: 1.2rem; color: #16a34a;">$ <?= number_format($venta['total'], 2) ?></td>
                                <td style="text-align: center;">
                                    <a href="index.php?view=boleta&id=<?= $venta['id'] ?>" class="btn-ver-boleta" target="_blank">
                                        <i class="fa-solid fa-eye"></i> Ver Ticket
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
</body>
</html>