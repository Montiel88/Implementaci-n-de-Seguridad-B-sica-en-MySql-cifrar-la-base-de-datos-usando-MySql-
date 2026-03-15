-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS crud_tarea;
USE crud_tarea;

-- ============================================
-- TABLA DE USUARIOS
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono TEXT,  -- Campo cifrado con AES-256 (desde PHP)
    direccion TEXT, -- Campo cifrado con AES-256 (desde PHP)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA DE PRODUCTOS
-- ============================================
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    usuario_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ÍNDICES PARA MEJORAR RENDIMIENTO
-- ============================================
CREATE INDEX idx_usuario_id ON productos(usuario_id);
CREATE INDEX idx_username ON usuarios(username);
CREATE INDEX idx_email ON usuarios(email);

-- ============================================
-- INSERTAR USUARIO DE PRUEBA
-- ============================================
-- Contraseña: 123456 (hasheada con password_hash())
INSERT INTO usuarios (username, email, password) VALUES 
('admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ============================================
-- INSERTAR ALGUNOS PRODUCTOS DE EJEMPLO
-- ============================================
INSERT INTO productos (nombre, descripcion, precio, usuario_id) VALUES
('Laptop HP', 'Laptop HP Pavilion 15.6", 8GB RAM, 256GB SSD', 850.00, 1),
('Mouse Inalámbrico', 'Mouse ergonómico con conexión USB', 25.50, 1),
('Teclado Mecánico', 'Teclado RGB con switches blue', 65.00, 1);

-- ============================================
-- VERIFICAR DATOS CIFRADOS (opcional)
-- ============================================
-- SELECT id, username, email, 
--        telefono, direccion 
-- FROM usuarios;

-- ============================================
-- PARA VER DATOS DESCIFRADOS (desde MySQL)
-- NOTA: Esto requiere la misma clave que en encryption.php
-- ============================================
/*
SELECT 
    id, 
    username,
    CAST(AES_DECRYPT(telefono, 'mi_clave_secreta_2026_#Segura') AS CHAR) as telefono_descifrado,
    CAST(AES_DECRYPT(direccion, 'mi_clave_secreta_2026_#Segura') AS CHAR) as direccion_descifrada
FROM usuarios;
*/