<?php
require_once 'includes/auth.php';
require_once 'config/encryption.php'; // 👈 AGREGADO para funciones de cifrado
redirectIfNotLoggedIn();

global $pdo;

// Obtener productos del usuario
$stmt = $pdo->prepare("SELECT * FROM productos WHERE usuario_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$productos = $stmt->fetchAll();

// Obtener datos del usuario para mostrar en perfil
$stmtUser = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch();

// Descifrar datos sensibles del usuario
$telefono = decryptFromDb($user['telefono'] ?? '');
$direccion = decryptFromDb($user['direccion'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CRUD Tarea</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos adicionales para el dashboard */
        .user-info-bar {
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .user-details {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .user-details span {
            color: #666;
        }
        
        .user-details strong {
            color: #333;
            margin-left: 5px;
        }
        
        .encrypted-badge {
            background: #4CAF50;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            margin: 10px 0 0;
        }
        
        .table-container {
            overflow-x: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-edit, .btn-delete, .btn-view {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 13px;
            transition: opacity 0.3s;
        }
        
        .btn-edit { background: #ffc107; }
        .btn-delete { background: #dc3545; }
        .btn-view { background: #17a2b8; }
        
        .btn-edit:hover, .btn-delete:hover, .btn-view:hover {
            opacity: 0.8;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .alert.info {
            background: #e7f3ff;
            color: #0066cc;
            border: 1px solid #b8daff;
        }
        
        .alert.info a {
            color: #0066cc;
            font-weight: 600;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <span class="nav-brand">📋 CRUD Tarea</span>
            <div class="nav-menu">
                <span>👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="perfil.php" class="btn-nav">🔒 Mi Perfil</a> <!-- 👈 AGREGADO -->
                <a href="create.php" class="btn-nav">➕ Nuevo</a>
                <a href="logout.php" class="btn-nav">🚪 Salir</a>
            </div>
        </div>
    </nav>
    
    <main class="container">
        <!-- 👇 NUEVA BARRA DE INFORMACIÓN DEL USUARIO -->
        <div class="user-info-bar">
            <div class="user-details">
                <span>📧 <strong><?php echo htmlspecialchars($user['email']); ?></strong></span>
                <?php if ($telefono): ?>
                <span>📞 <strong><?php echo htmlspecialchars($telefono); ?></strong></span>
                <?php endif; ?>
                <?php if ($direccion): ?>
                <span>🏠 <strong><?php echo htmlspecialchars(substr($direccion, 0, 30)) . '...'; ?></strong></span>
                <?php endif; ?>
            </div>
            <div class="encrypted-badge">
                <span>🔐</span> Datos cifrados AES-256
            </div>
        </div>
        
        <!-- 👇 NUEVAS ESTADÍSTICAS -->
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Productos</h3>
                <div class="stat-number"><?php echo count($productos); ?></div>
            </div>
            <?php 
            $totalValor = 0;
            foreach ($productos as $item) {
                $totalValor += $item['precio'];
            }
            ?>
            <div class="stat-card">
                <h3>Valor Total</h3>
                <div class="stat-number">$<?php echo number_format($totalValor, 2); ?></div>
            </div>
            <div class="stat-card">
                <h3>Producto más caro</h3>
                <div class="stat-number">
                    $<?php 
                    $maxPrecio = 0;
                    foreach ($productos as $item) {
                        if ($item['precio'] > $maxPrecio) $maxPrecio = $item['precio'];
                    }
                    echo number_format($maxPrecio, 2); 
                    ?>
                </div>
            </div>
        </div>
        
        <h1>📦 Mis Productos</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">✅ Operación realizada con éxito</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">❌ Error al procesar la solicitud</div>
        <?php endif; ?>
        
        <?php if (empty($productos)): ?>
            <div class="alert info">
                📭 No tienes productos registrados. 
                <a href="create.php">¡Crea tu primer producto!</a>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $index => $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td><?php echo htmlspecialchars(substr($item['descripcion'], 0, 50)) . (strlen($item['descripcion']) > 50 ? '...' : ''); ?></td>
                            <td><strong>$<?php echo number_format($item['precio'], 2); ?></strong></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?></td>
                            <td class="actions">
                                <a href="edit.php?id=<?php echo $item['id']; ?>" class="btn-edit" title="Editar">✏️</a>
                                <a href="view.php?id=<?php echo $item['id']; ?>" class="btn-view" title="Ver detalles">👁️</a>
                                <a href="delete.php?id=<?php echo $item['id']; ?>" class="btn-delete" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este producto?')">🗑️</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- 👇 PIE DE TABLA CON TOTALES -->
            <div style="text-align: right; margin-top: 20px; color: #666;">
                <small>Mostrando <?php echo count($productos); ?> productos | 
                Total: <strong>$<?php echo number_format($totalValor, 2); ?></strong></small>
            </div>
        <?php endif; ?>
    </main>
    
    <!-- 👇 SCRIPT PARA CONFIRMACIÓN DE ELIMINACIÓN -->
    <script>
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm('⚠️ ¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>