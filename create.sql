CREATE DATABASE portifolio;
USE portifolio;

CREATE TABLE `tokens_autorizados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `status` enum('S','N') NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_UNIQUE` (`token`)
);

CREATE TABLE IF NOT EXISTS `habilidades` (
  `tag` VARCHAR(8) NOT NULL,
  `nome` VARCHAR(45) NOT NULL,
  `porcentagem` INT NULL DEFAULT 0,
  `cor` VARCHAR(8) NULL,
  `icone` VARCHAR(25) NULL,
  PRIMARY KEY (`tag`));

CREATE TABLE IF NOT EXISTS `projetos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `imagemUrl` VARCHAR(255) NULL,
  `projetoUrl` VARCHAR(255) NULL,
  `githubUrl` VARCHAR(255) NULL,
  `descricao` TEXT NULL,
  `visualizacoes` INT NULL DEFAULT 0,
  PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS `projetos_has_habilidades` (
  `projetos_id` INT NOT NULL,
  `habilidades_tag` VARCHAR(8) NOT NULL,
  PRIMARY KEY (`projetos_id`, `habilidades_tag`),
  INDEX `fk_projetos_has_habilidades_habilidades1_idx` (`habilidades_tag` ASC),
  INDEX `fk_projetos_has_habilidades_projetos_idx` (`projetos_id` ASC),
  CONSTRAINT `fk_projetos_has_habilidades_projetos`
    FOREIGN KEY (`projetos_id`)
    REFERENCES `projetos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_projetos_has_habilidades_habilidades1`
    FOREIGN KEY (`habilidades_tag`)
    REFERENCES `habilidades` (`tag`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);