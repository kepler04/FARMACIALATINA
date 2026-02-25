<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salidas y Mermas - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .layout-salidas { display: grid; grid-template-columns: 450px 1fr; gap: 30px; margin-top: 20px; }
        
        .panel-registro { background: white; padding: 35px; border-radius: 8px; border-top: 5px solid #dc2626; box-shadow: 0 4px 10px rgba(0,0,0,0.03); height: fit-content; }
        .panel-historial { background: white; padding: 35px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-weight: bold; color: #475569; margin-bottom: 10px; font-size: 1.15rem; }
        .form-control { width: 100%; padding: 15px; font-size: 1.2rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; }
        
        .btn-procesar { background: #dc2626; color: white; border: none; padding: 20px; border-radius: 8px; font-size: 1.4rem; font-weight: bold; width: 100%; cursor: pointer; transition: 0.2s; margin-top: 10px; }
        .btn-procesar:hover { background: #b91c1c; transform: scale(1.01); }

        .tabla-salidas { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabla-salidas th { background: #f8fafc; padding: 18px; text-align: left; color: #64748b; text-transform: uppercase; font-size: 1rem; border-bottom: 2px solid #e2e8f0; }
        .tabla-salidas td { padding: 18px; border-bottom: 1px solid #f1f5f9; font-size: 1.15rem; vertical-align: middle; color: #1e293b; }

        .badge-motivo { padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 0.95rem; }
        .motivo-vencido { background: #fee2e2; color: #dc2626; }
        .motivo-danado { background: #fef3c7; color: #d97706; }
        
        /* Select2 grande */
        .select2-container .select2-selection--single { height: 55px !important; display: flex; align-items: center; font-size: 1.2rem; border: 1px solid #cbd5e1 !important; }
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
                <li><a href="index.php?view=proveedores"><i class="fa-solid fa-truck-field"></i> Proveedores</a></li>
                <li><a href="index.php?view=compras"><i class="fa-solid fa-truck-ramp-box"></i> Entradas</a></li>
                
                <li class="active"><a href="index.php?view=salidas"><i class="fa-solid fa-right-from-bracket"></i> Salidas / Mermas</a></li>
                
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
                <span>Control de Mermas y Ajustes</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; margin-top:0; font-size: 2.2rem; margin-bottom: 25px;"><i class="fa-solid fa-box-archive" style="color:#dc2626;"></i> Gestión de Salidas de Inventario</h2>
            
            <div class="layout-salidas">
                
                <div class="panel-registro">
                    <h3 style="margin-top:0; color:#1e293b; font-size: 1.4rem; margin-bottom: 25px;"><i class="fa-solid fa-pen-to-square"></i> Registrar Nueva Salida</h3>
                    
                    <form action="index.php?action=procesar_salida" method="POST">
                        <div class="form-group">
                            <label>Producto a retirar:</label>
                            <select name="id_producto" id="select_producto_salida" class="form-control" required>
                                <option value="">-- Seleccionar Medicamento --</option>
                                <?php foreach($listaProductos as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['descripcion']) ?> (Stock: <?= $p['stock'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Cantidad a retirar:</label>
                            <input type="number" name="cantidad" class="form-control" placeholder="Ej: 5" min="1" required>
                        </div>

                        <div class="form-group">
                            <label>Motivo de la salida:</label>
                            <select name="motivo" class="form-control" required>
                                <option value="Medicamento Vencido">Medicamento Vencido</option>
                                <option value="Producto Dañado/Roto">Producto Dañado / Roto</option>
                                <option value="Error de Inventario">Ajuste por Error de Inventario</option>
                                <option value="Donación / Muestra">Donación / Muestra Gratis</option>
                                <option value="Uso Interno">Consumo / Uso Interno</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Detalles adicionales (Opcional):</label>
                            <textarea name="detalles" class="form-control" rows="3" placeholder="Ej: Lote dañado en transporte..."></textarea>
                        </div>

                        <button type="submit" class="btn-procesar"><i class="fa-solid fa-check"></i> PROCESAR SALIDA</button>
                    </form>
                </div>

                <div class="panel-historial">
                    <h3 style="margin-top:0; color:#1e293b; font-size: 1.4rem;"><i class="fa-solid fa-clock-rotate-left"></i> Historial de Salidas (Últimas 50)</h3>
                    
                    <table class="tabla-salidas">
                        <thead>
                            <tr>
                                <th>Fecha / Hora</th>
                                <th>Producto</th>
                                <th style="text-align:center;">Cant.</th>
                                <th>Motivo / Descripción</th>
                                <th style="text-align:center;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($historialMermas)): ?>
                                <?php foreach($historialMermas as $h): ?>
                                <tr>
                                    <td style="font-size: 1rem; color:#64748b;"><?= date('d/m/Y H:i', strtotime($h['fecha'])) ?></td>
                                    <td style="font-weight:bold;"><?= htmlspecialchars($h['producto_nombre'] ?? 'Desconocido') ?></td>
                                    <td style="text-align:center; font-weight:900; color:#dc2626;">- <?= $h['cantidad'] ?></td>
                                    <td>
                                        <span class="badge-motivo motivo-vencido"><?= htmlspecialchars($h['descripcion']) ?></span>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="index.php?action=eliminar_salida&id=<?= $h['id'] ?>" onclick="return confirm('¿Anular esta salida y devolver el stock?');" style="color:#ef4444; font-size:1.4rem;"><i class="fa-solid fa-trash-can"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align:center; padding: 50px; color:#94a3b8;">No hay registros de salidas manuales.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#select_producto_salida').select2({
            width: '100%',
            placeholder: "Buscar medicamento..."
        });
    });
</script>
</body>
</html>