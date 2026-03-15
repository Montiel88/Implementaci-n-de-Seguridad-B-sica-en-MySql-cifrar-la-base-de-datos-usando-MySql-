<?php
require_once 'includes/auth.php';
require_once 'config/encryption.php';
redirectIfNotLoggedIn();

global $pdo;
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Descifrar datos
$telefono = decryptFromDb($user['telefono']);
$direccion = decryptFromDb($user['direccion']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil Seguro</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .profile-info {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .profile-info p {
            margin: 10px 0;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .security-note {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2196F3;
            margin-top: 20px;
        }
        
        .security-note h3 {
            color: #1976D2;
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .encrypted-badge {
            background: #4CAF50;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-left: 10px;
        }
        
        .data-sample {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            word-break: break-all;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <span class="nav-brand">🔒 Perfil Seguro</span>
            <div class="nav-menu">
                <a href="dashboard.php" class="btn-nav">⬅️ Volver</a>
                <a href="logout.php" class="btn-nav">🚪 Salir</a>
            </div>
        </div>
    </nav>
    
    <main class="container">
        <div class="profile-card">
            <h2>🔐 Datos Personales <span class="encrypted-badge">Cifrados AES-256</span></h2>
            
            <div class="profile-info">
                <p><strong>👤 Usuario:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>📧 Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>📞 Teléfono:</strong> <?php echo htmlspecialchars($telefono ?? 'No especificado'); ?></p>
                <p><strong>🏠 Dirección:</strong> <?php echo htmlspecialchars($direccion ?? 'No especificada'); ?></p>
                <p><strong>📅 Miembro desde:</strong> <?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></p>
            </div>
            
            <div class="security-note">
                <h3>🔒 Cifrado AES-256 en acción</h3>
                <p>✅ Tus datos de teléfono y dirección están cifrados en la base de datos usando AES-256-CBC.</p>
                <p>✅ Incluso si alguien accede directamente a la base de datos, NO podrá leer esta información.</p>
                <p>✅ La clave de cifrado está en el servidor (archivo encryption.php), no en la base de datos.</p>
                
                <?php if ($user['telefono']): ?>
                <div class="data-sample">
                    <small>🔐 Dato cifrado en BD:</small><br>
                    <code><?php echo htmlspecialchars(substr($user['telefono'], 0, 50)) . '...'; ?></code>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>