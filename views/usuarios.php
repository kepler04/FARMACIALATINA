<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* LAYOUT 2 COLUMNAS: Formulario y Tabla */
        .layout-usuarios { display: grid; grid-template-columns: 420px 1fr; gap: 30px; margin-top: 20px; }
        
        .panel-registro { background: white; padding: 35px; border-radius: 8px; border-top: 5px solid #dc2626; box-shadow: 0 4px 10px rgba(0,0,0,0.03); height: fit-content; }
        .panel-tabla { background: white; padding: 35px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        
        /* CAMPOS GIGANTES */
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-weight: bold; color: #475569; margin-bottom: 10px; font-size: 1.15rem; }
        .form-control { width: 100%; padding: 15px; font-size: 1.2rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; transition: 0.3s; }
        .form-control:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
        
        .btn-rojo { background: #dc2626; color: white; border: none; padding: 20px; border-radius: 8px; font-size: 1.4rem; font-weight: bold; width: 100%; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 10px; }
        .btn-rojo:hover { background: #b91c1c; transform: scale(1.02); }

        /* TABLA MODERNA */
        .tabla-moderna { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tabla-moderna th { background: #f8fafc; padding: 18px; text-align: left; color: #64748b; text-transform: uppercase; font-size: 1rem; border-bottom: 2px solid #e2e8f0; }
        .tabla-moderna td { padding: 18px; border-bottom: 1px solid #f1f5f9; font-size: 1.15rem; vertical-align: middle; color: #1e293b; }

        .badge-rol { padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 0.95rem; }
        .rol-admin { background: #fee2e2; color: #dc2626; }
        .rol-vendedor { background: #dcfce7; color: #16a34a; }
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
                <li><a href="index.php?view=salidas"><i class="fa-solid fa-right-from-bracket"></i> Salidas / Mermas</a></li>
                <li><a href="index.php?view=historial"><i class="fa-solid fa-file-invoice-dollar"></i> Historial</a></li>
                
                <li class="active"><a href="index.php?view=usuarios"><i class="fa-solid fa-user-shield"></i> Usuarios</a></li>
            <?php endif; ?>
            
            <li style="margin-top: 40px;"><a href="index.php?action=logout" style="color: #fecaca;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            <div class="user-info">
                <span>Control de Personal</span>
                <span style="font-size: 1.3rem; border-left: 2px solid #e2e8f0; padding-left: 20px; margin-left:15px;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <i class="fa-solid fa-circle-user" style="color:#dc2626; font-size: 1.8rem;"></i>
            </div>
        </header>

        <div class="dashboard-container">
            <h2 style="color: #0f172a; margin-top:0; font-size: 2.2rem; margin-bottom: 25px;"><i class="fa-solid fa-users-gear" style="color:#dc2626;"></i> Gestión de Usuarios y Roles</h2>
            
            <div class="layout-usuarios">
                
                <div class="panel-registro">
                    <h3 style="margin-top:0; color:#1e293b; font-size: 1.4rem; margin-bottom: 25px;"><i class="fa-solid fa-user-plus"></i> Nuevo Empleado</h3>
                    
                    <form action="index.php?action=guardar_usuario" method="POST">
                        <div class="form-group">
                            <label>Nombre Completo *</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Lorena Mendoza" required>
                        </div>

                        <div class="form-group">
                            <label>Usuario (Para login) *</label>
                            <input type="text" name="usuario" class="form-control" placeholder="Ej: lorena_bt" required>
                        </div>

                        <div class="form-group">
                            <label>Contraseña *</label>
                            <input type="password" name="password" class="form-control" placeholder="****" required>
                        </div>

                        <div class="form-group">
                            <label>Rol en el Sistema *</label>
                            <select name="rol" class="form-control" required>
                                <option value="Vendedor">Vendedor (Solo Caja y Clientes)</option>
                                <option value="Administrador">Administrador (Acceso Total)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-rojo"><i class="fa-solid fa-floppy-disk"></i> Guardar Usuario</button>
                    </form>
                </div>

                <div class="panel-tabla">
                    <h3 style="margin-top:0; color:#1e293b; font-size: 1.4rem; margin-bottom: 20px;"><i class="fa-solid fa-list"></i> Lista de Personal</h3>
                    
                    <table class="tabla-moderna">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th style="text-align:center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($listaUsuarios)): ?>
                                <?php foreach($listaUsuarios as $u): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($u['nombre']) ?></strong></td>
                                    <td style="color:#64748b; font-family:monospace;"><?= htmlspecialchars($u['usuario']) ?></td>
                                    <td>
                                        <span class="badge-rol <?= ($u['rol'] == 'Administrador') ? 'rol-admin' : 'rol-vendedor' ?>">
                                            <?= $u['rol'] ?>
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="index.php?action=eliminar_usuario&id=<?= $u['id'] ?>" class="btn-borrar" style="color:#ef4444; font-size:1.5rem;" onclick="return confirm('¿Borrar este usuario? Ya no podrá entrar al sistema.');">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
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
</body>
</html>