<?php
require_once 'includes/auth.php';
require_once 'config/encryption.php'; // 👈 AGREGAR para cifrado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $telefono = $_POST['telefono'] ?? ''; // 👈 NUEVO
    $direccion = $_POST['direccion'] ?? ''; // 👈 NUEVO
    
    if ($password !== $confirm) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        // Cifrar datos sensibles
        $telefono_enc = encryptForDb($telefono);
        $direccion_enc = encryptForDb($direccion);
        
        global $pdo;
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        // Modificar la consulta para incluir los nuevos campos
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, email, password, telefono, direccion) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashed, $telefono_enc, $direccion_enc])) {
            $success = "Registro exitoso. <a href='index.php'>Inicia sesión</a>";
        } else {
            $error = "El usuario o email ya existe";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - CRUD Tarea</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <h2>📝 Registrarse</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <!-- 👇 NUEVOS CAMPOS CIFRADOS -->
            <div class="form-group">
                <label>📞 Teléfono (opcional):</label>
                <input type="tel" name="telefono" placeholder="Ej: +34 612 345 678">
                <small style="color: #666; display: block; margin-top: 5px;">🔒 Se cifrará en la base de datos</small>
            </div>
            
            <div class="form-group">
                <label>🏠 Dirección (opcional):</label>
                <textarea name="direccion" rows="2" placeholder="Tu dirección completa"></textarea>
                <small style="color: #666; display: block; margin-top: 5px;">🔒 Se cifrará en la base de datos</small>
            </div>
            <!-- 👆 NUEVOS CAMPOS CIFRADOS -->
            
            <div class="form-group">
                <label>Contraseña (mínimo 6 caracteres):</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Confirmar Contraseña:</label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn-primary">Registrarse</button>
        </form>
        
        <p class="register-link">¿Ya tienes cuenta? <a href="index.php">Inicia sesión</a></p>
    </div>
</body>
</html>