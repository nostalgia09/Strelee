-- Strelee — esquema y datos demo (streetwear negro+lima)
-- Importar: /Applications/XAMPP/xamppfiles/bin/mysql -u root < bd/strelee.sql

CREATE DATABASE IF NOT EXISTS strelee
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE strelee;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS pedido;
DROP TABLE IF EXISTS prenda;
DROP TABLE IF EXISTS categoria;
DROP TABLE IF EXISTS administrador;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE administrador (
  id_admin INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categoria (
  id_categoria INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE prenda (
  id_prenda INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  talla VARCHAR(20) NOT NULL,
  precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  descripcion TEXT NULL,
  disponible TINYINT(1) NOT NULL DEFAULT 1,
  foto_url VARCHAR(500) NULL,
  id_categoria INT UNSIGNED NOT NULL,
  CONSTRAINT fk_prenda_categoria FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pedido (
  id_pedido INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_cliente VARCHAR(150) NOT NULL,
  telefono_cliente VARCHAR(30) NOT NULL,
  descripcion_solicitud TEXT NULL,
  tipo ENUM('disponible','personalizado') NOT NULL,
  fecha DATE NOT NULL,
  id_prenda INT UNSIGNED NULL,
  CONSTRAINT fk_pedido_prenda FOREIGN KEY (id_prenda) REFERENCES prenda(id_prenda)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin: admin@demo.com / Admin123!
INSERT INTO administrador (nombre, correo, password_hash) VALUES
('Admin Strelee', 'admin@demo.com', '$2y$10$xd4RXbPqhDTCVR4DOYzzS.ZF9gW40vMztCDPsalAtDAZ.7orwUeCW');

INSERT INTO categoria (nombre) VALUES
('Pantalones'),
('Buzos'),
('Vestidos'),
('Abrigos'),
('Chaquetas'),
('Camisas');

INSERT INTO prenda (nombre, talla, precio, descripcion, disponible, foto_url, id_categoria) VALUES
('Jean baggy vintage', 'M', 65000.00, 'Denim lavado años 90, corte amplio. Segunda vida, estado 9/10.', 1, 'img/jean-baggy.jpg', 1),
('Cargo militar reused', 'L', 72000.00, 'Bolsillos laterales, tono oliva. Upcycling local, sin roturas.', 1, 'img/cargo-militar.jpg', 1),
('Jogger reciclado negro', 'M', 52000.00, 'Algodón reciclado, puños elásticos. Ideal street daily.', 1, 'img/jogger-negro.jpg', 1),
('Cargo short wash', 'S', 45000.00, 'Short cargo deslavado, cintura ajustable. Pieza única.', 1, 'img/cargo-shorts.jpg', 1),
('Buzo oversize gris', 'L', 55000.00, 'Felpa suave, cuello redondo. Sin estampados rotos.', 1, 'img/buzo-gris.jpg', 2),
('Hoodie negro street', 'M', 68000.00, 'Capucha doble, vibes lima. Única en rack.', 1, 'img/hoodie-negro.jpg', 2),
('Polar fleece lime vibe', 'L', 58000.00, 'Polar medio peso, cuello alto. Perfecto para layering.', 1, 'img/polar-fleece.jpg', 2),
('Tee oversized washed', 'L', 38000.00, 'Algodón pesado, drop shoulder. Lavado vintage.', 1, 'img/oversized-tee.jpg', 2),
('Vestido midi floreado', 'S', 48000.00, 'Estampado floral, largo midi. Ideal primavera urbana.', 1, 'img/vestido-midi.jpg', 3),
('Abrigo trench beige', 'M', 120000.00, 'Gabardina clásica con cinturón. Forro íntegro.', 1, 'img/trench-beige.jpg', 4),
('Windbreaker packable', 'M', 62000.00, 'Rompevientos liviano, bolsillo pack. Tonos street.', 1, 'img/windbreaker.jpg', 4),
('Chaqueta jean clásica', 'S', 70000.00, 'Jean medio, botones metálicos. Unisex.', 1, 'img/chaqueta-jean.jpg', 5),
('Bomber patchwork', 'L', 95000.00, 'Parches bordados, forro interior. Pieza única — reservada.', 0, 'img/bomber-patch.jpg', 5),
('Flannel check reused', 'M', 42000.00, 'Camisa flannel a cuadros, soft wash. Second-hand premium.', 1, 'img/camisa-flannel.jpg', 6);

INSERT INTO pedido (nombre_cliente, telefono_cliente, descripcion_solicitud, tipo, fecha, id_prenda) VALUES
('Ana Pérez', '3001112233', 'Retiro en Cáceres sábado AM', 'disponible', '2026-03-10', 1),
('Laura Ruiz', '3023334455', NULL, 'disponible', '2026-03-14', 5),
('Mateo Ríos', '3158889900', '¿Incluye envío a Yopal?', 'disponible', '2026-03-16', 6),
('Sofía Castro', '3204445566', NULL, 'disponible', '2026-03-18', 10),
('Carlos Gómez', '3012223344', 'Hoodie negro oversize con logo lima bordado en pecho, cordones lima.\nTalla deseada: M', 'personalizado', '2026-03-12', NULL),
('Diego Mora', '3105556677', 'Pantalón cargo a medida, bolsillos extras y cintura reforzada.\nTalla deseada: L', 'personalizado', '2026-03-15', NULL),
('Valentina Cruz', '3187778899', 'Bomber negra con parche STRELEE en manga, forro lima.\nTalla deseada: S', 'personalizado', '2026-03-17', NULL),
('Andrés Quintero', '3009990011', 'Camisa flannel custom: cuadros negro/lima, botones metálicos.\nTalla deseada: XL', 'personalizado', '2026-03-19', NULL);
