<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="assets/css/productos.css">
</head>
<body>

<div class="container" style="max-width: 600px;">
    <a href="index.php?view=categorias" class="back-link">← Cancelar y Volver</a>
    <h2 class="header-title" style="color: #6f42c1; border-bottom-color: #6f42c1;">✏️ Editar Categoría</h2>

    <form method="POST" action="index.php?action=actualizar_categoria" class="seccion-form">
        <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
        
        <div class="form-group">
            <label>Nombre de la Categoría</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($categoria['nombre']); ?>" required autofocus>
        </div>
        
        <button type="submit" class="btn-save" style="background: linear-gradient(135deg, #6f42c1 0%, #59339d 100%); margin-top: 15px;">💾 Guardar Cambios</button>
    </form>
</div>

</body>
</html>