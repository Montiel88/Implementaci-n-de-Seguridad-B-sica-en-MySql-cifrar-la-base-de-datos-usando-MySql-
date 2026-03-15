<?php
/**
 * Configuración de cifrado usando PHP (OpenSSL)
 * Funciona en cualquier versión de MySQL/XAMPP
 */
define('ENCRYPTION_KEY', 'mi_clave_secreta_2026_#Segura'); // ¡CAMBIA ESTO!
define('ENCRYPTION_IV', '1234567890123456'); // 16 bytes para AES-256-CBC

/**
 * Cifrar datos usando AES-256-CBC (PHP)
 * @param string $data Datos a cifrar
 * @return string|null Datos cifrados en base64
 */
function encryptData($data) {
    if (empty($data)) return null;
    $encrypted = openssl_encrypt(
        $data,
        'AES-256-CBC',
        ENCRYPTION_KEY,
        0,
        ENCRYPTION_IV
    );
    return $encrypted;
}

/**
 * Descifrar datos usando AES-256-CBC (PHP)
 * @param string $encryptedData Datos cifrados
 * @return string|null Datos descifrados
 */
function decryptData($encryptedData) {
    if (empty($encryptedData)) return null;
    return openssl_decrypt(
        $encryptedData,
        'AES-256-CBC',
        ENCRYPTION_KEY,
        0,
        ENCRYPTION_IV
    );
}

/**
 * Función para preparar datos para MySQL (cifrados en PHP)
 * @param mixed $data Datos a cifrar
 * @return string|null Datos cifrados listos para guardar
 */
function encryptForDb($data) {
    $encrypted = encryptData($data);
    return $encrypted ?: null;
}

/**
 * Función para descifrar datos de MySQL
 * @param string $data Datos cifrados desde la BD
 * @return string|null Datos descifrados
 */
function decryptFromDb($data) {
    return decryptData($data);
}
?>