<?php
require_once "conexao.php";

// Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Página Inicial - Nutricionista</title>
    <meta charset = "utf-8">
</head>
<body>
	<h1>Bem-vindo, Nutricionista!</h1>
	<ul>
	<li><a href="perfil_nutricionista.php">Perfil</a></li>
		<li><a href="pacientes.php">Lista de Pacientes</a></li>
		<li><a href="dietas.php">Dietas e Receitas</a></li>
		<li><a href="calculos.php">Cáculos Nutricionais</a></li>
		<li><a href="consultas_nutri.php">Consultas</a></li>
		<li><a href="sobre.php">Sobre</a></li>
		<li><a href="logout_nutricionista.html">Sair</a></li>
	</ul>
</body>
</html>
