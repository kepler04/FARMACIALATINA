<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Proveedores - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <style>
        /* LAYOUT MAXIMIZADO: 2 Columnas */
        .layout-proveedores { display: grid; grid-template-columns: 420px 1fr; gap: 30px; margin-top: 20px; }
        
        .panel-form { background: white; padding: 35px; border-radius: 8px; border-top: 5px solid #dc2626; box-shadow: 0 4px 10px rgba(0,0,0,0.03); height: fit-content; }
        .panel-tabla { background: white; padding: 35px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); overflow-x: auto; }
        
        /* CAMPOS DE TEXTO GIGANTES */
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-weight: bold; color: #475569; margin-bottom: 10px; font-size: 1.2rem; }
        .form-control { width: 100%; padding: 16px; font-size: 1.25rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; transition: 0.3s; }
        .form-control:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
        
        /* TABLA MODERNA */
        .tabla-moderna { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabla-moderna th { background: #f8fafc; padding: 18px; text-align: left; font-size: 1.1rem; color: #475569; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; }
        .tabla-moderna td { padding: 18px; border-bottom: 1px solid #f1f5f9; font-size: 1.2rem; vertical-align: middle; color: #1e293b; }
        
        .btn-rojo { background: #dc2626; color: white; border: none; padding: 18px; border-radius: 6px; font-size: 1.3rem; font-weight: bold; width: 100%; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 10px; }
        .btn-rojo:hover { background: #b91c1c; transform: scale(1.02); }
        
        /* BOTONES DE ACCIÓN */
        .btn-borrar { color: #ef4444; font-size: 1.6rem; text-decoration: none; transition: 0.2s; }
        .btn-borrar:hover { color: #b91c1c; }

        .dataTables_wrapper .dataTables_filter input { padding: 12px; border-radius: 6px; border: 1px solid #ccc; font-size: 1.15rem; margin-bottom: 20px; width: 250px; }
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
                <li><a href="index.php?view=reportes"><i class="fa-solid fa-chart-pie"></i> Reportes / Finanzas</a></li>
                
                <li class="active"><a href="index.php?view=proveedores"><i class="fa-solid fa-truck-field"></i> Proveedores</a></li>
                
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
                <span>Directorio de Proveedores</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; margin-top:0; font-size: 2.2rem; margin-bottom: 25px;"><i class="fa-solid fa-truck" style="color:#dc2626;"></i> Gestión de Proveedores / Aliados</h2>
            
            <div class="layout-proveedores">
                
                <div class="panel-form">
                    <h3 style="margin-top:0; font-size: 1.4rem; margin-bottom: 25px; color:#1e293b;"><i class="fa-solid fa-building-circle-check" style="color:#dc2626;"></i> Registrar Empresa</h3>
                    <form method="POST" action="index.php?action=guardar_proveedor">
                        <div class="form-group">
                            <label>Razón Social / Empresa *</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Laboratorios Roche" required>
                        </div>
                        <div class="form-group">
                            <label>RUC / NIT</label>
                            <input type="text" name="ruc" class="form-control" placeholder="Ej: 20123456789">
                        </div>
                        <div class="form-group">
                            <label>Teléfono de Contacto</label>
                            <input type="text" name="telefono" class="form-control" placeholder="Ej: 987654321">
                        </div>
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" name="direccion" class="form-control" placeholder="Ej: Av. Industrial 456...">
                        </div>
                        <button type="submit" class="btn-rojo"><i class="fa-solid fa-floppy-disk"></i> Guardar Proveedor</button>
                    </form>
                </div>

                <div class="panel-tabla">
                    <table class="tabla-moderna" id="tablaProveedores">
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>RUC / NIT</th>
                                <th>Teléfono</th>
                                <th style="text-align:center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($listaProveedores)): ?>
                                <?php foreach($listaProveedores as $p): ?>
                                <tr>
                                    <td style="font-weight:bold; color:#0f172a;"><?= htmlspecialchars($p['nombre']) ?></td>
                                    <td style="color:#475569; font-family: monospace; font-size: 1.1rem;"><?= htmlspecialchars($p['ruc']) ?: '--' ?></td>
                                    <td style="color:#475569;"><?= htmlspecialchars($p['telefono']) ?: '--' ?></td>
                                    <td style="text-align:center;">
                                        <a href="index.php?action=eliminar_proveedor&id=<?= $p['id'] ?>" class="btn-borrar" onclick="return confirm('¿Seguro que deseas eliminar este proveedor?');" title="Eliminar Proveedor"><i class="fa-solid fa-trash-can"></i></a>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaProveedores').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "pageLength": 10
        });
    });
</script>
</body>
</html>