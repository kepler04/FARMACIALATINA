<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-briefcase-medical"></i>
            <span>BOTICA LATINA</span>
        </div>
        
        <ul class="sidebar-menu">
            <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 'Administrador'): ?>
                <li class="active"><a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
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
                <li><a href="index.php?view=historial"><i class="fa-solid fa-file-invoice-dollar"></i> Historial</a></li>
                <li><a href="index.php?view=usuarios"><i class="fa-solid fa-user-shield"></i> Usuarios</a></li>
            <?php endif; ?>
            
            <li style="margin-top: 40px;"><a href="index.php?action=logout" style="color: #fecaca;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            
            <div class="user-info" style="display: flex; align-items: center;">
                <div style="font-weight: 800; color: #dc2626; font-size: 1.3rem; margin-right: 25px; display: flex; align-items: center; gap: 10px; background: #fff1f2; padding: 8px 15px; border-radius: 8px; border: 1px solid #fecaca;">
                    <i class="fa-regular fa-clock"></i> 
                    <span id="reloj-vivo">00:00:00</span>
                </div>

                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px;">Hola, <?php echo isset($_SESSION['user_nombre']) ? htmlspecialchars($_SESSION['user_nombre']) : 'Administrador'; ?></span>
                <i class="fa-solid fa-circle-user" style="font-size: 1.8rem; color:#dc2626; margin-left:15px;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <div class="cards-grid">
                <div class="card-widget">
                    <div class="card-info">
                        <h3><?php echo isset($total_clientes) ? $total_clientes : 0; ?></h3>
                        <p>Clientes Registrados</p>
                    </div>
                    <i class="fa-solid fa-users card-icon"></i>
                </div>

                <div class="card-widget">
                    <div class="card-info">
                        <h3><?php echo isset($total_proveedores) ? $total_proveedores : 0; ?></h3>
                        <p>Proveedores</p>
                    </div>
                    <i class="fa-solid fa-truck-field card-icon"></i>
                </div>

                <div class="card-widget">
                    <div class="card-info">
                        <h3><?php echo isset($total_productos) ? $total_productos : 0; ?></h3>
                        <p>Total Productos</p>
                    </div>
                    <i class="fa-solid fa-pills card-icon"></i>
                </div>

                <div class="card-widget">
                    <div class="card-info">
                        <h3><?php echo isset($ventas_hoy) ? $ventas_hoy : 0; ?></h3>
                        <p>Tickets Hoy</p>
                    </div>
                    <i class="fa-solid fa-receipt card-icon"></i>
                </div>

                <a href="index.php?view=vencimientos" style="text-decoration: none; color: inherit;">
                    <div class="card-widget" style="border: 2px solid #dc2626; cursor: pointer;">
                        <div class="card-info">
                            <h3 style="color:#dc2626;"><?php echo isset($alertas_vencimiento) ? $alertas_vencimiento : 0; ?></h3>
                            <p style="font-weight: bold; color: #dc2626;">Productos por Vencer</p>
                        </div>
                        <i class="fa-solid fa-siren-on card-icon" style="color:#dc2626;"></i>
                    </div>
                </a>

                <div class="card-widget">
                    <div class="card-info">
                        <h3><?php echo isset($total_usuarios) ? $total_usuarios : 0; ?></h3>
                        <p>Usuarios del Sistema</p>
                    </div>
                    <i class="fa-solid fa-user-tie card-icon"></i>
                </div>
            </div>

            <a href="index.php?view=reportes" style="text-decoration: none; color: inherit;">
                <div class="card-total" style="cursor: pointer;">
                    <div class="card-info">
                        <h3>$ <?php echo number_format(isset($ingresos_mes) ? $ingresos_mes : 0, 2); ?></h3>
                        <p>Ingresos Generados del Mes (Clic para ver Utilidad Neta)</p>
                    </div>
                    <i class="fa-solid fa-chart-line card-icon"></i>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    function actualizarReloj() {
        const ahora = new Date();
        const h = ahora.getHours().toString().padStart(2, '0');
        const m = ahora.getMinutes().toString().padStart(2, '0');
        const s = ahora.getSeconds().toString().padStart(2, '0');
        
        const formatoHora = h + ":" + m + ":" + s;
        const display = document.getElementById('reloj-vivo');
        if(display) display.innerText = formatoHora;
    }
    
    // Iniciar el segundero
    setInterval(actualizarReloj, 1000);
    actualizarReloj();
</script>

</body>
</html>