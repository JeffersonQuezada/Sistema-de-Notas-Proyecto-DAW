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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `actividades` */

insert  into `actividades`(`id_actividad`,`id_curso`,`nombre`,`descripcion`,`fecha_limite`,`tipo`) values 
(1,1,'Examen Parcial','Primer examen parcial','2025-06-15 10:00:00','Examen'),
(2,1,'Examen Parcial 1','Evaluación de álgebra básica','2025-06-15 10:00:00','Examen'),
(3,1,'Tarea 1','Ejercicios de ecuaciones lineales','2025-06-01 23:59:00','Tarea'),
(4,1,'Proyecto Final','Aplicación de geometría analítica','2025-07-15 23:59:00','Proyecto'),
(5,2,'Examen Parcial 1','Evaluación de álgebra básica','2025-06-16 14:00:00','Examen'),
(6,2,'Tarea 1','Ejercicios de ecuaciones lineales','2025-06-02 23:59:00','Tarea'),
(7,3,'Laboratorio 1','Práctica de mecánica','2025-06-10 16:00:00','Tarea'),
(8,3,'Examen Parcial','Evaluación de mecánica clásica','2025-06-20 14:00:00','Examen'),
(9,3,'Proyecto de Investigación','Estudio de termodinámica','2025-07-10 23:59:00','Proyecto'),
(10,4,'Examen 1','Nomenclatura orgánica','2025-06-12 10:00:00','Examen'),
(11,4,'Laboratorio 1','Síntesis orgánica básica','2025-06-05 10:00:00','Tarea'),
(12,5,'Tarea 1','Algoritmos básicos en Python','2025-05-30 23:59:00','Tarea'),
(13,5,'Proyecto 1','Programa de gestión simple','2025-06-25 23:59:00','Proyecto'),
(14,5,'Examen Práctico','Evaluación de programación','2025-06-18 16:00:00','Examen'),
(15,6,'Tarea 1','Algoritmos básicos en Python','2025-05-31 23:59:00','Tarea'),
(16,6,'Proyecto 1','Programa de gestión simple','2025-06-26 23:59:00','Proyecto'),
(17,7,'Ensayo 1','Análisis de la Edad Media','2025-06-08 23:59:00','Tarea'),
(18,7,'Examen Parcial','Evaluación histórica','2025-06-22 10:00:00','Examen'),
(19,8,'Análisis Literario','Comentario de texto del Quijote','2025-06-14 23:59:00','Tarea'),
(20,8,'Examen Final','Evaluación del Siglo de Oro','2025-07-05 10:00:00','Examen'),
(21,11,'Jhon Wick 5','holajjj','2025-06-18 16:25:00','Examen');

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `asistencias` */

insert  into `asistencias`(`id_asistencia`,`id_curso`,`id_estudiante`,`fecha`,`estado`,`registrado_por`) values 
(1,1,1,'2025-05-19','Presente',4),
(2,1,5,'2025-05-19','Presente',4),
(3,1,7,'2025-05-19','Presente',4),
(4,1,10,'2025-05-19','Ausente',4),
(5,1,1,'2025-05-21','Presente',4),
(6,1,5,'2025-05-21','Presente',4),
(7,1,7,'2025-05-21','Justificado',4),
(8,1,10,'2025-05-21','Presente',4),
(9,5,1,'2025-05-19','Presente',7),
(10,5,6,'2025-05-19','Presente',7),
(11,5,8,'2025-05-19','Presente',7),
(12,5,1,'2025-05-21','Presente',7),
(13,5,6,'2025-05-21','Ausente',7),
(14,5,8,'2025-05-21','Presente',7);

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cursos` */

insert  into `cursos`(`id_curso`,`nombre_curso`,`descripcion`,`id_docente`,`contrasena`,`capacidad`,`grupo`) values 
(1,'Matematica 1','Matemática 1 generalmente cubre una amplia gama de temas, incluyendo álgebra, geometría, estadística, y a veces funciones y modelos. Se enfoca en conceptos fundamentales como números reales, álgebra básica, geometría plana, y estadística descriptiva.',5,'$2y$10$avUBqsO2obWaqkN0Gcc..u1nyfuFTkCDLuvFXg7zqyQzRP2OIrVhe',50,NULL),
(2,'Matemáticas I','Curso básico de matemáticas',3,'password123',30,NULL),
(3,'Matemáticas I','Álgebra básica y geometría analítica',4,'mat2025',30,'A'),
(4,'Matemáticas I','Álgebra básica y geometría analítica',4,'mat2025b',30,'B'),
(5,'Física General','Mecánica clásica y termodinámica',5,'fis2025',25,'A'),
(6,'Química Orgánica','Fundamentos de química orgánica',6,'quim2025',20,'A'),
(7,'Programación I','Introducción a la programación con Python',7,'prog2025',35,'A'),
(8,'Programación I','Introducción a la programación con Python',7,'prog2025b',35,'B'),
(9,'Historia Universal','Historia desde la antigüedad hasta el siglo XX',4,'hist2025',40,'A'),
(10,'Literatura Española','Literatura del Siglo de Oro',5,'lit2025',25,'A'),
(11,'Ejemplo','emplo para pruebas',20,'$2y$10$BtMipT3wfaMTWQ8dMiBFyucbb12g6pIIX8yZPZO.fydQZ.2Im0XvC',40,NULL);

/*Table structure for table `docentes` */

DROP TABLE IF EXISTS `docentes`;

CREATE TABLE `docentes` (
  `id_docente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mfa_codigo` varchar(10) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id_docente`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `docentes` */

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `estudiantes_cursos` */

