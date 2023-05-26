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
    <meta http-equiv="refresh" content="0; URL=cadastro_dieta.php">
</head>
<body>
</body>
</html>