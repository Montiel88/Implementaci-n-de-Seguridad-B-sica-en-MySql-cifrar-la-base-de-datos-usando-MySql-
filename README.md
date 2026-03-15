# 🔐 CRUD PHP MySQL con Cifrado AES-256

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat&logo=mysql)
![XAMPP](https://img.shields.io/badge/XAMPP-8.0+-FB7A24?style=flat&logo=xampp)
![License](https://img.shields.io/badge/License-MIT-green)

Proyecto educativo que implementa un **CRUD completo** con **autenticación de usuarios** y **cifrado AES-256** para datos sensibles. Desarrollado en PHP con MySQL y Bootstrap.

---

## 📋 Tabla de Contenidos
- [Características Principales](#-características-principales)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Requisitos Previos](#-requisitos-previos)
- [Instalación Paso a Paso](#-instalación-paso-a-paso)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Guía de Uso](#-guía-de-uso)
- [Seguridad Implementada](#-seguridad-implementada)
- [Base de Datos](#-base-de-datos)
- [Capturas de Pantalla](#-capturas-de-pantalla)
- [Créditos](#-créditos)

---

## ✨ Características Principales

✅ **Autenticación completa**
- Registro de nuevos usuarios
- Login con verificación de contraseña
- Cierre de sesión seguro
- Persistencia de sesión

✅ **CRUD de Productos**
- **Crear**: Añadir nuevos productos con nombre, descripción y precio
- **Leer**: Listar todos los productos del usuario
- **Actualizar**: Editar productos existentes
- **Eliminar**: Borrar productos con confirmación

✅ **Seguridad Avanzada**
- Cifrado AES-256 para datos sensibles (teléfono, dirección)
- Hashing de contraseñas con `password_hash()`
- Prepared statements contra inyección SQL
- Separación de datos por usuario
- Validación de formularios

✅ **Interfaz de Usuario**
- Diseño responsive con CSS profesional
- Dashboard con estadísticas
- Vista detallada de productos
- Perfil de usuario con datos cifrados
- Botón de navegación secuencial

---

## 🛠 Tecnologías Utilizadas

| Tecnología | Versión | Uso |
|------------|---------|-----|
| PHP | 7.4+ | Lógica del servidor |
| MySQL | 5.7+ | Base de datos |
| HTML5 | - | Estructura de páginas |
| CSS3 | - | Estilos y diseño responsive |
| XAMPP | 8.0+ | Entorno de desarrollo local |
| Git | - | Control de versiones |

---

## 📦 Requisitos Previos

- **XAMPP** (Apache + MySQL + PHP) instalado
- **Navegador web** moderno (Chrome, Firefox, Edge)
- **Git** (opcional, para clonar el repositorio)
- **Conocimientos básicos** de PHP/MySQL (para entender el código)

---

## 🚀 Instalación Paso a Paso

### **1. Clonar el repositorio**
```bash
cd C:\xampp\htdocs
git clone https://github.com/Montiel88/Implementaci-n-de-Seguridad-B-sica-en-MySql-cifrar-la-base-de-datos-usando-MySql-.git crud-tarea