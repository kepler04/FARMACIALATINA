<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .panel-editar { background: white; padding: 40px; border-radius: 8px; border-top: 5px solid #3b82f6; box-shadow: 0 4px 10px rgba(0,0,0,0.05); max-width: 600px; margin: 30px auto; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; color: #475569; margin-bottom: 8px; font-size: 1.1rem; }
        .form-control { width: 100%; padding: 15px; font-size: 1.1rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; transition: 0.3s; }
        .form-control:focus { border-color: #3b82f6; }
        .btn-azul { background: #3b82f6; color: white; border: none; padding: 16px; border-radius: 6px; font-size: 1.2rem; font-weight: bold; width: 100%; cursor: pointer; transition: 0.2s; margin-top: 10px;}
        .btn-azul:hover { background: #2563eb; }
        .btn-cancelar { display: block; text-align: center; color: #64748b; margin-top: 20px; text-decoration: none; font-weight: bold; font-size: 1.1rem; }
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
                <span>Modificando Cliente</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <div class="panel-editar">
                <h3 style="margin-top:0; font-size: 1.8rem; margin-bottom: 25px; color:#1e293b; text-align: center;"><i class="fa-solid fa-user-pen" style="color:#3b82f6;"></i> Editar Datos de Cliente</h3>
                
                <form method="POST" action="index.php?action=actualizar_cliente">
                    <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                    
                    <div class="form-group">
                        <label>Nombre Completo *</label>
                        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
                    </div>
                    
                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>DNI / Documento</label>
                            <input type="text" name="dni" class="form-control" value="<?= htmlspecialchars($cliente['dni']) ?>">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($cliente['telefono']) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($cliente['direccion']) ?>">
                    </div>

                    <div class="form-group" style="background: #fffbeb; padding: 15px; border-radius: 8px; border: 1px solid #fde047;">
                        <label style="color: #b45309;"><i class="fa-solid fa-star"></i> Puntos Acumulados (Club)</label>
                        <input type="number" name="puntos" class="form-control" value="<?= htmlspecialchars($cliente['puntos'] ?? 0) ?>" style="font-weight: bold; color: #b45309;">
                    </div>

                    <button type="submit" class="btn-azul"><i class="fa-solid fa-floppy-disk"></i> Guardar Cambios</button>
                    <a href="index.php?view=clientes" class="btn-cancelar">Cancelar y Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>