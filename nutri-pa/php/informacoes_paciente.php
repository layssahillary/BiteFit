<?php
require_once "conexao.php";

// Verifica se o usuário está logado como paciente
session_start();
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
  exit();
}

// Busca as informações do paciente no banco de dados
$sql = "SELECT nome, email, data_nascimento, idade, sexo, altura, peso FROM paciente WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['paciente_id']]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Minhas informações</title>
</head>
<body>
  <h1>Minhas informações</h1>
  <p><strong>Nome:</strong> <?php echo $paciente['nome']; ?></p>
  <p><strong>Email:</strong> <?php echo $paciente['email']; ?></p>
  <p><strong>Data de nascimento:</strong> <?php echo date("d/m/Y", strtotime($paciente['data_nascimento'])); ?></p>
  <p><strong>Idade:</strong> <?php echo $paciente['idade']; ?> anos</p>
  <p><strong>Sexo:</strong> <?php echo $paciente['sexo']; ?></p>
  <p><strong>Altura:</strong> <?php echo $paciente['altura']; ?> cm</p>
  <p><strong>Peso:</strong> <?php echo $paciente['peso']; ?> kg</p>
  <p><a href="logout_paciente.php">Sair</a></p>
  <p><a href="inicio_paciente.php">Voltar</a></p>
</body>
</html>
