<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Medicamento - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn-back { display: inline-block; padding: 10px 20px; background: #f1f5f9; color: #475569; text-decoration: none; border-radius: 8px; margin-bottom: 25px; font-weight: bold; border: 1px solid #cbd5e1; transition: 0.2s; }
        .btn-back:hover { background: #e2e8f0; color: #0f172a; }
        .panel-blanco { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
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
                <li class="active"><a href="index.php?view=productos"><i class="fa-solid fa-box-open"></i> Productos</a></li>
                <li><a href="index.php?view=vencimientos"><i class="fa-solid fa-triangle-exclamation"></i> Vencimientos</a></li>
                <li><a href="index.php?view=proveedores"><i class="fa-solid fa-truck-field"></i> Proveedores</a></li>
                <li><a href="index.php?view=compras"><i class="fa-solid fa-truck-ramp-box"></i> Entradas</a></li>
                <li><a href="index.php?view=historial"><i class="fa-solid fa-file-invoice-dollar"></i> Historial</a></li>
                <li><a href="index.php?view=usuarios"><i class="fa-solid fa-user-shield"></i> Usuarios</a></li>
            <?php endif; ?>
            <li style="margin-top: 40px;"><a href="index.php?action=logout" style="color: #fecaca;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            <div class="user-info"><span>Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span><i class="fa-solid fa-circle-user" style="color:#dc2626;"></i></div>
        </header>

        <div class="dashboard-container">
            <a href="index.php?view=productos" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Cancelar y Volver</a>
            
            <h2 style="color: #0f172a; margin-top: 0; margin-bottom: 25px;"><i class="fa-solid fa-pen-to-square" style="color:#3b82f6;"></i> Editar Medicamento</h2>

            <form method="POST" action="index.php?action=actualizar_producto">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
                
                <div class="split-layout" style="align-items: flex-start; gap: 25px;">
                    
                    <div class="left-panel panel-blanco" style="flex: 1;">
                        <h3 style="margin-top:0; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px;"><i class="fa-solid fa-notes-medical" style="color:#64748b;"></i> Datos Médicos y Control</h3>
                        
                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Código de Barras *</label>
                                <input type="text" name="codigo" class="form-control" value="<?php echo htmlspecialchars($producto['codigo'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label>Nombre Comercial *</label>
                                <input type="text" name="descripcion" class="form-control" value="<?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 2;">
                                <label>Principio Activo (Genérico)</label>
                                <input type="text" name="principio_activo" class="form-control" value="<?php echo htmlspecialchars($producto['principio_activo'] ?? ''); ?>">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label>Categoría</label>
                                <select name="categoria" class="form-control">
                                    <option value="Medicamentos" <?php echo ($producto['categoria'] == 'Medicamentos') ? 'selected' : ''; ?>>Medicamentos</option>
                                    <option value="Inyectables" <?php echo ($producto['categoria'] == 'Inyectables') ? 'selected' : ''; ?>>Inyectables</option>
                                    <option value="Cuidado Personal" <?php echo ($producto['categoria'] == 'Cuidado Personal') ? 'selected' : ''; ?>>Cuidado Personal</option>
                                    <option value="<?php echo htmlspecialchars($producto['categoria']); ?>" selected><?php echo htmlspecialchars($producto['categoria']); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Descripción / Detalles</label>
                            <textarea name="detalles" class="form-control" rows="2"><?php echo htmlspecialchars($producto['detalles'] ?? ''); ?></textarea>
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Lote</label>
                                <input type="text" name="lote" class="form-control" value="<?php echo htmlspecialchars($producto['lote'] ?? ''); ?>">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label>Fecha Vencimiento *</label>
                                <input type="date" name="fecha_vencimiento" class="form-control" value="<?php echo htmlspecialchars($producto['fecha_vencimiento'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="form-group" style="background: #fef2f2; padding: 15px; border-radius: 8px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 10px; margin-bottom: 0;">
                            <input type="checkbox" name="requiere_receta" id="receta" value="1" <?php echo (!empty($producto['requiere_receta'])) ? 'checked' : ''; ?> style="width: 20px; height: 20px;">
                            <label for="receta" style="margin:0; color: #dc2626; font-weight: bold; cursor: pointer;"><i class="fa-solid fa-triangle-exclamation"></i> Este medicamento exige Receta Médica</label>
                        </div>
                    </div>

                    <div class="right-panel" style="flex: 1; display: flex; flex-direction: column; gap: 25px;">
                        
                        <div class="panel-blanco">
                            <h3 style="margin-top:0; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px;"><i class="fa-solid fa-sack-dollar" style="color:#64748b;"></i> Fracciones y Precios</h3>
                            
                            <div style="border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; margin-bottom: 15px; background: #f8fafc;">
                                <h4 style="margin-top:0; color:#0f172a;"><i class="fa-solid fa-box" style="color:#d97706;"></i> Venta por CAJA completa</h4>
                                <div style="display: flex; gap: 15px;">
                                    <div class="form-group" style="flex: 1; margin-bottom: 0;"><label>Unidades que trae *</label><input type="number" name="unidades_por_caja" class="form-control" value="<?php echo htmlspecialchars($producto['unidades_por_caja'] ?? '1'); ?>" required></div>
                                    <div class="form-group" style="flex: 1; margin-bottom: 0;"><label>Precio Caja ($) *</label><input type="number" step="0.01" name="precio_caja" class="form-control" value="<?php echo htmlspecialchars($producto['precio_caja'] ?? '0.00'); ?>" required></div>
                                </div>
                            </div>

                            <div style="border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                                <h4 style="margin-top:0; color:#0f172a;"><i class="fa-solid fa-capsules" style="color:#dc2626;"></i> Venta por BLÍSTER</h4>
                                <div style="display: flex; gap: 15px;">
                                    <div class="form-group" style="flex: 1; margin-bottom: 0;"><label>Unidades que trae</label><input type="number" name="unidades_por_blister" class="form-control" value="<?php echo htmlspecialchars($producto['unidades_por_blister'] ?? '1'); ?>"></div>
                                    <div class="form-group" style="flex: 1; margin-bottom: 0;"><label>Precio Blíster ($)</label><input type="number" step="0.01" name="precio_blister" class="form-control" value="<?php echo htmlspecialchars($producto['precio_blister'] ?? '0.00'); ?>"></div>
                                </div>
                            </div>

                            <div style="border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px;">
                                <h4 style="margin-top:0; color:#0f172a;"><i class="fa-solid fa-pills" style="color:#dc2626;"></i> Venta por UNIDAD (Pastilla suelta)</h4>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Precio Unidad ($) *</label>
                                    <input type="number" step="0.01" name="precio_unidad" class="form-control" value="<?php echo htmlspecialchars($producto['precio_unidad'] ?? '0.00'); ?>" required>
                                </div>
                            </div>
                        </div>