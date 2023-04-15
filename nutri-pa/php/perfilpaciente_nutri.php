<?php
require_once 'conexao.php';

// Verifica se o usuário é nutricionista antes de continuar
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header('Location: login_nutricionista.php');
  exit();
}

// Verifica se foi passado o ID do paciente
if (!isset($_GET['id'])) {
  header('Location: pacientes.php');
  exit();
}

// Recupera as informações do paciente
$stmt = $pdo->prepare('SELECT * FROM paciente WHERE id = ? AND nutricionista_id = ?');
$stmt->execute([$_GET['id'], $_SESSION['nutricionista_id']]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o paciente existe e pertence ao nutricionista
if (!$paciente) {
  header('Location: pacientes.php');
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Perfil do Paciente</title>
</head>
<body>
  <h1>Perfil do Paciente</h1>
  <p><strong>Nome completo:</strong> <?php echo $paciente['nome']; ?></p>
  <p><strong>Sexo:</strong> <?php echo $paciente['sexo']; ?></p>
  <p><strong>Data de Nascimento:</strong> <?php echo $paciente['data_nascimento']; ?></p>
  <p><strong>Idade:</strong> <?php echo $paciente['idade'] . " anos"; ?></p>
  <p><strong>Altura:</strong> <?php echo $paciente['altura']; ?> m</p>
  <p><strong>Peso:</strong> <?php echo $paciente['peso']; ?> kg</p>
  <p><strong>Objetivo do paciente:</strong> <?php echo $paciente['objetivo']; ?></p>
  <a href="editar_paciente.php?id=<?php echo $paciente['id']; ?>">Editar</a>
  <a href="excluir_paciente.php?id=<?php echo $paciente['id']; ?>">Excluir</a>
  <a href="pacientes.php">Voltar para a lista de pacientes</a>
</body>
</html>
