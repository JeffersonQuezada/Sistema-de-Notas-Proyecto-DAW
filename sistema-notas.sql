/*
SQLyog Community v13.1.9 (64 bit)
MySQL - 10.4.32-MariaDB : Database - gestion_notas
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gestion_notas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `gestion_notas`;

/*Table structure for table `actividades` */

DROP TABLE IF EXISTS `actividades`;

CREATE TABLE `actividades` (
  `id_actividad` int(11) NOT NULL AUTO_INCREMENT,
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_limite` datetime NOT NULL,
  `tipo` enum('Tarea','Examen','Proyecto') NOT NULL,
  PRIMARY KEY (`id_actividad`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `actividades` */

/*Table structure for table `asistencias` */

DROP TABLE IF EXISTS `asistencias`;

CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `id_curso` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('Presente','Ausente','Justificado') NOT NULL,
  `registrado_por` int(11) NOT NULL,
  PRIMARY KEY (`id_asistencia`),
  KEY `id_curso` (`id_curso`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `registrado_por` (`registrado_por`),
  CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE,
  CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `asistencias_ibfk_3` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `asistencias` */

/*Table structure for table `cursos` */

DROP TABLE IF EXISTS `cursos`;

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_curso` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_docente` int(11) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `capacidad` int(11) NOT NULL DEFAULT 50,
  `grupo` enum('A','B') DEFAULT NULL,
  PRIMARY KEY (`id_curso`),
  KEY `id_docente` (`id_docente`),
  CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cursos` */

/*Table structure for table `entregas` */

DROP TABLE IF EXISTS `entregas`;

CREATE TABLE `entregas` (
  `id_entrega` int(11) NOT NULL AUTO_INCREMENT,
  `id_actividad` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `fecha_entrega` timestamp NOT NULL DEFAULT current_timestamp(),
  `comentario` text DEFAULT NULL,
  PRIMARY KEY (`id_entrega`),
  KEY `id_actividad` (`id_actividad`),
  KEY `id_estudiante` (`id_estudiante`),
  CONSTRAINT `entregas_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id_actividad`) ON DELETE CASCADE,
  CONSTRAINT `entregas_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `entregas` */

/*Table structure for table `estudiantes_cursos` */

DROP TABLE IF EXISTS `estudiantes_cursos`;

CREATE TABLE `estudiantes_cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `grupo` enum('A','B') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `estudiantes_cursos_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `estudiantes_cursos_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `estudiantes_cursos` */

/*Table structure for table `horarios` */

DROP TABLE IF EXISTS `horarios`;

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL AUTO_INCREMENT,
  `id_curso` int(11) NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `horarios` */

/*Table structure for table `notas` */

DROP TABLE IF EXISTS `notas`;

CREATE TABLE `notas` (
  `id_nota` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  `nota` decimal(5,2) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_nota`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_curso` (`id_curso`),
  KEY `notas_ibfk_3` (`id_actividad`),
  CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE,
  CONSTRAINT `notas_ibfk_3` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id_actividad`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `notas` */

/*Table structure for table `notificaciones` */

DROP TABLE IF EXISTS `notificaciones`;

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `tipo` enum('Sistema','Curso','Mensaje') NOT NULL,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `notificaciones` */

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('docente','estudiante','admin') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuarios` */

insert  into `usuarios`(`id_usuario`,`nombre`,`correo`,`contrasena`,`rol`,`fecha_registro`) values 
(1,'Jefferson Quezada','queza@gmail.com','$2y$10$5mWn7rj3y3t7Hh9pDocRp.U.twOmQI1o..wydq/mNQyc7Fv1AoB9y','estudiante','2025-04-29 19:20:27'),
(2,'Jefferson','jeff@gmail.com','$2y$10$WZEM1sOVkMYcDDS/0Guise074xuFErWSk/F9.9NpLH6KIo1oWODKO','estudiante','2025-05-01 19:42:18'),
(3,'Administrador','admin@gmail.com','$2y$10$HycAleooD1VVOxcKDrBuMe3cJLGSAPXkyoog9tAUf6l7AJUfCHTE.','admin','2025-05-07 20:22:31');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
