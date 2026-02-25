<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Club de Clientes - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <style>
        /* ✨ LAYOUT MAXIMIZADO: Formulario más ancho ✨ */
        .layout-clientes { display: grid; grid-template-columns: 420px 1fr; gap: 30px; margin-top: 20px; }
        
        .panel-form { background: white; padding: 35px; border-radius: 8px; border-top: 5px solid #dc2626; box-shadow: 0 4px 10px rgba(0,0,0,0.03); height: fit-content; }
        .panel-tabla { background: white; padding: 35px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); overflow-x: auto; }
        
        /* ✨ CAMPOS DE TEXTO GIGANTES ✨ */
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-weight: bold; color: #475569; margin-bottom: 10px; font-size: 1.2rem; }
        .form-control { width: 100%; padding: 16px; font-size: 1.25rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; transition: 0.3s; }
        .form-control:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
        
        /* ✨ MEDALLA DE PUNTOS MÁS GRANDE ✨ */
        .badge-puntos { background: #fef08a; color: #a16207; padding: 8px 18px; border-radius: 30px; font-weight: 900; font-size: 1.25rem; border: 2px solid #fde047; display: inline-flex; align-items: center; gap: 8px; }
        .puntos-cero { background: #f1f5f9; color: #64748b; border-color: #e2e8f0; }
        
        /* ✨ TABLA MÁS ESPACIOSA Y LEGIBLE ✨ */
        .tabla-moderna { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabla-moderna th { background: #f8fafc; padding: 18px; text-align: left; font-size: 1.1rem; color: #475569; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; }
        .tabla-moderna td { padding: 18px; border-bottom: 1px solid #f1f5f9; font-size: 1.2rem; vertical-align: middle; }
        
        .btn-rojo { background: #dc2626; color: white; border: none; padding: 18px; border-radius: 6px; font-size: 1.3rem; font-weight: bold; width: 100%; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 10px; }
        .btn-rojo:hover { background: #b91c1c; }
        
        /* ✨ BOTONES DE ACCIÓN MÁS GRANDES ✨ */
        .btn-editar { color: #3b82f6; font-size: 1.6rem; text-decoration: none; transition: 0.2s; margin-right: 18px; }
        .btn-editar:hover { color: #2563eb; }
        
        .btn-borrar { color: #ef4444; font-size: 1.6rem; text-decoration: none; transition: 0.2s; }
        .btn-borrar:hover { color: #b91c1c; }
        .btn-historial { color: #10b981; font-size: 1.3rem; text-decoration: none; transition: 0.2s; margin-right: 15px; }
        .btn-historial:hover { color: #059669; }
        
        /* ✨ Ajustes para que el buscador de la tabla también sea grande ✨ */
        .dataTables_wrapper .dataTables_filter input { padding: 12px; border-radius: 6px; border: 1px solid #ccc; font-size: 1.15rem; margin-bottom: 20px; width: 300px; }
        .dataTables_wrapper .dataTables_length select { padding: 8px; font-size: 1.1rem; border-radius: 4px; }
        .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { font-size: 1.1rem; margin-top: 15px; }
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
                <span>Directorio de Clientes</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; margin-top:0; font-size: 2.2rem; margin-bottom: 25px;"><i class="fa-solid fa-users" style="color:#dc2626;"></i> Gestión de Clientes y Puntos</h2>
            
            <div class="layout-clientes">
                
                <div class="panel-form">
                    <h3 style="margin-top:0; font-size: 1.4rem; margin-bottom: 25px; color:#1e293b;"><i class="fa-solid fa-user-plus" style="color:#dc2626;"></i> Registrar Cliente</h3>
                    <form method="POST" action="index.php?action=guardar_cliente">
                        <div class="form-group">
                            <label>Nombre Completo *</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Juan Pérez" required>
                        </div>
                        <div class="form-group">
                            <label>DNI / Documento</label>
                            <input type="text" name="dni" class="form-control" placeholder="Ej: 72485912">
                        </div>
                        <div class="form-group">
                            <label>Teléfono / WhatsApp</label>
                            <input type="text" name="telefono" class="form-control" placeholder="Ej: 987654321">
                        </div>
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" name="direccion" class="form-control" placeholder="Ej: Av. Las Flores 123...">
                        </div>
                        <button type="submit" class="btn-rojo"><i class="fa-solid fa-floppy-disk"></i> Guardar Cliente</button>
                    </form>
                </div>

                <div class="panel-tabla">
                    <table class="tabla-moderna" id="tablaClientes">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>DNI</th>
                                <th>Teléfono</th>
                                <th style="text-align:center;">Pts. Acumulados</th>
                                <th style="text-align:center;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($listaClientes)): ?>
                                <?php foreach($listaClientes as $c): 
                                    $puntos = isset($c['puntos']) ? $c['puntos'] : 0;
                                    $clase_pts = ($puntos > 0) ? 'badge-puntos' : 'badge-puntos puntos-cero';
                                    $icono_pts = ($puntos > 0) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                                ?>
                                <tr>
                                    <td style="font-weight:bold; color:#0f172a;"><?= htmlspecialchars($c['nombre']) ?></td>
                                    <td style="color:#475569;"><?= htmlspecialchars($c['dni']) ?: '--' ?></td>
                                    <td style="color:#475569;"><?= htmlspecialchars($c['telefono']) ?: '--' ?></td>
                                    <td style="text-align:center;">
                                        <span class="<?= $clase_pts ?>">
                                            <?= $icono_pts ?> <?= $puntos ?> pts
                                        </span>
                                    </td>
                                   <td style="text-align:center;">
                                        <a href="index.php?view=historial_cliente&id=<?= $c['id'] ?>" class="btn-historial" title="Ver Historial de Compras"><i class="fa-solid fa-receipt"></i></a>
                                        <a href="index.php?view=editar_cliente&id=<?= $c['id'] ?>" class="btn-editar" title="Editar Cliente y Puntos"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="index.php?action=eliminar_cliente&id=<?= $c['id'] ?>" class="btn-borrar" onclick="return confirm('¿Seguro que deseas eliminar a este cliente y perder sus puntos?');" title="Eliminar Cliente"><i class="fa-solid fa-trash-can"></i></a>
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
        $('#tablaClientes').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "pageLength": 10,
            "ordering": false 
        });
    });
</script>
</body>
</html>