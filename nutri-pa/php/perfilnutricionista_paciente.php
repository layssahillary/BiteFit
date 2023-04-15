<?php
session_start();

// Verifica se o usuário está logado como paciente
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
  exit();
}

require_once "conexao.php";

// Busca as informações do nutricionista que cadastrou o paciente
$sql = "SELECT * FROM nutricionista WHERE id = (SELECT nutricionista_id FROM paciente WHERE id = ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['paciente_id']]);
$nutricionista = $stmt->fetch();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Perfil do Nutricionista</title>
	<meta charset="UTF-8">
</head>
<body>
	<h1>Perfil do seu Nutricionista</h1>
	<p>Aqui você verá todas as informações do seu nutricionista: </p>
	<br>
	<p><strong>Nome:</strong><?php echo $nutricionista['nome']; ?></p>
	<p><strong>E-mail:</strong> <?php echo $nutricionista['email']; ?></p>
	<p><strong>Telefone:</strong> <?php echo $nutricionista['telefone']; ?></p>
	<p><strong>Celular:</strong> <?php echo $nutricionista['celular']; ?></p>	
	<p><strong>Endereço:</strong> <?php echo $nutricionista['endereco']; ?></p>
	<p><strong>CRN:</strong> <?php echo $nutricionista['crn']; ?></p>
	<a href="inicio_paciente.php">Voltar para o ínicio</a>
</body>
</html>