insert  into `estudiantes_cursos`(`id`,`id_estudiante`,`id_curso`,`grupo`) values 
(1,1,1,NULL),
(2,1,1,'A'),
(3,1,3,'A'),
(4,1,5,'A'),
(5,2,2,'B'),
(6,2,4,'A'),
(7,2,6,'B'),
(8,5,1,'A'),
(9,5,7,'A'),
(10,5,8,'A'),
(11,6,2,'B'),
(12,6,3,'A'),
(13,6,5,'A'),
(14,7,4,'A'),
(15,7,7,'A'),
(16,7,1,'A'),
(17,8,5,'A'),
(18,8,6,'B'),
(19,8,3,'A'),
(20,9,8,'A'),
(21,9,7,'A'),
(22,9,2,'B'),
(23,10,1,'A'),
(24,10,4,'A'),
(25,10,6,'B');

/*Table structure for table `estudiantes_grupos` */

DROP TABLE IF EXISTS `estudiantes_grupos`;

CREATE TABLE `estudiantes_grupos` (
  `id_usuario` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_grupo`),
  KEY `id_grupo` (`id_grupo`),
  CONSTRAINT `estudiantes_grupos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `estudiantes_grupos_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `estudiantes_grupos` */

/*Table structure for table `grupos` */

DROP TABLE IF EXISTS `grupos`;

CREATE TABLE `grupos` (
  `id_grupo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `id_docente` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_grupo`),
  KEY `id_docente` (`id_docente`),
  CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  CONSTRAINT `grupos_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `grupos` */

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `horarios` */

insert  into `horarios`(`id_horario`,`id_curso`,`dia_semana`,`hora_inicio`,`hora_fin`) values 
(1,1,'Lunes','08:00:00','10:00:00'),
(2,1,'Miércoles','08:00:00','10:00:00'),
(3,1,'Viernes','08:00:00','10:00:00'),
(4,2,'Martes','10:00:00','12:00:00'),
(5,2,'Jueves','10:00:00','12:00:00'),
(6,3,'Lunes','14:00:00','16:00:00'),
(7,3,'Miércoles','14:00:00','16:00:00'),
(8,4,'Martes','08:00:00','10:00:00'),
(9,4,'Jueves','08:00:00','10:00:00'),
(10,5,'Lunes','16:00:00','18:00:00'),
(11,5,'Miércoles','16:00:00','18:00:00'),
(12,5,'Viernes','16:00:00','18:00:00'),
(13,6,'Martes','14:00:00','16:00:00'),
(14,6,'Jueves','14:00:00','16:00:00'),
(15,6,'Sábado','08:00:00','12:00:00'),
(16,7,'Viernes','10:00:00','12:00:00'),
(17,7,'Sábado','14:00:00','16:00:00'),
(18,8,'Miércoles','10:00:00','12:00:00'),
(19,8,'Viernes','14:00:00','16:00:00');

/*Table structure for table `insignias` */

DROP TABLE IF EXISTS `insignias`;

CREATE TABLE `insignias` (
  `id_insignia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_insignia`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `insignias` */

insert  into `insignias`(`id_insignia`,`nombre`,`descripcion`,`imagen`) values 
(1,'Primera Entrega','Completaste tu primera entrega','badge1.png'),
(2,'Buen Promedio','Obtuviste un promedio mayor a 8.5','badge2.png'),
(3,'Puntual','Entregaste 10 actividades a tiempo','badge3.png');

/*Table structure for table `insignias_estudiantes` */

DROP TABLE IF EXISTS `insignias_estudiantes`;

CREATE TABLE `insignias_estudiantes` (
  `id_insignia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_insignia`,`id_usuario`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `insignias_estudiantes_ibfk_1` FOREIGN KEY (`id_insignia`) REFERENCES `insignias` (`id_insignia`),
  CONSTRAINT `insignias_estudiantes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `insignias_estudiantes` */

/*Table structure for table `misiones` */

DROP TABLE IF EXISTS `misiones`;

CREATE TABLE `misiones` (
  `id_mision` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `recompensa` varchar(50) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_mision`),
  KEY `id_grupo` (`id_grupo`),
  CONSTRAINT `misiones_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `misiones` */

insert  into `misiones`(`id_mision`,`titulo`,`descripcion`,`recompensa`,`id_grupo`) values 
(1,'Primeros Pasos','Completa tu primer curso','100 puntos',NULL),
(2,'Estrella Naciente','Obtén una calificación de 90+','150 puntos',NULL);

/*Table structure for table `misiones_estudiantes` */

DROP TABLE IF EXISTS `misiones_estudiantes`;

CREATE TABLE `misiones_estudiantes` (
  `id_mision` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `completado` tinyint(1) DEFAULT NULL,
  `fecha_aceptacion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_mision`,`id_usuario`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `misiones_estudiantes_ibfk_1` FOREIGN KEY (`id_mision`) REFERENCES `misiones` (`id_mision`),
  CONSTRAINT `misiones_estudiantes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `misiones_estudiantes` */

/*Table structure for table `notas` */

DROP TABLE IF EXISTS `notas`;

CREATE TABLE `notas` (
  `id_nota` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  `id_entrega` int(11) DEFAULT NULL,
  `nota` decimal(5,2) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_nota`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_curso` (`id_curso`),
  KEY `notas_ibfk_3` (`id_actividad`),
  KEY `fk_entrega` (`id_entrega`),
  CONSTRAINT `fk_entrega` FOREIGN KEY (`id_entrega`) REFERENCES `entregas` (`id_entrega`),
  CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE,
  CONSTRAINT `notas_ibfk_3` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id_actividad`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `notas` */

insert  into `notas`(`id_nota`,`id_estudiante`,`id_curso`,`id_actividad`,`id_entrega`,`nota`,`observaciones`,`fecha_registro`) values 
(1,1,1,1,NULL,85.50,'Buen desempeño','2025-05-21 19:26:21'),
(2,1,1,1,NULL,85.50,'Buen desempeño en álgebra','2025-05-21 19:30:52'),
(3,1,1,2,NULL,92.00,'Excelentes ejercicios resueltos','2025-05-21 19:30:52'),
(4,1,3,6,NULL,78.75,'Necesita mejorar en laboratorio','2025-05-21 19:30:52'),
(5,1,5,11,NULL,88.00,'Código bien estructurado','2025-05-21 19:30:52'),
(6,1,5,13,NULL,91.50,'Excelente dominio de Python','2025-05-21 19:30:52'),
(7,2,2,4,NULL,76.25,'Rendimiento promedio','2025-05-21 19:30:52'),
(8,2,2,5,NULL,83.50,'Mejora progresiva','2025-05-21 19:30:52'),
(9,2,4,9,NULL,89.00,'Muy buen conocimiento teórico','2025-05-21 19:30:52'),
(10,2,6,14,NULL,87.75,'Buenas prácticas de programación','2025-05-21 19:30:52'),
(11,5,1,1,NULL,94.00,'Excelente comprensión matemática','2025-05-21 19:30:52'),
(12,5,1,2,NULL,96.50,'Trabajo excepcional','2025-05-21 19:30:52'),
(13,5,7,15,NULL,91.00,'Análisis histórico profundo','2025-05-21 19:30:52'),
(14,5,8,17,NULL,93.25,'Excelente interpretación literaria','2025-05-21 19:30:52'),
(15,6,2,4,NULL,82.00,'Buen rendimiento general','2025-05-21 19:30:52'),
(16,6,3,6,NULL,85.50,'Mejora en práctica de laboratorio','2025-05-21 19:30:52'),
(17,6,5,11,NULL,79.25,'Necesita práctica adicional','2025-05-21 19:30:52'),
(18,7,4,9,NULL,92.75,'Excelente en química teórica','2025-05-21 19:30:52'),
(19,7,4,10,NULL,88.50,'Buena técnica de laboratorio','2025-05-21 19:30:52'),
(20,7,7,15,NULL,86.00,'Buen análisis histórico','2025-05-21 19:30:52'),
(21,7,1,1,NULL,90.25,'Sólidos conocimientos matemáticos','2025-05-21 19:30:52'),
(22,8,5,11,NULL,95.00,'Programador excepcional','2025-05-21 19:30:52'),
(23,8,5,13,NULL,97.50,'Dominio avanzado del lenguaje','2025-05-21 19:30:52'),
(24,8,3,6,NULL,84.00,'Buen trabajo experimental','2025-05-21 19:30:52'),
(25,9,8,17,NULL,89.75,'Buena comprensión literaria','2025-05-21 19:30:52'),
(26,9,7,15,NULL,92.50,'Excelente perspectiva histórica','2025-05-21 19:30:52'),
(27,9,2,4,NULL,81.25,'Rendimiento satisfactorio','2025-05-21 19:30:52'),
(28,10,1,1,NULL,87.50,'Buen nivel matemático','2025-05-21 19:30:52'),
(29,10,4,9,NULL,90.00,'Sólidos fundamentos químicos','2025-05-21 19:30:52'),
(30,10,6,14,NULL,86.25,'Buenas habilidades de programación','2025-05-21 19:30:52');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `notificaciones` */

insert  into `notificaciones`(`id_notificacion`,`id_usuario`,`titulo`,`mensaje`,`fecha`,`leida`,`tipo`) values 
(1,1,'Nuevo Examen Programado','Se ha programado el examen parcial de Matemáticas I para el 15 de junio a las 10:00 AM','2025-05-21 19:30:52',0,'Curso'),
(2,1,'Tarea Pendiente','Recuerda entregar la tarea de Programación I antes del 30 de mayo','2025-05-21 19:30:52',1,'Curso'),
(3,2,'Bienvenido al Sistema','Te damos la bienvenida al sistema de gestión de notas','2025-05-21 19:30:52',1,'Sistema'),
(4,5,'Calificación Disponible','Ya está disponible tu calificación del ensayo de Historia Universal','2025-05-21 19:30:52',0,'Curso'),
(5,6,'Recordatorio de Clase','La clase de Física General de mañana será en el laboratorio','2025-05-21 19:30:52',0,'Curso'),
(6,8,'Felicitaciones','Excelente desempeño en el examen práctico de Programación','2025-05-21 19:30:52',1,'Curso'),
(7,4,'Estudiante Inscrito','Un nuevo estudiante se ha inscrito en tu curso de Matemáticas I','2025-05-21 19:30:52',1,'Curso'),
(8,7,'Sistema Actualizado','El sistema ha sido actualizado con nuevas funcionalidades','2025-05-21 19:30:52',0,'Sistema');

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('docente','estudiante','admin') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `primer_login` tinyint(1) DEFAULT 0,
  `mfa_codigo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuarios` */

insert  into `usuarios`(`id_usuario`,`nombre`,`correo`,`contrasena`,`rol`,`fecha_registro`,`primer_login`,`mfa_codigo`) values 
(1,'Jefferson Quezada','queza@gmail.com','$2y$10$5mWn7rj3y3t7Hh9pDocRp.U.twOmQI1o..wydq/mNQyc7Fv1AoB9y','estudiante','2025-04-29 19:20:27',0,NULL),
(2,'Jefferson','jeff@gmail.com','$2y$10$WZEM1sOVkMYcDDS/0Guise074xuFErWSk/F9.9NpLH6KIo1oWODKO','estudiante','2025-05-01 19:42:18',0,NULL),
(3,'Administrador','admin@gmail.com','$2y$10$HycAleooD1VVOxcKDrBuMe3cJLGSAPXkyoog9tAUf6l7AJUfCHTE.','admin','2025-05-07 20:22:31',0,NULL),
(4,'Maybeline Rosmeri','rosmery@gmail.com','$2y$10$dopf67l5whiqnioIrap.T.R6Z2QmLETglLu61CuSPo63YaLawV8PW','estudiante','2025-05-08 09:46:43',0,NULL),
(5,'Carlos Manuel','carlos@gmail.com','$2y$10$8zZ69XIhzPkoOompq73K.ejg9w9VxoHUtCQ1JO0GJZ1w6TD7O8dni','docente','2025-05-08 10:42:02',0,NULL),
(6,'Jefferson','qjeffer@gmail.com','$2y$10$hhu0MSdfPFiIFml8GS00be1yVdwMK4CQyZQ066Zjm7rbpuqA5EBXy','estudiante','2025-05-17 19:18:15',0,NULL),
(7,'Jefferson','jq@gmail.com','$2y$10$oFjzSdajaO7Tah6zgE4DUeTDPPwrZtwwz5K3QFsWi1F48U0VmsezS','estudiante','2025-05-21 19:14:35',0,NULL),
(8,'María García','maria.garcia@escuela.edu','$2y$10$HycAleooD1VVOxcKDrBuMe3cJLGSAPXkyoog9tAUf6l7AJUfCHTE.','docente','2025-05-21 19:30:52',0,NULL),
(9,'Carlos Rodriguez','carlos.rodriguez@escuela.edu','$2y$10$HycAleooD1VVOxcKDrBuMe3cJLGSAPXkyoog9tAUf6l7AJUfCHTE.','docente','2025-05-21 19:30:52',0,NULL),
(10,'Ana López','ana.lopez@escuela.edu','$2y$10$HycAleooD1VVOxcKDrBuMe3cJLGSAPXkyoog9tAUf6l7AJUfCHTE.','docente','2025-05-21 19:30:52',0,NULL),
(11,'Luis Martínez','luis.martinez@escuela.edu','$2y$10$HycAleooD1VVOxcKDrBuMe3cJLGSAPXkyoog9tAUf6l7AJUfCHTE.','docente','2025-05-21 19:30:52',0,NULL),
(12,'Sofia Hernández','sofia.hernandez@estudiante.edu','$2y$10$5mWn7rj3y3t7Hh9pDocRp.U.twOmQI1o..wydq/mNQyc7Fv1AoB9y','estudiante','2025-05-21 19:30:52',0,NULL),
(13,'Miguel Torres','miguel.torres@estudiante.edu','$2y$10$5mWn7rj3y3t7Hh9pDocRp.U.twOmQI1o..wydq/mNQyc7Fv1AoB9y','estudiante','2025-05-21 19:30:52',0,NULL),
(14,'Carmen Ruiz','carmen.ruiz@estudiante.edu','$2y$10$5mWn7rj3y3t7Hh9pDocRp.U.twOmQI1o..wydq/mNQyc7Fv1AoB9y','estudiante','2025-05-21 19:30:52',0,NULL),
(15,'Diego Morales','diego.morales@estudiante.edu','$2y$10$5mWn7rj3y3t7Hh9pDocRp.U.twOmQI1o..wydq/mNQyc7Fv1AoB9y','estudiante','2025-05-21 19:30:52',0,NULL),
(16,'Isabella Cruz','isabella.cruz@estudiante.edu','$2y$10$5mWn7rj3y3t7Hh9pDocRp.U.twOmQI1o..wydq/mNQyc7Fv1AoB9y','estudiante','2025-05-21 19:30:52',0,NULL),
(17,'Andrés Vega','andres.vega@estudiante.edu','$2y$10$5mWn7rj3y3t7Hh9pDocRp.U.twOmQI1o..wydq/mNQyc7Fv1AoB9y','estudiante','2025-05-21 19:30:52',0,NULL),
(18,'Carlos','carlos@gail.com','$2y$10$nVUNjMmwOVeLoTOZifj9meJOhoDXPzf/CC.Vz7VIOOPu/zWI4bM1S','estudiante','2025-05-25 09:46:29',0,NULL),
(19,'Jefferson','jquezada@gmail.com','$2y$10$biCK4.f.KWSqXkAXrSuDnuvGDqFOXLfYIpWsKvejTI61WQxX6mn4u','estudiante','2025-06-05 09:06:59',0,NULL),
(20,'Jefferson Quezada','jefferson@docente.com','$2y$10$6f9oKlSYhTenKyY1UErHy.h/1hHxMpSp.OK/WICKaX8b77gKcRmK.','docente','2025-06-07 11:28:32',0,NULL),
(21,'Stuar','stuar@gmail.com','$2y$10$LW.6GoKaqMzlNZO8upE1COTQog3vLN9N.XpV0RiYACKes3K7ZWGWK','estudiante','2025-06-08 20:17:29',0,NULL),
(22,'Juan Martinez','juamar0125@estudiante.edu','$2y$10$ZPpTJ6K263qSRZYzYHMWFenvol6AqvARD6PeY5N/k55GkpTfWQuIu','estudiante','2025-06-10 13:59:08',0,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
