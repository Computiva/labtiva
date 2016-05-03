-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: labvad
-- ------------------------------------------------------
-- Server version	5.5.44-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agendamentos`
--

DROP TABLE IF EXISTS `agendamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_laboratorio` int(11) NOT NULL,
  `fk_pessoa` int(11) NOT NULL,
  `dt_agendamento` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `dt_cadastro` datetime NOT NULL,
  `utilizado` char(1) NOT NULL DEFAULT 'n' COMMENT 'Define se foi utilizado pelo usuario',
  PRIMARY KEY (`id`),
  KEY `fk_agendamentos_1` (`fk_laboratorio`),
  KEY `fk_agendamentos_2` (`fk_pessoa`),
  CONSTRAINT `fk_laboratorio` FOREIGN KEY (`fk_laboratorio`) REFERENCES `laboratorios` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_pessoa` FOREIGN KEY (`fk_pessoa`) REFERENCES `pessoas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=691 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `experimentos`
--

DROP TABLE IF EXISTS `experimentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `experimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(70) NOT NULL,
  `codigo` text NOT NULL,
  `dt_envio` datetime NOT NULL,
  `fk_pessoa` int(11) NOT NULL,
  `publico` char(1) NOT NULL DEFAULT 'n',
  `fk_lab_tipo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lab_tipo` (`fk_lab_tipo`),
  CONSTRAINT `fk_lab_tipo` FOREIGN KEY (`fk_lab_tipo`) REFERENCES `laboratorios_tipo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=395 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `experimentos_versao`
--

DROP TABLE IF EXISTS `experimentos_versao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `experimentos_versao` (
  `id` int(11) NOT NULL,
  `fk_experimento` int(11) NOT NULL,
  `codigo` text NOT NULL,
  `dt_cadastro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_experimentos_versao_1` (`fk_experimento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `laboratorios`
--

DROP TABLE IF EXISTS `laboratorios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `laboratorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `dt_cadastro` datetime NOT NULL,
  `url_lab` varchar(150) DEFAULT NULL,
  `url_video` varchar(150) DEFAULT NULL,
  `nome_responsavel` varchar(70) DEFAULT NULL,
  `email_responsavel` varchar(70) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `fk_lab_tipo` int(11) DEFAULT NULL,
  `incluir_multiplexacao` char(1) DEFAULT NULL,
  `placa_arduino` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_UNIQUE` (`nome`),
  KEY `fk_lab_grupo` (`fk_lab_tipo`),
  CONSTRAINT `fk_lab_grupo` FOREIGN KEY (`fk_lab_tipo`) REFERENCES `laboratorios_tipo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `laboratorios_tipo`
--

DROP TABLE IF EXISTS `laboratorios_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `laboratorios_tipo` (
  `id` int(11) NOT NULL,
  `nome_tipo` varchar(45) DEFAULT NULL,
  `descricao` varchar(300) DEFAULT NULL,
  `prog_arduino` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_pessoa` int(11) NOT NULL,
  `acao` varchar(100) NOT NULL,
  `dt` datetime NOT NULL,
  `sessao` varchar(30) NOT NULL,
  `fk_lab_tipo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pessoa` (`fk_pessoa`),
  KEY `fk_lab_tipo` (`fk_lab_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=1328 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pessoas`
--

DROP TABLE IF EXISTS `pessoas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pessoas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(70) NOT NULL,
  `email` varchar(70) NOT NULL,
  `dt_cadastro` datetime NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 's',
  `tipo` char(1) NOT NULL DEFAULT 'u' COMMENT 'u = usuario\na = administrador',
  `senha` varchar(70) NOT NULL,
  `recuperar_senha` varchar(70) DEFAULT NULL,
  `senha_temp` date NOT NULL,
  `escola` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_pessoa` int(11) NOT NULL,
  `fk_lab_tipo` int(11) NOT NULL,
  `fk_laboratorio` int(11) NOT NULL,
  `dt_execucao` datetime NOT NULL,
  `codigo_arduino` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-25 13:34:30
