<?php
require_once "conexao.php";

// Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
  exit();
}


// Verifica se o ID do paciente foi informado
if (!isset($_GET['id'])) {
  header("Location: pacientes.php");
  exit();
}

$id_paciente = $_GET['id'];

// Verifica se o paciente pertence ao nutricionista logado
$sql = "SELECT * FROM paciente WHERE id = ? AND nutricionista_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_paciente, $_SESSION['nutricionista_id']]);
$paciente = $stmt->fetch();

if (!$paciente) {
  header("Location: pacientes.php");
  exit();
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Exclui a dieta do banco de dados
  $sql  = "DELETE FROM dieta WHERE id_paciente = :id_paciente";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
  $stmt->execute();

  header("Location: pacientes.php");
  exit();
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Excluir Dieta</title>
    <meta charset="UTF-8">
</head>
<body>
	<h1>Excluir Dieta</h1>
	<p>Tem certeza que deseja excluir o dieta?</p>
	<form method="POST">
		<input type="submit" value="Sim">
	</form>
    <a href="perfilpaciente_nutri.php"><button>Não</button></a>
</body>
</html>
