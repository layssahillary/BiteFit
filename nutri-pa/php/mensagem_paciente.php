<?php
require_once "conexao.php";

// Verifica se o usuário está logado como paciente
session_start();
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
  exit();
}

// Verifica se o ID do nutricionista foi informado
if (!isset($_GET['nutricionista_id'])) {
  header("Location: nutricionistas.php");
  exit();
}

$nutricionista_id = $_GET['nutricionista_id'];

// Verifica se o nutricionista existe no banco de dados
$sql = "SELECT * FROM nutricionista WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nutricionista_id]);
$nutricionista = $stmt->fetch();

if (!$nutricionista) {
  header("Location: nutricionistas.php");
  exit();
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Salva a mensagem no banco de dados
  $mensagem = $_POST['mensagem'];
  $sql = "INSERT INTO mensagem (texto, paciente_id, nutricionista_id) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$mensagem, $_SESSION['paciente_id'], $nutricionista_id]);
}

// Busca as mensagens trocadas entre o paciente e o nutricionista
$sql = "SELECT * FROM mensagem WHERE (paciente_id = ? AND nutricionista_id = ?) OR (paciente_id = ? AND nutricionista_id = ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['paciente_id'], $nutricionista_id, $nutricionista_id, $_SESSION['paciente_id']]);
$mensagens = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Mensagens com <?php echo $nutricionista['nome']; ?></title>
  <meta charset="UTF-8">
</head>
<body>
	<h1>Mensagens com <?php echo $nutricionista['nome']; ?></h1>
	<ul>
    <?php foreach ($mensagens as $mensagem): ?>
      <li>
        <strong><?php echo $mensagem['remetente'] == 'paciente' ? 'Eu' : $nutricionista['nome']; ?>:</strong>
        <?php echo $mensagem['texto']; ?>
      </li>
    <?php endforeach; ?>
  </ul>
  <form method="POST">
    <label for="mensagem">Mensagem:</label><br>
    <textarea id="mensagem" name="mensagem" required></textarea><br><br>
    <input type="submit" value="Enviar">
  </form>
</body>
</html>
