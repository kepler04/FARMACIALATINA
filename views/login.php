<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Botica Latina</title>
    <link rel="stylesheet" href="assets/css/login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="login-wrapper">
    
    <div class="login-left">
        <i class="fa-solid fa-staff-snake brand-icon"></i>
        <h1 class="brand-title">BOTICA LATINA</h1>
        <p class="brand-subtitle">Sistema Profesional de Gestión Farmacéutica</p>
    </div>

    <div class="login-right">
        <div class="login-form-container">
            
            <div class="login-header">
                <h2>Bienvenido de nuevo 👋</h2>
                <p>Ingresa tus credenciales para acceder al panel.</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php">
                
                <div class="form-group">
                    <label class="form-label">Usuario</label>
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" name="usuario" class="form-control" placeholder="Ej: admin" required autofocus autocomplete="off">
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>

                <button type="submit" class="btn-login">
                    Ingresar al Sistema <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i>
                </button>

            </form>
            
            <p style="text-align: center; margin-top: 30px; color: #94a3b8; font-size: 0.9rem;">
                © <?php echo date('Y'); ?> Botica Latina. Todos los derechos reservados.
            </p>

        </div>
    </div>

</div>

</body>
</html>