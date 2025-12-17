-- Script para crear la tabla aguinaldo_consulta_2025
-- Base de datos: fecolsub_tienda_2

CREATE TABLE IF NOT EXISTS aguinaldo_consulta_2025 (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cookie_id VARCHAR(255) UNIQUE NOT NULL,
    identificacion VARCHAR(50) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    codigo_otp INT NOT NULL,
    intentos_fallidos INT DEFAULT 0,
    estado ENUM('activo', 'validado', 'expirado', 'bloqueado') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion TIMESTAMP NOT NULL,
    fecha_validacion TIMESTAMP NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(500),
    
    -- Índices para optimización
    INDEX idx_cookie_id (cookie_id),
    INDEX idx_identificacion (identificacion),
    INDEX idx_fecha_expiracion (fecha_expiracion),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Descripción de columnas:
-- 1. id: Identificador único de la sesión
-- 2. cookie_id: Identificador de sesión único (hash MD5)
-- 3. identificacion: Cédula del usuario
-- 4. nombre: Nombre completo del usuario
-- 5. telefono: Teléfono del usuario para envío de SMS
-- 6. codigo_otp: Código de un solo uso enviado por SMS
-- 7. intentos_fallidos: Contador de intentos fallidos (máx 3)
-- 8. estado: Estado de la sesión (activo, validado, expirado, bloqueado)
-- 9. fecha_creacion: Fecha/hora de creación de la sesión
-- 10. fecha_expiracion: Fecha/hora en que expira la sesión (15 minutos)
-- 11. fecha_validacion: Fecha/hora en que se validó el OTP
-- 12. ip_address: IP del cliente para auditoría
-- 13. user_agent: Navegador/dispositivo del cliente
