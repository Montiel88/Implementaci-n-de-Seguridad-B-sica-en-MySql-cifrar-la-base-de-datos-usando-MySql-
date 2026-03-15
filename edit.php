<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$id = $_GET['id'] ?? 0;
global $pdo;

// Verificar que el producto pertenece al usuario
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$producto = $stmt->fetch();

if (!$producto) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    
    $errors = [];
    if (empty($nombre)) $errors[] = "El nombre es obligatorio";
    if ($precio <= 0) $errors[] = "El precio debe ser mayor a 0";
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ? WHERE id = ? AND usuario_id = ?");
        if ($stmt->execute([$nombre, $descripcion, $precio, $id, $_SESSION['user_id']])) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Error al actualizar";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <span class="nav-brand">✏️ Editar Producto</span>
            <div class="nav-menu">
                <a href="dashboard.php" class="btn-nav">⬅️ Volver</a>
            </div>
        </div>
    </nav>
    
    <main class="container">
        <h1>Editar Producto</h1>
        
        <form method="POST" class="form-crud">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="3"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Precio ($):</label>
                <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" required>
            </div>
            
            <button type="submit" class="btn-primary">Actualizar</button>
        </form>
    </main>
</body>
</html>