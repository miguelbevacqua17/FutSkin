-- Create the database
CREATE DATABASE futskin;

-- Switch to the database
USE futskin;

-- Table structure and data for table `categorias`
CREATE TABLE categorias (
  id int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(255) DEFAULT NULL,
  descripcion varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
);

INSERT INTO `categorias` (`nombre`, `descripcion`) VALUES ('River', 'descripcion de River');
INSERT INTO `categorias` (`nombre`, `descripcion`) VALUES ('Boca', 'descripcion de Boca');
INSERT INTO `categorias` (`nombre`, `descripcion`) VALUES ('San Lorenzo', 'descripcion de San Lorenzo');
INSERT INTO `categorias` (`nombre`, `descripcion`) VALUES ('Independiente', 'descripcion de Independiente');
INSERT INTO `categorias` (`nombre`, `descripcion`) VALUES ('Racing', 'descripcion de Racing');


-- Table structure and data for table `envios`
CREATE TABLE `envios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barrio` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `altura` varchar(255) DEFAULT NULL,
  `piso` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `envios` (`barrio`, `direccion`, `altura`, `piso`) VALUES ('Palermo', 'Avenida Santa Fe', '1234', '2');
INSERT INTO `envios` (`barrio`, `direccion`, `altura`, `piso`) VALUES ('Belgrano', 'Calle Cabildo', '5678', '3');
INSERT INTO `envios` (`barrio`, `direccion`, `altura`, `piso`) VALUES ('Recoleta', 'Avenida Alvear', '910', '1');
INSERT INTO `envios` (`barrio`, `direccion`, `altura`, `piso`) VALUES ('San Telmo', 'Calle Defensa', '4321', '4');
INSERT INTO `envios` (`barrio`, `direccion`, `altura`, `piso`) VALUES ('La Boca', 'Calle Caminito', '987', '2');


-- Table structure and data for table `facturas`
CREATE TABLE `facturas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_a_pagar` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `facturas` (`total_a_pagar`) VALUES (200);
INSERT INTO `facturas` (`total_a_pagar`) VALUES (400);
INSERT INTO `facturas` (`total_a_pagar`) VALUES (600);
INSERT INTO `facturas` (`total_a_pagar`) VALUES (800);
INSERT INTO `facturas` (`total_a_pagar`) VALUES (1000);

-- Table structure and data for table `productos`
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_fk` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `descripcion` varchar(510) DEFAULT NULL,
  `precio_lista` decimal(10,0) DEFAULT NULL,
  `descuento` decimal(10,0) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `deleteable` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_fk` (`categoria_fk`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_fk`) REFERENCES `categorias` (`id`)
);

INSERT INTO `productos` (`categoria_fk`, `nombre`, `imagen`, `descripcion`, `precio_lista`, `descuento`, `stock`, `deleteable`) VALUES (1, 'Camiseta de Fútbol', 'camiseta_futbol.jpg', 'Camiseta oficial de fútbol de Argentina', 500, 20, 50, 0);
INSERT INTO `productos` (`categoria_fk`, `nombre`, `imagen`, `descripcion`, `precio_lista`, `descuento`, `stock`, `deleteable`) VALUES (2, 'Zapatillas de Running', 'zapatillas_running.jpg', 'Zapatillas ideales para correr', 800, 15, 30, 0);
INSERT INTO `productos` (`categoria_fk`, `nombre`, `imagen`, `descripcion`, `precio_lista`, `descuento`, `stock`, `deleteable`) VALUES (3, 'Shorts Deportivos', 'shorts_deportivos.jpg', 'Shorts cómodos para actividades físicas', 300, 10, 70, 0);
INSERT INTO `productos` (`categoria_fk`, `nombre`, `imagen`, `descripcion`, `precio_lista`, `descuento`, `stock`, `deleteable`) VALUES (1, 'Mochila Deportiva', 'mochila_deportiva.jpg', 'Mochila con varios compartimentos para deportistas', 600, 25, 40, 0);
INSERT INTO `productos` (`categoria_fk`, `nombre`, `imagen`, `descripcion`, `precio_lista`, `descuento`, `stock`, `deleteable`) VALUES (4, 'Guantes de Boxeo', 'guantes_boxeo.jpg', 'Guantes profesionales para entrenamiento de boxeo', 200, 5, 20, 0);

-- Table structure and data for table `clientes`
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `envio_fk` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol` tinyint(1) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `envio_fk` (`envio_fk`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`envio_fk`) REFERENCES `envios` (`id`)
);

INSERT INTO `clientes` (`envio_fk`, `nombre`, `apellido`, `email`, `contrasena`, `rol`, `imagen`) 
VALUES (NULL, 'María', 'González', 'maria@gmail.com', '1234', NULL, 'imagen1.jpg');
INSERT INTO `clientes` (`envio_fk`, `nombre`, `apellido`, `email`, `contrasena`, `rol`, `imagen`) 
VALUES (NULL, 'Carlos', 'Martínez', 'carlos@gmail.com', '1234', NULL, 'imagen2.jpg');
INSERT INTO `clientes` (`envio_fk`, `nombre`, `apellido`, `email`, `contrasena`, `rol`, `imagen`) 
VALUES (NULL, 'Laura', 'Rodríguez', 'laura@gmail.com', '1234', NULL, 'imagen3.jpg');
INSERT INTO `clientes` (`envio_fk`, `nombre`, `apellido`, `email`, `contrasena`, `rol`, `imagen`) 
VALUES (NULL, 'Juan', 'Pérez', 'juan@gmail.com', '1234', NULL, 'imagen4.jpg');
INSERT INTO `clientes` (`envio_fk`, `nombre`, `apellido`, `email`, `contrasena`, `rol`, `imagen`) 
VALUES (NULL, 'Ana', 'López', 'ana@gmail.com', '1234', NULL, 'imagen5.jpg');


-- Table structure and data for table `pedidos`
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_fk` int(11) DEFAULT NULL,
  `producto_fk` int(11) DEFAULT NULL,
  `factura_fk` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,0) DEFAULT NULL,
  `cantidad_prod` int(11) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_fk` (`cliente_fk`),
  KEY `factura_fk` (`factura_fk`),
  KEY `producto_fk` (`producto_fk`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_fk`) REFERENCES `clientes` (`id`),
  CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`factura_fk`) REFERENCES `facturas` (`id`),
  CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`id`)
);

INSERT INTO `pedidos` (`cliente_fk`, `producto_fk`, `factura_fk`, `precio_venta`, `cantidad_prod`, `estado`)
VALUES (1, 1, 1, 500, 2, 'pendiente');
INSERT INTO `pedidos` (`cliente_fk`, `producto_fk`, `factura_fk`, `precio_venta`, `cantidad_prod`, `estado`)
VALUES (2, 3, 2, 300, 1, 'procesando');
INSERT INTO `pedidos` (`cliente_fk`, `producto_fk`, `factura_fk`, `precio_venta`, `cantidad_prod`, `estado`)
VALUES (3, 2, 3, 800, 3, 'enviado');
INSERT INTO `pedidos` (`cliente_fk`, `producto_fk`, `factura_fk`, `precio_venta`, `cantidad_prod`, `estado`)
VALUES (4, 5, 4, 200, 1, 'entregado');
