CREATE TABLE nutricionista (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  telefone VARCHAR(20),
  celular VARCHAR(20),
  crn VARCHAR(10),
  endereco VARCHAR(150),
  senha VARCHAR(255) NOT NULL;
);

CREATE TABLE paciente (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  altura FLOAT NOT NULL,
  peso FLOAT NOT NULL,
  data_nascimento DATE NOT NULL,
  idade INT(11) NOT NULL,
  sexo VARCHAR(20) NOT NULL,
  objetivo VARCHAR(50) NOT NULL,
  nutricionista_id INT(11) UNSIGNED NOT NULL,
  FOREIGN KEY (nutricionista_id) REFERENCES nutricionista(id) ON DELETE CASCADE
);
