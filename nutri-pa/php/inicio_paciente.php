<?php
require_once "conexao.php";

// Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Página Inicial do Paciente</title>
  </head>
  <body>
    <h1>Bem-vindo, Paciente</h1>
    <p>Esta é a sua página inicial. Aqui você poderá encontrar informações sobre sua dieta e exercícios, bem como entrar em contato com seu nutricionista.</p>
    <ul>
      <li><a href="perfilpaciente_paciente.php">Perfil</a></li>
      <li><a href="dieta.php">Dietas</a></li>
      <li><a href="perfilnutricionista_paciente.php">Seu Nutricionista</a></li>
      <li><a href="consultas_paciente.php">Consultas</a></li>
      <li><a href="logout_paciente.html">Sair</a></li>
    </ul>
  </body>
</html>
