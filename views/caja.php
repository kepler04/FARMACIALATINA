<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Punto de Venta - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .pos-grid { display: grid; grid-template-columns: 1fr 500px; gap: 25px; margin-top: 20px; }
        .pos-panel { background: white; padding: 30px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        
        .panel-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 25px; }
        .panel-header h3 { margin: 0; color: #1e293b; font-size: 1.5rem; }
        
        .search-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 25px; border-radius: 8px; margin-bottom: 25px; }
        .form-control { font-size: 1.25rem; padding: 15px; height: 55px; } 
        
        .btn-add { background: #dc2626; color: white; border: none; padding: 20px; border-radius: 6px; font-size: 1.3rem; font-weight: bold; width: 100%; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 10px; }
        .btn-add:hover { background: #b91c1c; }

        .select2-container .select2-selection--single { height: 55px !important; border: 1px solid #ccc !important; border-radius: 6px !important; display: flex; align-items: center; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { font-size: 1.25rem; color: #333; line-height: 55px; padding-left: 15px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 55px !important; width: 40px !important; }
        .select2-dropdown { font-size: 1.2rem; }
        .select2-search__field { padding: 10px !important; font-size: 1.2rem !important; }

        .ticket-table { width: 100%; border-collapse: collapse; }
        .ticket-table th { background: #f8fafc; color: #64748b; padding: 15px; text-align: left; font-size: 1rem; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
        .ticket-table td { padding: 18px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #1e293b; font-weight: 500; font-size: 1.15rem; }
        
        .qty-controls { display: flex; align-items: center; gap: 12px; }
        .qty-btn { background: #e2e8f0; color: #475569; border: none; width: 35px; height: 35px; border-radius: 6px; font-weight: bold; font-size: 1.2rem; cursor: pointer; text-decoration: none; display: flex; justify-content: center; align-items: center; }
        .qty-btn:hover { background: #cbd5e1; color: #1e293b; }
        
        .btn-remove { color: #ef4444; font-size: 1.5rem; cursor: pointer; text-decoration: none; }
        .btn-remove:hover { color: #b91c1c; }

        .total-box { background: #1e293b; border-radius: 8px; padding: 30px; text-align: center; margin: 30px 0 20px 0; }
        .total-box h4 { color: #94a3b8; margin: 0 0 10px 0; font-size: 1.1rem; letter-spacing: 1px; text-transform: uppercase; }
        .total-box h2 { color: #22c55e; margin: 0; font-size: 4rem; font-weight: bold; line-height: 1; }

        .btn-green { background: #22c55e; color: white; border: none; padding: 20px; border-radius: 6px; font-size: 1.3rem; font-weight: bold; width: 100%; cursor: pointer; text-align: center; display: block; text-decoration: none; transition: 0.2s; }
        .btn-green:hover { background: #16a34a; }

        .checkout-panel { background: white; padding: 30px; border-radius: 8px; border: 1px solid #e5e7eb; border-top: 5px solid #22c55e; box-shadow: 0 4px 10px rgba(0,0,0,0.03); margin-top: 25px; }

        .btn-top-cerrar { background: #f59e0b; color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; text-decoration: none; font-size: 1.1rem; transition: 0.2s; box-shadow: 0 2px 5px rgba(245, 158, 11, 0.3); }
        .btn-top-cerrar:hover { background: #d97706; transform: translateY(-2px); }
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
            
            <li class="active"><a href="index.php?view=caja"><i class="fa-solid fa-cash-register"></i> Punto de Venta</a></li>
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
            
            <div class="user-info" style="display: flex; align-items: center; gap: 20px;">
                <a href="index.php?view=arqueo" class="btn-top-cerrar">
                    <i class="fa-solid fa-scale-balanced" style="margin-right: 8px;"></i> Cuadrar y Cerrar Caja
                </a>
                
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            
            <div class="pos-grid">
                
                <div class="pos-panel">
                    <div class="panel-header">
                        <h3><i class="fa-solid fa-barcode" style="color:#dc2626; margin-right: 8px;"></i> Buscar y Agregar Producto</h3>
                    </div>
                    
                    <form method="POST" action="index.php?action=agregar_al_carrito">
                        <div class="search-box">
                            <label style="font-weight:bold; color:#475569; margin-bottom:12px; display:block; font-size: 1.15rem;">Seleccionar Medicamento *</label>
                            <select name="id" id="buscador_productos" class="form-control" required>
                                <option value=""></option>
                                <?php foreach($listaProductos as $prod): ?>
                                    <option value="<?= $prod['id'] ?>">
                                        <?= htmlspecialchars($prod['descripcion']) ?> (Stock: <?= $prod['stock'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                            <div>
                                <label style="font-weight:bold; color:#475569; margin-bottom:10px; display:block; font-size:1.1rem;">Modo de Venta *</label>
                                <select name="tipo_venta" class="form-control" style="background: #f8fafc;" required>
                                    <option value="unidad">x1 Unidad (Suelto)</option>
                                    <option value="blister">x1 Blíster</option>
                                    <option value="caja">Caja Completa</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-weight:bold; color:#475569; margin-bottom:10px; display:block; font-size:1.1rem;">Cantidad *</label>
                                <input type="number" name="cantidad" class="form-control" value="1" min="1" style="background: #f8fafc; text-align:center;" required>
                            </div>
                            <div>
                                <label style="font-weight:bold; color:#475569; margin-bottom:10px; display:block; font-size:1.1rem;">Descuento ($)</label>
                                <input type="number" step="0.01" name="descuento" class="form-control" value="0.00" style="background: #f8fafc; text-align:center;">
                            </div>
                        </div>

                        <button type="submit" class="btn-add">
                            <i class="fa-solid fa-plus"></i> AGREGAR AL TICKET
                        </button>
                    </form>
                </div>

                <div class="pos-panel">
                    <div class="panel-header">
                        <h3><i class="fa-solid fa-receipt" style="color:#64748b; margin-right: 8px;"></i> Ticket de Venta</h3>
                        <?php if(!empty($_SESSION['carrito'])): ?>
                            <a href="index.php?action=vaciar_carrito" style="color:#ef4444; font-weight:bold; text-decoration:none; font-size:1.1rem;"><i class="fa-solid fa-trash"></i> Vaciar</a>
                        <?php endif; ?>
                    </div>

                    <table class="ticket-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th style="text-align:center;">Cant.</th>
                                <th style="text-align:right;">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_pagar = 0;
                            if(empty($_SESSION['carrito'])): 
                            ?>
                                <tr><td colspan="4" style="text-align:center; padding: 50px; color:#94a3b8; font-size:1.2rem;">Aún no hay productos en el ticket.</td></tr>
                            <?php 
                            else:
                                foreach($_SESSION['carrito'] as $index => $item): 
                                    $subtotal = ($item['precio_venta'] - $item['descuento']) * $item['cantidad'];
                                    $total_pagar += $subtotal;
                            ?>
                                <tr>
                                    <td>
                                        <div style="font-weight:bold; color:#1e293b; font-size:1.2rem;"><?= htmlspecialchars($item['descripcion']) ?></div>
                                        <div style="font-size:1rem; color:#64748b; margin-top:5px;">$ <?= number_format($item['precio_venta'] - $item['descuento'], 2) ?> c/u</div>
                                    </td>
                                    <td>
                                        <div class="qty-controls" style="justify-content: center;">
                                            <a href="index.php?action=restar_cantidad&index=<?= $index ?>" class="qty-btn">-</a>
                                            <span style="width: 25px; text-align: center; font-weight:bold; font-size:1.3rem;"><?= $item['cantidad'] ?></span>
                                            <a href="index.php?action=sumar_cantidad&index=<?= $index ?>" class="qty-btn">+</a>
                                        </div>
                                    </td>
                                    <td style="text-align:right; font-weight:bold; font-size:1.3rem; color:#0f172a;">$ <?= number_format($subtotal, 2) ?></td>
                                    <td style="text-align:center;">
                                        <a href="index.php?action=eliminar_item&index=<?= $index ?>" class="btn-remove"><i class="fa-solid fa-circle-xmark"></i></a>
                                    </td>
                                </tr>
                            <?php 
                                endforeach; 
                            endif; 
                            ?>
                        </tbody>
                    </table>

                    <div class="total-box">
                        <h4>Total a Cobrar</h4>
                        <h2>$ <?= number_format($total_pagar, 2) ?></h2>
                    </div>

                    <?php if(!empty($_SESSION['carrito'])): ?>
                        <a href="#panel-pago" class="btn-green"><i class="fa-solid fa-hand-holding-dollar"></i> CONTINUAR AL PAGO</a>
                    <?php else: ?>
                        <button class="btn-green" style="background:#cbd5e1; cursor:not-allowed;" disabled><i class="fa-solid fa-hand-holding-dollar"></i> CONTINUAR AL PAGO</button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if(!empty($_SESSION['carrito'])): ?>
            <div id="panel-pago" class="checkout-panel">
                <div class="panel-header" style="border:none; padding:0; margin-bottom:25px;">
                    <h3 style="font-size: 1.6rem;"><i class="fa-solid fa-file-invoice" style="color:#22c55e; margin-right:10px;"></i> Finalizar Venta y Generar Comprobante</h3>
                </div>
                
                <form method="POST" action="index.php?action=finalizar_venta">
                    
                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #e2e8f0; display: flex; gap: 30px;">
                        <label style="cursor: pointer; font-weight: bold; color: #0f172a; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="tipo_cliente" value="existente" checked onchange="toggleClienteForm(this.value)" style="transform: scale(1.3);"> 
                            <i class="fa-solid fa-address-book" style="color: #3b82f6;"></i> Cliente Registrado / Público General
                        </label>
                        <label style="cursor: pointer; font-weight: bold; color: #16a34a; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="tipo_cliente" value="nuevo" onchange="toggleClienteForm(this.value)" style="transform: scale(1.3);"> 
                            <i class="fa-solid fa-user-plus"></i> Registrar Nuevo Cliente (Ganar Puntos)
                        </label>
                    </div>

                    <div id="div_cliente_existente" style="margin-bottom: 25px;">
                        <label style="font-weight:bold; color:#475569; margin-bottom:10px; display:block; font-size:1.15rem;">Buscar Cliente en la Base de Datos</label>
                        <select name="id_cliente" id="buscador_clientes" class="form-control" style="width: 100%;">
                            <option value="0">🛒 PÚBLICO GENERAL (Compra normal sin puntos)</option>
                            <?php if(!empty($listaClientes)): foreach($listaClientes as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= htmlspecialchars($c['dni'] . ' - ' . $c['nombre']) ?> ⭐ (<?= $c['puntos'] ?? 0 ?> pts actuales)
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                        <p style="margin-top: 10px; color: #b45309; font-weight: bold; font-size: 0.95rem; background: #fffbeb; padding: 8px 12px; border-radius: 6px; display: inline-block; border: 1px solid #fde047;">
                            <i class="fa-solid fa-star"></i> Al seleccionar un cliente registrado, sumará <?= floor($total_pagar) ?> puntos automáticamente por esta compra.
                        </p>
                    </div>

                    <div id="div_cliente_nuevo" style="display: none; margin-bottom: 25px; background: #dcfce7; padding: 25px; border-radius: 8px; border: 2px dashed #22c55e;">
                        <h4 style="margin-top:0; color:#16a34a; font-size: 1.2rem; margin-bottom: 15px;"><i class="fa-solid fa-user-plus"></i> Datos del Nuevo Cliente</h4>
                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 2;">
                                <label style="font-weight:bold; color:#166534; margin-bottom:8px; display:block;">Nombre Completo *</label>
                                <input type="text" name="nuevo_nombre" id="input_nuevo_nombre" class="form-control" placeholder="Ej: Juan Pérez" style="border-color: #86efac;">
                            </div>
                            <div style="flex: 1;">
                                <label style="font-weight:bold; color:#166534; margin-bottom:8px; display:block;">DNI (Opcional)</label>
                                <input type="text" name="nuevo_dni" class="form-control" placeholder="Ej: 72485912" style="border-color: #86efac;">
                            </div>
                            <div style="flex: 1;">
                                <label style="font-weight:bold; color:#166534; margin-bottom:8px; display:block;">Celular (Opcional)</label>
                                <input type="text" name="nuevo_telefono" class="form-control" placeholder="Ej: 987654321" style="border-color: #86efac;">
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 20px; align-items: center;">
                        <a href="index.php?view=caja" style="color:#64748b; text-decoration:none; font-weight:bold; font-size:1.2rem; padding: 15px 25px;">Cancelar y Volver</a>
                        <button type="submit" style="padding: 20px 40px; background: #22c55e; color: white; border: none; border-radius: 8px; font-size: 1.3rem; font-weight: bold; cursor: pointer; transition: 0.2s;"><i class="fa-solid fa-check"></i> CONFIRMAR E IMPRIMIR BOLETA</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Buscador de Medicamentos
        $('#buscador_productos').select2({
            placeholder: "-- Escribe el nombre o código... --",
            allowClear: true,
            width: '100%',
            language: { noResults: function() { return "No se encontró el medicamento"; } }
        });

        // ✨ NUEVO: Buscador inteligente de Clientes
        $('#buscador_clientes').select2({
            width: '100%'
        });
    });

    // ✨ NUEVA FUNCIÓN: Intercambiar entre elegir cliente o crear uno nuevo
    function toggleClienteForm(valor) {
        if(valor === 'nuevo') {
            document.getElementById('div_cliente_existente').style.display = 'none';
            document.getElementById('div_cliente_nuevo').style.display = 'block';
            document.getElementById('input_nuevo_nombre').setAttribute('required', 'required');
        } else {
            document.getElementById('div_cliente_existente').style.display = 'block';
            document.getElementById('div_cliente_nuevo').style.display = 'none';
            document.getElementById('input_nuevo_nombre').removeAttribute('required');
        }
    }
</script>

</body>
</html>