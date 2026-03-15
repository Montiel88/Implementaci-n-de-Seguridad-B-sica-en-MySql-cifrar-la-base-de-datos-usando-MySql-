<?php
require_once 'includes/auth.php';
require_once 'config/encryption.php';
redirectIfNotLoggedIn();

$id = $_GET['id'] ?? 0;

global $pdo;

// Verificar que el producto pertenece al usuario
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$producto = $stmt->fetch();

if (!$producto) {
    header('Location: dashboard.php?error=notfound');
    exit;
}

// Obtener datos del usuario para mostrar
$stmtUser = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch();

// Descifrar datos del usuario
$telefono = decryptFromDb($user['telefono'] ?? '');
$direccion = decryptFromDb($user['direccion'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .product-detail {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            max-width: 800px;
            margin: 40px auto;
            overflow: hidden;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .product-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .product-header h1 {
            margin: 0;
            font-size: 2rem;
            word-break: break-word;
        }
        
        .product-header .product-id {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-top: 10px;
        }
        
        .product-body {
            padding: 40px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        
        .info-section h3 {
            margin: 0 0 15px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-section .label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .info-section .value {
            color: #333;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            word-break: break-word;
        }
        
        .info-section .description-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            min-height: 100px;
        }
        
        .price-tag {
            display: inline-block;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 2rem;
            font-weight: 700;
            margin: 20px 0;
        }
        
        .meta-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 2px dashed #e0e0e0;
            color: #666;
        }
        
        .owner-info {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .owner-info p {
            margin: 5px 0;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn-action {
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #ffc107, #ffb300);
            color: #333;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .encryption-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-left: 10px;
        }
        
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .product-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <span class="nav-brand">📋 Detalle del Producto</span>
            <div class="nav-menu">
                <span>👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="dashboard.php" class="btn-nav">⬅️ Volver</a>
                <a href="logout.php" class="btn-nav">🚪 Salir</a>
            </div>
        </div>
    </nav>
    
    <main class="container">
        <div class="product-detail">
            <div class="product-header">
                <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
                <span class="product-id">ID: #<?php echo $producto['id']; ?></span>
            </div>
            
            <div class="product-body">
                <div class="info-grid">
                    <div class="info-section">
                        <h3>📋 Información General</h3>
                        <div class="label">Precio</div>
                        <div class="price-tag">$<?php echo number_format($producto['precio'], 2); ?></div>
                        
                        <div class="label">Fecha de creación</div>
                        <div class="value"><?php echo date('d/m/Y H:i:s', strtotime($producto['created_at'])); ?></div>
                    </div>
                    
                    <div class="info-section">
                        <h3>📝 Descripción Completa</h3>
                        <div class="description-box">
                            <?php echo nl2br(htmlspecialchars($producto['descripcion'] ?: 'Sin descripción')); ?>
                        </div>
                    </div>
                </div>
                
                <div class="meta-info">
                    <span>🔒 Datos cifrados con AES-256</span>
                    <span>📦 Producto #<?php echo $producto['id']; ?></span>
                </div>
                
                <div class="owner-info">
                    <h4 style="margin: 0 0 10px; display: flex; align-items: center; gap: 10px;">
                        👤 Propietario 
                        <span class="encryption-badge">🔐 Cifrado</span>
                    </h4>
                    <p><strong>Usuario:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <?php if ($telefono): ?>
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></p>
                    <?php endif; ?>
                    <?php if ($direccion): ?>
                    <p><strong>Dirección:</strong> <?php echo htmlspecialchars($direccion); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="action-buttons">
                    <a href="dashboard.php" class="btn-action btn-back">
                        <span>⬅️</span> Volver al Dashboard
                    </a>
                    <a href="edit.php?id=<?php echo $producto['id']; ?>" class="btn-action btn-edit">
                        <span>✏️</span> Editar Producto
                    </a>
                    <a href="delete.php?id=<?php echo $producto['id']; ?>" class="btn-action btn-delete" 
                       onclick="return confirm('⚠️ ¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.')">
                        <span>🗑️</span> Eliminar
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>