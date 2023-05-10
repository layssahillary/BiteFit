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
  // Exclui o paciente do banco de dados
  $sql  = "DELETE FROM paciente WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id_paciente]);

  header("Location: pacientes.php");
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Excluir Paciente</title>
    <meta charset="UTF-8">
</head>
<body>
	<h1>Excluir Paciente</h1>
	<p>Tem certeza que deseja excluir o paciente <?php echo $paciente['nome']; ?>?</p>
	<form method="POST">
		<input type="submit" value="Sim">
	</form>
    <a href="perfilpaciente_nutri.php"><button>Não</button></a>
</body>
</html>
