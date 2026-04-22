-- Autor: Álvaro Torroba Velasco
-- Fecha: 2026-04-21
-- Versión: 1.0.0
-- Descripción: Estructura de base de datos para Nosecaen S.L.
-- Cumple: tablas, claves foráneas y disparador de fecha de creación.

CREATE DATABASE IF NOT EXISTS nosecaen CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nosecaen;

-- Tabla de provincias (códigos INE para el <select>)
CREATE TABLE provincias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo_ine VARCHAR(2) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- Insertamos algunas provincias básicas para que el select no esté vacío
INSERT INTO provincias (codigo_ine, nombre) VALUES 
('01','Araba/Álava'),('04','Almería'),('05','Ávila'),('08','Barcelona'),('10','Cáceres'),
('11','Cádiz'),('14','Córdoba'),('15','A Coruña'),('21','Huelva'),('28','Madrid'),
('30','Murcia'),('41','Sevilla'),('46','Valencia'),('48','Bizkaia'),('50','Zaragoza');

-- Empleados (Administradores y Operarios)
CREATE TABLE empleados (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(9) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    fecha_alta DATE NOT NULL,
    tipo ENUM('administrador','operario') DEFAULT 'operario',
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

-- Clientes
CREATE TABLE clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cif VARCHAR(9) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(150),
    cuenta_corriente VARCHAR(34),
    pais VARCHAR(50) NOT NULL,
    moneda VARCHAR(3) NOT NULL DEFAULT 'EUR',
    cuota_mensual DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

-- Cuotas (mensuales o excepcionales)
CREATE TABLE cuotas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT UNSIGNED NOT NULL,
    concepto VARCHAR(150) NOT NULL,
    fecha_emision DATE NOT NULL,
    importe DECIMAL(10,2) NOT NULL,
    pagada ENUM('S','N') DEFAULT 'N',
    fecha_pago DATE NULL,
    notas TEXT,
    archivo_pdf VARCHAR(255),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Incidencias / Tareas
CREATE TABLE incidencias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT UNSIGNED NOT NULL,
    persona_contacto VARCHAR(100) NOT NULL,
    telefono_contacto VARCHAR(20),
    descripcion TEXT NOT NULL,
    email_contacto VARCHAR(150) NOT NULL,
    direccion VARCHAR(255),
    poblacion VARCHAR(100),
    codigo_postal VARCHAR(5),
    provincia_codigo VARCHAR(2),
    estado ENUM('P','R','C') DEFAULT 'P',
    operario_id INT UNSIGNED NULL,
    fecha_realizacion DATE NULL,
    anotaciones_antes TEXT,
    anotaciones_despues TEXT,
    archivo_resumen VARCHAR(255),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    FOREIGN KEY (operario_id) REFERENCES empleados(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Disparador obligatorio (PDF: "Se debería usar un disparador")
DELIMITER $$
CREATE TRIGGER trg_incidencias_created_at
BEFORE INSERT ON incidencias
FOR EACH ROW
BEGIN
    IF NEW.created_at IS NULL THEN
        SET NEW.created_at = NOW();
    END IF;
END$$
DELIMITER ;