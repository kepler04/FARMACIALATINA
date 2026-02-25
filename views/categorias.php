<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Categorías</title>
    <link rel="stylesheet" href="assets/css/productos.css">
</head>
<body>

<div class="container" style="max-width: 650px;">
    <a href="index.php?view=productos" class="back-link">← Volver a Productos</a>
    <h2 class="header-title" style="color: #6f42c1; border-bottom-color: #6f42c1;">🏷️ Gestionar Categorías</h2>

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'guardado'): ?><div class="alert alert-success">✅ Categoría agregada.</div><?php endif; ?>
        <?php if($_GET['msg'] == 'actualizado'): ?><div class="alert alert-success">✏️ Categoría actualizada correctamente.</div><?php endif; ?>
        <?php if($_GET['msg'] == 'eliminado'): ?><div class="alert alert-success">🗑️ Categoría eliminada.</div><?php endif; ?>
        <?php if($_GET['msg'] == 'error'): ?><div class="alert alert-error">❌ Hubo un error.</div><?php endif; ?>
    <?php endif; ?>

    <form method="POST" action="index.php?action=guardar_categoria" class="seccion-form" style="margin-bottom: 20px;">
        <div class="form-group">
            <label>Nueva Categoría (Puedes usar Emojis)</label>
            <div style="display: flex; gap: 10px;">
                <input type="text" name="nombre" class="form-control" placeholder="Ej: 🍬 Caramelos" required autofocus>
                <button type="submit" class="btn-save" style="width: auto; background: linear-gradient(135deg, #6f42c1 0%, #59339d 100%);">➕ Agregar</button>
            </div>
        </div>
    </form>

    <table id="tablaProductos">
        <thead>
            <tr>
                <th style="background-color: #6f42c1;">Nombre de Categoría</th>
                <th style="text-align: center; background-color: #6f42c1; width: 120px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($listaCategorias)): ?>
                <tr><td colspan="2" style="text-align:center;">No hay categorías.</td></tr>
            <?php else: ?>
                <?php foreach($listaCategorias as $cat): ?>
                <tr>
                    <td style="font-weight: bold; font-size: 1.1rem;"><?php echo htmlspecialchars($cat['nombre']); ?></td>
                    <td style="text-align: center; white-space: nowrap;">
                        <a href="index.php?view=editar_categoria&id=<?php echo $cat['id']; ?>" class="btn-accion btn-edit" title="Editar Categoría">✏️</a>
                        
                        <a href="index.php?action=eliminar_categoria&id=<?php echo $cat['id']; ?>" class="btn-accion btn-delete" onclick="return confirm('¿Borrar esta categoría?');" title="Eliminar">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>