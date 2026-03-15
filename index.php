<?php
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRUD Tarea</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <h2>🔐 Iniciar Sesión</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-primary">Ingresar</button>
        </form>
        
        <p class="register-link">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        <p class="demo">Demo: admin / 123456</p>
    </div>
</body>
</html>