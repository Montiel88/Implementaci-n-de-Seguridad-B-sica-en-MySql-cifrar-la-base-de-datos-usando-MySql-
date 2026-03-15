<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    
    $errors = [];
    if (empty($nombre)) $errors[] = "El nombre es obligatorio";
    if ($precio <= 0) $errors[] = "El precio debe ser mayor a 0";
    
    if (empty($errors)) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, usuario_id) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nombre, $descripcion, $precio, $_SESSION['user_id']])) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Error al guardar";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <span class="nav-brand">📋 Nuevo Producto</span>
            <div class="nav-menu">
                <a href="dashboard.php" class="btn-nav">⬅️ Volver</a>
            </div>
        </div>
    </nav>
    
    <main class="container">
        <h1>Crear Producto</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <?php foreach ($errors as $e): ?>
                    <p><?php echo $e; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="form-crud">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Precio ($):</label>
                <input type="number" step="0.01" name="precio" required>
            </div>
            
            <button type="submit" class="btn-primary">Guardar</button>
        </form>
    </main>
</body>
</html>