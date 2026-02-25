<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <style>
        .panel-form { background: white; padding: 30px; border-radius: 8px; border-top: 5px solid #dc2626; box-shadow: 0 4px 10px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .panel-tabla { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); overflow-x: auto; }
        
        .seccion-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px; margin-bottom: 25px; display: block; width: 100%; }
        .seccion-amarilla { background: #fffbeb; border-color: #fde047; }
        
        .seccion-titulo { font-size: 1.3rem; color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-top: 0; margin-bottom: 20px; font-weight: bold; }
        .titulo-amarillo { color: #b45309; border-bottom-color: #fef08a; }

        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; color: #475569; margin-bottom: 8px; font-size: 1.05rem; }
        .form-control { width: 100%; padding: 12px; font-size: 1.1rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; transition: 0.3s; background: white; }
        .form-control:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
        
        .caja-precio { background: white; border: 1px solid #fde047; padding: 15px; border-radius: 8px; }
        .caja-precio label { color: #b45309; font-size: 1.1rem; }
        
        .checkbox-gigante { transform: scale(1.5); margin-right: 12px; cursor: pointer; }
        
        .btn-rojo { background: #dc2626; color: white; border: none; padding: 18px; border-radius: 8px; font-size: 1.3rem; font-weight: bold; width: 100%; cursor: pointer; transition: 0.2s; display: block; text-align: center; margin-top: 10px; }
        .btn-rojo:hover { background: #b91c1c; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2); }
        
        /* Tabla */
        .tabla-moderna { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabla-moderna th { background: #f8fafc; padding: 15px; text-align: left; font-size: 0.95rem; color: #475569; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; }
        .tabla-moderna td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 1.05rem; vertical-align: middle; }
        
        .badge-stock { padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 1rem; text-align: center; display: inline-block; min-width: 60px;}
        .stock-ok { background: #dcfce7; color: #16a34a; }
        .stock-bajo { background: #fee2e2; color: #dc2626; }
        
        .btn-editar { color: #3b82f6; font-size: 1.3rem; text-decoration: none; transition: 0.2s; margin-right: 15px; }
        .btn-borrar { color: #ef4444; font-size: 1.3rem; text-decoration: none; transition: 0.2s; }
        
        .dataTables_wrapper .dataTables_filter input { padding: 8px; border-radius: 6px; border: 1px solid #ccc; font-size: 1.1rem; margin-bottom: 15px; width: 250px; }

        /* ✨ ESTILOS DEL MODAL DE CATEGORÍAS ✨ */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); display: none; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); }
        .modal-content { background: white; padding: 30px; border-radius: 12px; width: 450px; max-width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; }
        .close-btn { background: #f1f5f9; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
        .close-btn:hover { background: #e2e8f0; color: #dc2626; }
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
                <span>Gestión de Inventario Farmacéutico</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; margin-top:0; font-size: 2.2rem; margin-bottom: 25px;"><i class="fa-solid fa-pills" style="color:#dc2626;"></i> Catálogo de Medicamentos</h2>
            
            <div class="panel-form">
                <form method="POST" action="index.php?action=guardar_producto">
                    
                    <div class="seccion-card">
                        <h4 class="seccion-titulo"><i class="fa-solid fa-notes-medical" style="color:#dc2626;"></i> 1. Datos del Medicamento</h4>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Código de Barras *</label>
                                <input type="text" name="codigo" class="form-control" placeholder="Ej: 750123456" required>
                            </div>
                            <div class="form-group">
                                <label>Nombre Comercial *</label>
                                <input type="text" name="descripcion" class="form-control" placeholder="Ej: Panadol Antigripal" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Categoría</label>
                                <div style="display: flex; gap: 10px;">
                                    <select name="categoria" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <?php if(!empty($listaCategorias)): foreach($listaCategorias as $cat): ?>
                                            <option value="<?= htmlspecialchars($cat['nombre']) ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <button type="button" onclick="document.getElementById('modalCategorias').style.display='flex'" style="background:#3b82f6; color:white; border:none; border-radius:6px; padding: 0 15px; cursor:pointer; font-size:1.2rem; transition:0.2s;" title="Gestionar Categorías">
                                        <i class="fa-solid fa-gear"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                        <div class="grid-3" style="margin-top: 10px;">
                            <div class="form-group">
                                <label>Principio Activo (Genérico)</label>
                                <input type="text" name="principio_activo" class="form-control" placeholder="Ej: Paracetamol 500mg">
                            </div>
                            <div class="form-group">
                                <label>Lote</label>
                                <input type="text" name="lote" class="form-control" placeholder="Ej: L-2024A">
                            </div>
                            <div class="form-group">
                                <label>Fecha de Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" class="form-control">
                            </div>
                        </div>
                        
                        <div style="margin-top: 10px; padding-top: 15px; border-top: 1px dashed #cbd5e1;">
                            <label style="display: flex; align-items: center; color: #dc2626; cursor: pointer; font-weight: bold; font-size: 1.1rem;">
                                <input type="checkbox" name="requiere_receta" value="1" class="checkbox-gigante"> 
                                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> Este medicamento exige Receta Médica
                            </label>
                        </div>
                    </div>

                    <div class="seccion-card seccion-amarilla">
                        <h4 class="seccion-titulo titulo-amarillo"><i class="fa-solid fa-tags"></i> 2. Fracciones y Precios de Venta</h4>
                        <div class="grid-3">
                            <div class="caja-precio">
                                <label><i class="fa-solid fa-box"></i> Venta por CAJA</label>
                                <div class="grid-2" style="margin-top: 10px; gap: 10px;">
                                    <input type="number" name="unidades_por_caja" class="form-control" placeholder="Cant. Unds" value="1">
                                    <input type="number" step="0.01" name="precio_caja" class="form-control" placeholder="Precio ($)">
                                </div>
                            </div>
                            <div class="caja-precio">
                                <label><i class="fa-solid fa-tablets"></i> Venta por BLÍSTER</label>
                                <div class="grid-2" style="margin-top: 10px; gap: 10px;">
                                    <input type="number" name="unidades_por_blister" class="form-control" placeholder="Cant. Unds" value="1">
                                    <input type="number" step="0.01" name="precio_blister" class="form-control" placeholder="Precio ($)">
                                </div>
                            </div>
                            <div class="caja-precio">
                                <label><i class="fa-solid fa-capsules"></i> Venta por UNIDAD</label>
                                <div style="margin-top: 10px;">
                                    <input type="number" step="0.01" name="precio_unidad" class="form-control" placeholder="Precio pastilla ($)" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="seccion-card">
                        <h4 class="seccion-titulo"><i class="fa-solid fa-boxes-stacked" style="color:#3b82f6;"></i> 3. Inventario Inicial</h4>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Stock Físico (Unidades Totales) *</label>
                                <input type="number" name="stock" class="form-control" value="0" required>
                            </div>
                            <div class="form-group">
                                <label>Alerta de Stock Mínimo</label>
                                <input type="number" name="stock_minimo" class="form-control" value="20">
                            </div>
                            <div class="form-group">
                                <label>Costo de Compra ($)</label>
                                <input type="number" step="0.01" name="compra" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-rojo"><i class="fa-solid fa-floppy-disk"></i> Guardar Medicamento en el Catálogo</button>
                </form>
            </div>

            <div class="panel-tabla">
                <table class="tabla-moderna" id="tablaProductos">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Medicamento / Producto</th>
                            <th>Vencimiento</th>
                            <th style="text-align:center;">Stock</th>
                            <th>$ Caja</th>
                            <th>$ Blíster</th>
                            <th>$ Unidad</th>
                            <th style="text-align:center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($listaProductos)): ?>
                            <?php foreach($listaProductos as $p): 
                                $es_bajo = ($p['stock'] <= $p['stock_minimo']);
                                $vence = (!empty($p['fecha_vencimiento']) && $p['fecha_vencimiento'] != '0000-00-00') ? date('d/m/Y', strtotime($p['fecha_vencimiento'])) : '--';
                            ?>
                            <tr>
                                <td style="color:#64748b; font-size: 0.95rem;"><?= htmlspecialchars($p['codigo']) ?></td>
                                <td>
                                    <strong style="color:#0f172a; display:block;"><?= htmlspecialchars($p['descripcion']) ?></strong>
                                    <span style="font-size: 0.85rem; color:#64748b;"><?= htmlspecialchars($p['principio_activo'] ?? '') ?></span>
                                </td>
                                <td style="color:#475569;"><?= $vence ?></td>
                                <td style="text-align:center;">
                                    <span class="badge-stock <?= $es_bajo ? 'stock-bajo' : 'stock-ok' ?>">
                                        <?= $p['stock'] ?>
                                    </span>
                                </td>
                                <td style="font-weight:bold; color:#1e293b;">$ <?= number_format($p['precio_caja'] ?? 0, 2) ?></td>
                                <td style="font-weight:bold; color:#1e293b;">$ <?= number_format($p['precio_blister'] ?? 0, 2) ?></td>
                                <td style="font-weight:bold; color:#16a34a;">$ <?= number_format($p['precio_unidad'] ?? 0, 2) ?></td>
                                <td style="text-align:center;">
                                    <a href="index.php?view=editar_producto&id=<?= $p['id'] ?>" class="btn-editar" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="index.php?action=eliminar_producto&id=<?= $p['id'] ?>" class="btn-borrar" onclick="return confirm('¿Seguro que deseas eliminar este producto?');" title="Eliminar"><i class="fa-solid fa-trash-can"></i></a>
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

<div id="modalCategorias" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin:0; color:#0f172a; font-size: 1.5rem;"><i class="fa-solid fa-tags" style="color:#3b82f6;"></i> Gestionar Categorías</h3>
            <button class="close-btn" onclick="document.getElementById('modalCategorias').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <form action="index.php?action=agregar_categoria" method="POST" style="display:flex; gap:10px; margin-bottom:20px;">
            <input type="text" name="nombre_categoria" class="form-control" placeholder="Ej: Helados, Jarabes..." required>
            <button type="submit" style="background:#22c55e; color:white; border:none; border-radius:6px; padding:0 20px; cursor:pointer; font-weight:bold; font-size:1.1rem;"><i class="fa-solid fa-plus"></i> Añadir</button>
        </form>
        
        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 6px;">
            <table style="width:100%; border-collapse: collapse;">
                <?php if(!empty($listaCategorias)): foreach($listaCategorias as $cat): ?>
                <tr>
                    <td style="padding:15px; border-bottom:1px solid #f1f5f9; font-weight:bold; color:#475569; font-size: 1.1rem;"><?= htmlspecialchars($cat['nombre']) ?></td>
                    <td style="padding:15px; border-bottom:1px solid #f1f5f9; text-align:right;">
                        <a href="index.php?action=eliminar_categoria&id=<?= $cat['id'] ?>" onclick="return confirm('¿Borrar esta categoría permanentemente?');" style="color:#ef4444; font-size:1.3rem;"><i class="fa-solid fa-trash-can"></i></a>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaProductos').DataTable({
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