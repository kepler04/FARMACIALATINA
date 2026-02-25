<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pago</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #e9ecef; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #333; margin-top: 0; }
        .total { font-size: 2.5rem; text-align: center; color: #28a745; font-weight: bold; margin: 20px 0; }
        
        label { display: block; margin-top: 15px; font-weight: bold; color: #555; }
        input { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 1rem; }
        
        .btn-pay { width: 100%; padding: 15px; background: #28a745; color: white; border: none; font-size: 1.2rem; font-weight: bold; border-radius: 5px; cursor: pointer; margin-top: 25px; }
        .btn-pay:hover { background: #218838; }
        
        .btn-back { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; }
    </style>
</head>
<body>

<div class="card">
    <h2>Confirmar Venta</h2>
    
    <div class="total">$<?php echo number_format($total_calculado, 2); ?></div>
    
    <form action="index.php?action=finalizar_venta" method="POST">
        
        <label>Buscar Cliente (Escribe para buscar)</label>
        <input list="lista_clientes" id="input_nombre" name="cliente_nombre" placeholder="Escribe el nombre..." autocomplete="off" required>
        
        <datalist id="lista_clientes">
            <?php foreach($clientes as $c): ?>
                <option value="<?php echo $c['nombre']; ?>" data-dni="<?php echo $c['dni']; ?>">
            <?php endforeach; ?>
        </datalist>

        <label>DNI / RUC Cliente</label>
        <input type="text" id="input_dni" name="cliente_dni" placeholder="Ej: 12345678" required>
        
        <button type="submit" class="btn-pay">🖨️ Confirmar e Imprimir</button>
    </form>
    
    <a href="index.php?view=caja" class="btn-back">← Volver a modificar productos</a>
</div>

<script>
    const inputNombre = document.getElementById('input_nombre');
    const inputDni = document.getElementById('input_dni');
    const lista = document.getElementById('lista_clientes');

    // Escuchamos cuando el usuario escribe o selecciona algo en el nombre
    inputNombre.addEventListener('input', function() {
        const valor = this.value;
        const opciones = lista.options;

        // Buscamos si lo que escribió coincide con algún cliente de la lista
        for (let i = 0; i < opciones.length; i++) {
            if (opciones[i].value === valor) {
                // Si coincide, sacamos el DNI guardado y lo ponemos en la caja de texto
                inputDni.value = opciones[i].getAttribute('data-dni');
                break;
            }
        }
    });
</script>

</body>
</html>