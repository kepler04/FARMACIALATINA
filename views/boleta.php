<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta #<?php echo $venta['id']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Courier New', Courier, monospace; background-color: #f1f5f9; display: flex; justify-content: center; padding: 40px 20px; }
        
        .ticket-box {
            background-color: #fff;
            width: 320px; /* Tamaño ideal para ticketera térmica */
            padding: 25px 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            border-radius: 5px;
            color: #000;
        }

        .header-ticket { text-align: center; margin-bottom: 20px; border-bottom: 2px dashed #ccc; padding-bottom: 15px; }
        .header-ticket h1 { margin: 0; font-size: 1.5rem; color: #dc2626; font-family: Arial, sans-serif; font-weight: 900;}
        .header-ticket p { margin: 5px 0 0 0; font-size: 0.85rem; }

        .info-ticket { font-size: 0.85rem; margin-bottom: 20px; border-bottom: 2px dashed #ccc; padding-bottom: 15px; }
        .info-ticket div { margin-bottom: 5px; }

        .items-ticket { width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 20px; }
        .items-ticket th { text-align: left; padding-bottom: 10px; border-bottom: 1px solid #ddd; }
        .items-ticket td { padding: 8px 0; border-bottom: 1px dashed #eee; }
        .items-ticket .right { text-align: right; }
        .items-ticket .center { text-align: center; }

        .total-ticket { font-size: 1.2rem; font-weight: bold; text-align: right; border-bottom: 2px dashed #ccc; padding-bottom: 15px; margin-bottom: 15px; }
        
        .footer-ticket { text-align: center; font-size: 0.8rem; color: #555; }

        /* Botones de acción (No se imprimen) */
        .actions { margin-top: 30px; display: flex; flex-direction: column; gap: 10px; }
        .btn { padding: 12px; border-radius: 6px; text-decoration: none; text-align: center; font-family: Arial, sans-serif; font-weight: bold; cursor: pointer; border: none; font-size: 1rem; }
        .btn-print { background-color: #dc2626; color: white; }
        .btn-back { background-color: #e2e8f0; color: #334155; }

        /* Ocultar botones al imprimir */
        @media print {
            body { background-color: white; padding: 0; display: block; }
            .ticket-box { box-shadow: none; width: 100%; max-width: 300px; margin: 0 auto; padding: 0; }
            .actions { display: none; }
        }
    </style>
</head>
<body>

    <div class="ticket-box">
        
        <div class="header-ticket">
            <h1><i class="fa-solid fa-briefcase-medical"></i> BOTICA LATINA</h1>
            <p>La mejor salud al mejor precio</p>
            <p>Av. Principal 123 - Ciudad</p>
        </div>

        <div class="info-ticket">
            <div><strong>TICKET N°:</strong> <?php echo str_pad($venta['id'], 6, "0", STR_PAD_LEFT); ?></div>
            <div><strong>FECHA:</strong> <?php echo date("d/m/Y H:i", strtotime($venta['fecha'])); ?></div>
            <div><strong>CLIENTE:</strong> <?php echo htmlspecialchars($venta['cliente_nombre'] ?? 'Público General'); ?></div>
            <?php if(!empty($venta['cliente_dni'])): ?>
                <div><strong>DNI:</strong> <?php echo htmlspecialchars($venta['cliente_dni']); ?></div>
            <?php endif; ?>
        </div>

        <table class="items-ticket">
            <thead>
                <tr>
                    <th>CANT</th>
                    <th>PRODUCTO</th>
                    <th class="right">SUBT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($detalles as $d): 
                    // Asumimos que tu modelo Detalles retorna 'cantidad', 'precio_unitario' y 'descripcion'
                    $desc = $d['descripcion'] ?? 'Producto';
                    $cant = $d['cantidad'] ?? 1;
                    $precio = $d['precio_unitario'] ?? 0;
                    $subt = $cant * $precio;
                ?>
                <tr>
                    <td class="center"><?php echo $cant; ?></td>
                    <td><?php echo htmlspecialchars($desc); ?></td>
                    <td class="right">$<?php echo number_format($subt, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-ticket">
            TOTAL: $<?php echo number_format($venta['total'], 2); ?>
        </div>

        <div class="footer-ticket">
            <p>¡Gracias por su preferencia!</p>
            <p>Conserve este ticket para cualquier reclamo.</p>
        </div>

        <div class="actions">
            <button class="btn btn-print" onclick="window.print()"><i class="fa-solid fa-print"></i> IMPRIMIR TICKET</button>
            <a href="index.php?view=caja" class="btn btn-back"><i class="fa-solid fa-arrow-left"></i> NUEVA VENTA</a>
        </div>

    </div>

</body>
</html>