<?php
require_once "conexao.php";

// Verifica se o usuário está logado como paciente
session_start();
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
  exit();
}

// Obtém as informações do paciente
$sql = "SELECT * FROM paciente WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['paciente_id']]);
$paciente = $stmt->fetch();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Perfil do Paciente</title>
    <meta charset="UTF-8">
</head>
<body>
	<h1>Seu perfil como paciente:</h1>
    <p><strong>Nome completo:</strong> <?php echo $paciente['nome']; ?></p>
    <p><strong>Sexo:</strong> <?php echo $paciente['sexo']; ?></p>
    <p><strong>Data de Nascimento:</strong> <?php echo $paciente['data_nascimento']; ?></p>
    <p><strong>Idade:</strong> <?php echo $paciente['idade'] . " anos"; ?></p>
    <p><strong>Altura:</strong> <?php echo $paciente['altura']; ?> m</p>
    <p><strong>Peso:</strong> <?php echo $paciente['peso']; ?> kg</p>
    <p><strong>Seu objetivo:</strong> <?php echo $paciente['objetivo']; ?></p>
    <a href="inicio_paciente.php">Voltar para o início</a>
</body>
</html>
