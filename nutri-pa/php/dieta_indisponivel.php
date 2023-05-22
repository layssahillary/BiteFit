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
	<title>Dieta indisponivel</title>
    <meta charset="UTF-8">
</head>
<body>
	<h1>Dieta indisponivel</h1>
	<p>Este paciente ainda não possui uma dieta, deseja cria-la??</p>
    <a href="cadastro_dieta.php"><button>Sim</button></a>
    <a href="perfilpaciente_nutri.php"><button>Não</button></a>
</body>
</html>