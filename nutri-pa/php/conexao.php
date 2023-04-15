<?php
  // Define as constantes de conexão com o banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'bitefit');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

  // Tenta se conectar ao banco de dados usando as constantes definidas acima
try {
  $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Caso ocorra um erro na conexão, exibe uma mensagem de erro
  die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
