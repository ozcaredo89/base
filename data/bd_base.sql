-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-09-2016 a las 03:19:59
-- Versión del servidor: 10.1.13-MariaDB
-- Versión de PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_base`
--
CREATE DATABASE IF NOT EXISTS `bd_base` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bd_base`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_par_estados`
--

DROP TABLE IF EXISTS `tb_par_estados`;
CREATE TABLE IF NOT EXISTS `tb_par_estados` (
  `esta_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `esta_descripcion` varchar(20) NOT NULL,
  PRIMARY KEY (`esta_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_par_estados`
--

INSERT INTO `tb_par_estados` (`esta_codigo`, `esta_descripcion`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_seg_modulos`
--

DROP TABLE IF EXISTS `tb_seg_modulos`;
CREATE TABLE IF NOT EXISTS `tb_seg_modulos` (
  `modu_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `modu_descripcion` varchar(50) NOT NULL,
  `modu_icono` varchar(20) NOT NULL,
  `fk_par_estados` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`modu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_seg_modulos`
--

INSERT INTO `tb_seg_modulos` (`modu_codigo`, `modu_descripcion`, `modu_icono`, `fk_par_estados`) VALUES
(1, 'Seguridad', 'shield', 1),
(2, 'Parametros', 'sliders', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_seg_opciones`
--

DROP TABLE IF EXISTS `tb_seg_opciones`;
CREATE TABLE IF NOT EXISTS `tb_seg_opciones` (
  `opci_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `fk_seg_modulos` int(11) NOT NULL,
  `opci_nombre` varchar(20) NOT NULL,
  `opci_enlace` varchar(100) NOT NULL,
  `fk_par_estados` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`opci_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_seg_opciones`
--

INSERT INTO `tb_seg_opciones` (`opci_codigo`, `fk_seg_modulos`, `opci_nombre`, `opci_enlace`, `fk_par_estados`) VALUES
(1001, 1, 'Perfiles', '../vista/interfaces/Seguridad/Perfiles.html', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_seg_perfiles`
--

DROP TABLE IF EXISTS `tb_seg_perfiles`;
CREATE TABLE IF NOT EXISTS `tb_seg_perfiles` (
  `perf_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `perf_descripcion` varchar(50) NOT NULL,
  `fk_par_estados` int(11) NOT NULL DEFAULT '1',
  `perf_fc` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `perf_uc` int(11) NOT NULL,
  `perf_fm` datetime DEFAULT NULL,
  `perf_um` int(11) DEFAULT NULL,
  PRIMARY KEY (`perf_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_seg_perfiles`
--

INSERT INTO `tb_seg_perfiles` (`perf_codigo`, `perf_descripcion`, `fk_par_estados`, `perf_fc`, `perf_uc`, `perf_fm`, `perf_um`) VALUES
(1, 'root', 1, '2016-08-19 10:25:28', 1, NULL, NULL),
(2, 'Administrador', 1, '2016-08-19 11:26:38', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_seg_permisos`
--

DROP TABLE IF EXISTS `tb_seg_permisos`;
CREATE TABLE IF NOT EXISTS `tb_seg_permisos` (
  `perm_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `fk_seg_perfiles` int(11) NOT NULL,
  `fk_seg_opciones` int(11) NOT NULL,
  `fk_par_estados` int(11) NOT NULL DEFAULT '1',
  `perm_lectura` int(11) NOT NULL DEFAULT '0',
  `perm_escritura` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`perm_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_seg_permisos`
--

INSERT INTO `tb_seg_permisos` (`perm_codigo`, `fk_seg_perfiles`, `fk_seg_opciones`, `fk_par_estados`, `perm_lectura`, `perm_escritura`) VALUES
(1, 1, 1001, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_seg_usuarios`
--

DROP TABLE IF EXISTS `tb_seg_usuarios`;
CREATE TABLE IF NOT EXISTS `tb_seg_usuarios` (
  `usua_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `usua_nombre` varchar(50) NOT NULL,
  `usua_mail` varchar(100) NOT NULL,
  `usua_login` varchar(20) NOT NULL,
  `usua_password` varchar(50) NOT NULL,
  `fk_seg_perfiles` int(11) NOT NULL,
  `fk_par_estados` int(11) NOT NULL DEFAULT '1',
  `usua_fc` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usua_uc` int(11) NOT NULL,
  `usua_fm` datetime DEFAULT NULL,
  `usua_um` int(11) DEFAULT NULL,
  PRIMARY KEY (`usua_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_seg_usuarios`
--

INSERT INTO `tb_seg_usuarios` (`usua_codigo`, `usua_nombre`, `usua_mail`, `usua_login`, `usua_password`, `fk_seg_perfiles`, `fk_par_estados`, `usua_fc`, `usua_uc`, `usua_fm`, `usua_um`) VALUES
(1, 'Usuario Root', '', 'root', '202cb962ac59075b964b07152d234b70', 1, 1, '2016-08-13 19:44:52', 0, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
