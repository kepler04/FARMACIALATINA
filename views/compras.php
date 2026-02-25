<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entrada de Mercadería - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .layout-compras { display: grid; grid-template-columns: 450px 1fr; gap: 25px; margin-top: 20px; }
        .panel-entrada { background: white; padding: 30px; border-radius: 8px; border-top: 5px solid #0f172a; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .panel-lista { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; color: #475569; margin-bottom: 8px; font-size: 1.1rem; }
        .form-control { width: 100%; padding: 14px; font-size: 1.2rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; }
        
        .btn-add-list { background: #0f172a; color: white; border: none; padding: 15px; border-radius: 6px; font-weight: bold; width: 100%; cursor: pointer; font-size: 1.1rem; margin-top: 10px; }
        .btn-finalizar { background: #22c55e; color: white; border: none; padding: 20px; border-radius: 8px; font-size: 1.4rem; font-weight: bold; width: 100%; cursor: pointer; margin-top: 20px; transition: 0.2s; }
        .btn-finalizar:hover { background: #16a34a; transform: scale(1.01); }

        .tabla-ingreso { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabla-ingreso th { background: #f8fafc; padding: 15px; text-align: left; color: #64748b; text-transform: uppercase; font-size: 0.9rem; border-bottom: 2px solid #e2e8f0; }
        .tabla-ingreso td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; vertical-align: middle; }

        /* Select2 grande */
        .select2-container .select2-selection--single { height: 50px !important; display: flex; align-items: center; font-size: 1.1rem; }
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
                
                <li class="active"><a href="index.php?view=compras"><i class="fa-solid fa-truck-ramp-box"></i> Entradas</a></li>
                
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
                <span>Ingreso de Mercadería</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; margin-top:0; font-size: 2.2rem; margin-bottom: 25px;"><i class="fa-solid fa-truck-moving" style="color:#dc2626;"></i> Abastecimiento de Inventario</h2>
            
            <form action="index.php?action=procesar_compra" method="POST">
                <div class="layout-compras">
                    
                    <div class="panel-entrada">
                        <h3 style="margin-top:0; color:#1e293b; font-size: 1.3rem; border-bottom: 2px solid #f1f5f9; padding-bottom:10px;"><i class="fa-solid fa-file-invoice"></i> Datos del Documento</h3>
                        
                        <div class="form-group" style="margin-top:20px;">
                            <label>Proveedor</label>
                            <select name="id_proveedor" id="select_proveedor" class="form-control" required>
                                <option value="">-- Seleccionar Proveedor --</option>
                                <?php foreach($listaProveedores as $prov): ?>
                                    <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nº Factura / Guía</label>
                            <input type="text" name="factura" class="form-control" placeholder="Ej: F001-000452">
                        </div>

                        <h3 style="margin-top:40px; color:#1e293b; font-size: 1.3rem; border-bottom: 2px solid #f1f5f9; padding-bottom:10px;"><i class="fa-solid fa-magnifying-glass"></i> Buscar Producto</h3>
                        
                        <div class="form-group" style="margin-top:20px;">
                            <select id="buscador_productos" class="form-control">
                                <option value="">-- Buscar Medicamento --</option>
                                <?php foreach($listaProductos as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-nombre="<?= htmlspecialchars($p['descripcion']) ?>" data-costo="<?= $p['compra'] ?>">
                                        <?= htmlspecialchars($p['descripcion']) ?> (Stock: <?= $p['stock'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="button" id="btn_agregar_lista" class="btn-add-list"><i class="fa-solid fa-plus"></i> AGREGAR A LA LISTA</button>
                    </div>

                    <div class="panel-lista">
                        <h3 style="margin-top:0; color:#1e293b; font-size: 1.3rem;"><i class="fa-solid fa-list-check"></i> Productos para Ingresar</h3>
                        
                        <table class="tabla-ingreso" id="tabla_ingreso_stock">
                            <thead>
                                <tr>
                                    <th>Medicamento</th>
                                    <th style="width: 120px;">Cantidad</th>
                                    <th style="width: 150px;">Costo Unit.</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>

                        <button type="submit" class="btn-finalizar"><i class="fa-solid fa-check-double"></i> FINALIZAR Y SUBIR STOCK</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#select_proveedor').select2({ width: '100%' });
        $('#buscador_productos').select2({ width: '100%', placeholder: "Escribe el nombre..." });

        $('#btn_agregar_lista').click(function() {
            let prod = $('#buscador_productos').find(':selected');
            let id = prod.val();
            let nombre = prod.data('nombre');
            let costo = prod.data('costo');

            if(!id) return alert("Selecciona un producto primero");

            let fila = `
                <tr>
                    <td>
                        <input type="hidden" name="prod_ids[]" value="${id}">
                        <strong>${nombre}</strong>
                    </td>
                    <td>
                        <input type="number" name="cantidades[]" class="form-control" value="1" min="1" style="padding:8px; height:auto;">
                    </td>
                    <td>
                        <input type="number" step="0.01" name="costos[]" class="form-control" value="${costo}" style="padding:8px; height:auto;">
                    </td>
                    <td>
                        <button type="button" class="btn_quitar" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:1.5rem;"><i class="fa-solid fa-circle-xmark"></i></button>
                    </td>
                </tr>
            `;

            $('#tabla_ingreso_stock tbody').append(fila);
        });

        $(document).on('click', '.btn_quitar', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
</body>
</html>