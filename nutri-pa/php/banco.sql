CREATE TABLE `nutricionista` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `crn` varchar(10) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `horario_inicio` TIME,
  `horario_fim` TIME,
  `dias_semana` VARCHAR(100),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `paciente` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `altura` float NOT NULL,
  `peso` float NOT NULL,
  `data_nascimento` date NOT NULL,
  `idade` int(11) NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `objetivo` varchar(50) NOT NULL,
  `nutricionista_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nutricionista_id` (`nutricionista_id`),
  CONSTRAINT `paciente_ibfk_1` FOREIGN KEY (`nutricionista_id`) REFERENCES `nutricionista` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dieta` (
  `id_dieta` int(11) NOT NULL AUTO_INCREMENT,
  `data_validade` date NOT NULL,
  `id_paciente` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_dieta`),
  KEY `id_paciente` (`id_paciente`) USING BTREE,
  CONSTRAINT `id_paciente_fk` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `refeicao` (
  `id_refeicao` int(11) NOT NULL AUTO_INCREMENT,
  `nome_refeicao` varchar(30) NOT NULL,
  `dia_semana` varchar(30) NOT NULL,
  `horario` time NOT NULL,
  `dieta_id` int(11) NOT NULL,
  PRIMARY KEY (`id_refeicao`),
  KEY `dieta_id` (`dieta_id`) USING BTREE,
  CONSTRAINT `id_dieta_fk` FOREIGN KEY (`dieta_id`) REFERENCES `dieta` (`id_dieta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `alimento` (
  `id_alimento` int(11) NOT NULL AUTO_INCREMENT,
  `nome_alimento` varchar(30) NOT NULL,
  `quantidade` float NOT NULL,
  `medidas` varchar(30) NOT NULL,
  `calorias` float NOT NULL,
  `proteina` float NOT NULL,
  `carboidrato` float NOT NULL,
  `gordura` float NOT NULL,
  `refeicao_id` int(11) NOT NULL,
  PRIMARY KEY (`id_alimento`),
  KEY `id_refeicao_fk` (`refeicao_id`),
  CONSTRAINT `id_refeicao_fk` FOREIGN KEY (`refeicao_id`) REFERENCES `refeicao` (`id_refeicao`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `consulta` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `data` DATE NOT NULL,
  `horario` TIME NOT NULL,
  `paciente_id` INT(11) UNSIGNED NOT NULL,
  `nutricionista_id` INT(11) UNSIGNED NOT NULL,
  `realizada` BOOLEAN NOT NULL DEFAULT FALSE,
  `descricao` VARCHAR(100) NOT NULL,
  CONSTRAINT `fk_consulta_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_consulta_nutricionista` FOREIGN KEY (`nutricionista_id`) REFERENCES `nutricionista` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE info_nutri (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    IMC FLOAT NOT NULL,
    proteinas FLOAT NOT NULL,
    carboidratos FLOAT NOT NULL,
    gorduras FLOAT NOT NULL,
    taxa_metabolica FLOAT NOT NULL,
    GCD FLOAT NOT NULL,
    resultado VARCHAR(255) NOT NULL,
    paciente_id INT(11) UNSIGNED NOT NULL,
    FOREIGN KEY (paciente_id) REFERENCES paciente(id) ON DELETE CASCADE,
    data DATE NOT NULL DEFAULT CURRENT_DATE
);

ALTER TABLE `alimento`
  MODIFY `id_alimento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;


ALTER TABLE `dieta`
  MODIFY `id_dieta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

ALTER TABLE `nutricionista`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `paciente`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


ALTER TABLE `refeicao`
  MODIFY `id_refeicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;


