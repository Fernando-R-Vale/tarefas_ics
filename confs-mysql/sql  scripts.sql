-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`usuario` (
  `email` VARCHAR(255) NOT NULL,
  `senha` VARCHAR(20) NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`email`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`categoria` (
  `idCategoria` INT NOT NULL AUTO_INCREMENT,
  `nomeCategoria` VARCHAR(45) NOT NULL,
  `usuario_email` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idCategoria`),
  INDEX `fk_categoria_usuario1_idx` (`usuario_email` ASC) VISIBLE,
  CONSTRAINT `fk_categoria_usuario1`
    FOREIGN KEY (`usuario_email`)
    REFERENCES `mydb`.`usuario` (`email`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`tarefa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`tarefa` (
  `idtarefa` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(40) NOT NULL,
  `descricao` TEXT NULL,
  `dhLimite` DATETIME NOT NULL,
  `dhCriacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `prioridade` ENUM("Alta", "Média", "Baixa") NOT NULL,
  `status` ENUM("Pendente", "Em andamento" ,"Concluido") NOT NULL,
  `usuario_email` VARCHAR(255) NOT NULL,
  `categoria_idCategoria` INT NOT NULL,
  PRIMARY KEY (`idtarefa`),
  INDEX `fk_tarefa_usuario_idx` (`usuario_email` ASC) VISIBLE,
  INDEX `fk_tarefa_categoria1_idx` (`categoria_idCategoria` ASC) VISIBLE,
  CONSTRAINT `fk_tarefa_usuario`
    FOREIGN KEY (`usuario_email`)
    REFERENCES `mydb`.`usuario` (`email`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tarefa_categoria1`
    FOREIGN KEY (`categoria_idCategoria`)
    REFERENCES `mydb`.`categoria` (`idCategoria`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
