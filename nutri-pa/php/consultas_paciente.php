<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado como paciente
if (!isset($_SESSION['paciente_id'])) {
  header('Location: login_paciente.php');
  exit();
}

// Obtém o ID do paciente logado
$paciente_id = $_SESSION['paciente_id'];

// Obtém os dados do paciente
$stmt = $pdo->prepare('SELECT * FROM paciente WHERE id = ?');
$stmt->execute([$paciente_id]);
$paciente = $stmt->fetch();

// Obtém as consultas do paciente
$stmt = $pdo->prepare('SELECT consulta.id, consulta.data, consulta.horario, consulta.realizada, nutricionista.nome as nutricionista_nome FROM consulta JOIN nutricionista ON consulta.nutricionista_id = nutricionista.id WHERE consulta.paciente_id = ?');
$stmt->execute([$paciente_id]);
$consultas = $stmt->fetchAll();

// Verifica se o formulário de agendamento de consulta foi submetido
if (isset($_POST['agendar_consulta'])) {
  $data = $_POST['data'];
  $horario = $_POST['horario'];
  $nutricionista_id = $paciente['nutricionista_id'];

  // Insere a consulta no banco de dados
  $stmt = $pdo->prepare('INSERT INTO consulta (data, horario, paciente_id, nutricionista_id) VALUES (?, ?, ?, ?)');
  $stmt->execute([$data, $horario, $paciente_id, $nutricionista_id]);

  // Redireciona para a página de consultas
  header('Location: consultas_paciente.php');
  exit();
}

// Verifica se o formulário de exclusão de consulta foi submetido
if (isset($_POST['excluir_consulta'])) {
  $consulta_id = $_POST['consulta_id'];

  // Remove a consulta do banco de dados
  $stmt = $pdo->prepare('DELETE FROM consulta WHERE id = ?');
  $stmt->execute([$consulta_id]);

  // Redireciona para a página de consultas
  header('Location: consultas_paciente.php');
  exit();
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Consultas - Paciente</title>
</head>
<body>
  <h1>Suas consultas:</h1>
  <h2>Nova Consulta</h2>
  <form method="post">
    <label>Data:</label>
    <input type="date" name="data" required><br><br>
    <label>Horário:</label>
    <input type="time" name="horario" required><br><br>
    <input type="submit" name="agendar_consulta" value="Agendar Consulta">
  </form>
  <h2>Consultas Agendadas</h2>
  <table>
    <tr>
      <th>Data</th>
      <th>Horário</th>
<th>Nutricionista</th>
<th>Realizada</th>
<th>Ações</th>
</tr>
<?php foreach ($consultas as $consulta): ?>
<tr>
<td><?php echo $consulta['data']; ?></td>
<td><?php echo $consulta['horario']; ?></td>
<td><?php echo $consulta['nutricionista_nome']; ?></td>
<td><?php echo $consulta['realizada'] ? 'Sim' : 'Não'; ?></td>
<td>
<form method="post" style="display: inline-block;">
<input type="hidden" name="consulta_id" value="<?php echo $consulta['id']; ?>">
<input type="submit" name="excluir_consulta" value="Excluir">
</form>
</td>
</tr>
<?php endforeach; ?>
  </table>
<p><a href="inicio_paciente.php">Voltar</a></p>
</body>
</html>