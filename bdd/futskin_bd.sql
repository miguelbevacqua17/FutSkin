-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-futskin.alwaysdata.net
-- Generation Time: Dec 07, 2023 at 06:42 AM
-- Server version: 10.6.14-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `futskin_bd`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `imagen`) VALUES
(1, 'River', 'Encontrá en nuestra tienda online todo lo que necesitás para demostrar la grandeza de formar parte de la hichada de River. Camisetas, camperas, buzos, shorts pantalones medias y más', 'river.png'),
(2, 'Boca', 'La camiseta de Boca es más que una simple indumentaria deportiva. Es el tesoro de cada hincha que pide a sus jugadores que la transpiren hasta la última gota de sudor', 'boca.png'),
(3, 'San Lorenzo', 'La sensación nunca cambia: llevar la azulgrana sobre la piel supone un privilegio intransferible. Y ciertamente inolvidable...', 'san-lorenzo.png'),
(4, 'Independiente', 'Independiente se ganó el mote de Rey de Copas por su dominio histórico en las copas internacionales. Camisetas, shorts, y todo para el hincha del Rojo', 'independiente.png'),
(5, 'Racing', 'Encontrá la indumentaria de La Academia para hinchar por el primer grande.', 'racing.png');

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `envio_fk` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `envio_fk`, `nombre`, `apellido`, `email`, `contrasena`, `rol`) VALUES
(1, 1, 'María', 'González', 'maria@gmail.com', '1234', 1),
(2, 2, 'Carlos Hugo', 'Martínez', 'carlos@gmail.com', '1234', NULL),
(3, 3, 'Laura', 'Rodríguez', 'laura@gmail.com', '1234', NULL),
(4, 4, 'Juan', 'Pérez', 'juan@gmail.com', '1234', NULL),
(5, 5, 'Ana', 'López', 'ana@gmail.com', '1234', NULL),
(7, 6, 'Lucho', 'Pedemonte', 'luchop@gmail.com', '1234', 1),
(17, NULL, 'Miguel', 'Bevacqua', 'miguelbevacqua@gmail.com', '1234', 1),
(18, NULL, 'Miguel', 'Bevacqua', 'miguel@gmail.com', '1234', NULL),
(19, 7, 'Augusto', 'Laprovitta', 'alaprovitta@gmail.com', '1234', NULL),
(20, NULL, 'Martin', 'Apellido', 'martin@gmail.com', '1234', NULL),
(21, 8, 'Martin', 'Apellido', 'martin1@gmail.com', '1234', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `envios`
--

CREATE TABLE `envios` (
  `id` int(11) NOT NULL,
  `barrio` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `altura` varchar(255) DEFAULT NULL,
  `piso` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `envios`
--

INSERT INTO `envios` (`id`, `barrio`, `direccion`, `altura`, `piso`) VALUES
(1, 'Palermo', 'Avenida Santa Fe', '1234', '2'),
(2, 'Belgrano', 'Calle Cabildo', '8888', '22'),
(3, 'Recoleta', 'Avenida Alvear', '910', '1'),
(4, 'San Telmo', 'Calle Defensa', '4321', '4'),
(5, 'La Boca', 'Calle Caminito', '1000', '2'),
(6, 'Retiro', 'Av. Santa Fe', '200', '7'),
(7, 'Puerto Madero', 'Alicia Moreau', '1300', '2'),
(8, 'Puerto Madero', 'Alicia Moreau ', '1111', '2');

-- --------------------------------------------------------

--
-- Table structure for table `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `total_a_pagar` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facturas`
--

INSERT INTO `facturas` (`id`, `total_a_pagar`) VALUES
(1, 200),
(2, 400),
(3, 600),
(4, 800),
(5, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_fk` int(11) DEFAULT NULL,
  `producto_fk` int(11) DEFAULT NULL,
  `factura_fk` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,0) DEFAULT NULL,
  `cantidad_prod` int(11) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_fk`, `producto_fk`, `factura_fk`, `precio_venta`, `cantidad_prod`, `estado`) VALUES
(67, 17, 51, NULL, 47999, 1, 'pendiente'),
(68, 17, 52, NULL, 40999, 1, 'pendiente'),
(69, 17, 54, NULL, 65999, 1, 'eliminado'),
(70, 1, 59, NULL, 39999, 1, 'eliminado'),
(71, 1, 63, NULL, 17999, 1, 'venta'),
(72, 2, 64, NULL, 32999, 1, 'venta'),
(73, 2, 65, NULL, 30999, 1, 'venta'),
(74, 2, 66, NULL, 30999, 1, 'eliminado'),
(75, 3, 69, NULL, 28999, 1, 'venta'),
(76, 4, 45, NULL, 47999, 1, 'venta'),
(77, 4, 46, NULL, 47999, 1, 'venta'),
(78, 4, 48, NULL, 26999, 1, 'venta'),
(85, 7, 45, NULL, 47999, 1, 'pendiente'),
(86, 17, 54, NULL, 65999, 1, 'pendiente'),
(87, NULL, 63, NULL, 17999, 1, 'pendiente'),
(88, 2, 46, NULL, 47999, 1, 'venta'),
(89, 18, 51, NULL, 47999, 1, 'eliminado'),
(90, 18, 52, NULL, 40999, 1, 'pendiente'),
(91, NULL, 62, NULL, 28999, 1, 'pendiente'),
(92, NULL, 62, NULL, 28999, 1, 'pendiente'),
(93, 19, 46, NULL, 47999, 1, 'eliminado'),
(94, 19, 46, NULL, 47999, 3, 'venta'),
(95, 19, 46, NULL, 47999, 1, 'venta'),
(96, 19, 45, NULL, 47999, 1, 'venta'),
(97, 5, 45, NULL, 47999, 1, 'venta'),
(98, 1, 46, NULL, 47999, 1, 'venta'),
(99, 20, 52, NULL, 40999, 1, 'eliminado'),
(100, NULL, 46, NULL, 47999, 1, 'pendiente'),
(101, 21, 56, NULL, 39999, 1, 'venta'),
(102, 21, 52, NULL, 40999, 1, 'eliminado'),
(103, 7, 72, NULL, 46999, 1, 'pendiente');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `categoria_fk` int(11) DEFAULT NULL,
  `producto` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `descripcion` varchar(510) DEFAULT NULL,
  `precio_lista` decimal(10,0) DEFAULT NULL,
  `descuento` decimal(10,0) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `deleteable` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `categoria_fk`, `producto`, `imagen`, `descripcion`, `precio_lista`, `descuento`, `stock`, `deleteable`) VALUES
(45, 1, 'Camiseta Titular River Plate', 'ae0a92f46f3104f7d50b9fe821e7b462.jpeg', 'Esta nueva camiseta oficial de River Plate adidas muestra elegancia en su diseño con una banda roja que le cruza simulando aquellos uniformes de los inicios del Siglo XX. Un diseño sobrio para un equipo que se supera cada día.', 47999, 0, 1000, 0),
(46, 1, 'Camiseta Alternativa River Plate', '1fb08b12c01f315581b690b83b9bb315.jpeg', 'Reinterpretación del icónico diseño alternativo que recuerda los éxitos del Millonario en la decáda del ´90.', 47999, 0, 1000, 0),
(47, 1, 'Camiseta Tercer Uniforme River Plate', '778df273529dc4f86fc3d501f037cbcb.jpeg', 'La favorita de los hinchas vuelve a las canchas. Con la característica banda roja de la camiseta titular de River Plate, esta camiseta del tercer uniforme de adidas está inspirada en un look conocido y adorado.', 40999, 0, 1000, 0),
(48, 1, 'Short Uniforme Titular River Plate', 'e6124b668d69515bfaab31de89937993.jpeg', 'Alentá a River Plate desde la tribuna o desde donde sea con este short de fútbol que complementa el conjunto titular.', 26999, 0, 1000, 0),
(49, 1, 'Short Uniforme Alternativo River Plate', '3f3c438926285875ca1fc39e1744c222.jpeg', 'Rindiendo homenaje a las leyendas del pasado, estos shorts son parte del uniforme alternativo de River Plate.', 24999, 0, 1000, 0),
(51, 2, 'Camiseta Titular Boca Juniors', 'bb10ace2ab6884f5de4fd688e33c52b3.webp', 'Esta nueva camiseta titular de Boca Juniors adidas une la pasión y el barrio con sus colores, el azul y oro.', 47999, 0, 1000, 0),
(52, 2, 'Camiseta Tercer Uniforme Boca Juniors', 'a3c5a5ace392be491152f6f8cdde4aac.jpg', 'Una conexión con su hogar. Los estampados dinámicos en el frente de esta camiseta de Boca Juniors adidas de fútbol se inspira en las vigas del puente de La Boca.', 40999, 0, 1000, 0),
(53, 2, 'Short Boca Juniors', '6d8c7e73d03e197aafe3782fe4a2de61.jpeg', 'Completá el uniforme titular del xeneize. Estos shorts de fútbol de Boca Juniors adidas son los que usan los jugadores cuando juegan en La Bombonera. ', 23999, 0, 1000, 0),
(54, 2, 'Campera Boca Juniors', '1ab26eada46e76d4f023958eee007f04.jpeg', 'Demostrá que sos de Boca Juniors sin importar el clima.', 65999, 0, 1000, 0),
(55, 2, 'Camiseta Sin Mangas Boca', '2388d005207f702fbc3116aed9f9c9fa.webp', 'Entrená como tus héroes. Esta camiseta sin mangas de fútbol adidas luce los colores históricos de Boca Juniors.', 28999, 0, 1000, 0),
(56, 3, 'Camiseta Titular San Lorenzo', '0062d5bd1a4a5617c067206cff4b044d.jpeg', 'Lucite en la cancha con los colores más lindos y llevá tu pasión a todas partes con este nuevo modelo.', 39999, 0, 1000, 0),
(59, 3, 'Camiseta Alternativa San Lorenzo', 'a2b796ced51c6b360f31c47acb8b14c1.webp', 'Lucite en la cancha con los colores más lindos y llevá tu pasión a todas partes con este nuevo modelo.', 39999, 0, 1000, 0),
(60, 3, 'Short Stadium San Lorenzo Blanco', '27b2fec798fc0447a5c0748c939f733a.webp', 'Completa tu uniforme de campo con los pantalones cortos San Lorenzo 3ra 2023.', 28999, 0, 1000, 0),
(62, 3, 'Short Stadium San Lorenzo ', '9c6b5119afde63d5d6b6c468112ddad7.jpg', 'Completa tu uniforme de campo con los pantalones cortos San Lorenzo 3ra 2023.', 28999, 0, 1000, 0),
(63, 3, 'Pelota Dribbling San Lorenzo', '351c4a6d2b5a28b6f482640f497624f7.jpg', 'Realizá las mejores jugadas y disfrutá de un partido con tus amigos con la Pelota Dribbling San Lorenzo .', 17999, 0, 1000, 0),
(64, 4, 'Camiseta Titular Independiente', '9a724bdff6b1400aa206fb02ad7e2bba.jpeg', 'La camiseta titular del Club Atlético Independiente es tu mejor opción para vestir en cada partido de futbol.', 32999, 0, 1000, 0),
(65, 4, 'Camiseta Alternativa Independiente', '468b2bb30065220e3e2c70a082cea3fb.jpeg', 'La camiseta oficial alternativa del Club Atlético Independiente es tu mejor opción para vestir en cada partido de futbol.', 30999, 0, 1000, 0),
(66, 4, 'Camiseta Arquero Independiente', 'd26b0996af686918bed2fb40de6b30fc.jpeg', 'La nueva Camiseta Puma Club Atlético Independiente Arquero te hará destacar en la portería como nunca antes!', 30999, 0, 1000, 0),
(67, 4, 'Pelota Dribbling Independiente', '38bf5e4a93113390a03171bedfde25a4.jpg', 'Realizá las mejores jugadas y disfrutá de un partido con tus amigos con la Pelota Dribbling Independiente Mundial 2.0', 17999, 0, 1000, 0),
(68, 5, 'Camiseta Titular Racing', 'd92996558c4fe694257b829b327ac260.jpg', 'Camiseta titular para hinchar por La Academia', 28999, 0, 1000, 0),
(69, 5, 'Camiseta Alternativa Racing', 'd9398c13c6e0a19651824addef8fd8bb.jpeg', 'Camiseta suplente Racing Club', 28999, 0, 1000, 0),
(70, 5, 'Short Deportivo Racing Club', '063205ea987216384288ec8b372dee86.jpg', 'Te presentamos el short titular de Racing.', 21999, 0, 1000, 0),
(71, 5, 'Short Alternativo Racing Club', 'b8a102003698797d7c29278188053ab4.jpeg', 'Te presentamos el short alternativo de Racing Club.', 21999, 0, 1000, 0),
(72, 1, 'Camiseta River', 'd42b864bac3a6f61da4e536a5460b1da.jpeg', 'Ejemplo descripción', 46999, 10, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `envio_fk` (`envio_fk`);

--
-- Indexes for table `envios`
--
ALTER TABLE `envios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_fk` (`cliente_fk`),
  ADD KEY `factura_fk` (`factura_fk`),
  ADD KEY `producto_fk` (`producto_fk`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_fk` (`categoria_fk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `envios`
--
ALTER TABLE `envios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`envio_fk`) REFERENCES `envios` (`id`);

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_fk`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`factura_fk`) REFERENCES `facturas` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`id`);

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_fk`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
