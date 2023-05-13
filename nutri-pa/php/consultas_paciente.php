<?php
// Inclui o arquivo de conexão com o banco de dados
require_once "conexao.php";

// Inicia a sessão
session_start();

// Verifica se o paciente já está logado e redireciona para a página de início do paciente
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
  exit();
}

// Obtém o ID do paciente logado
$paciente_id = $_SESSION['paciente_id'];

// Obtém as consultas do paciente ordenadas pela data em que foram agendadas
$stmt = $pdo->prepare('SELECT consulta.*, nutricionista.nome AS nutricionista_nome, consulta.descricao FROM consulta 
INNER JOIN nutricionista ON consulta.nutricionista_id = nutricionista.id WHERE consulta.paciente_id = ? ORDER BY data ASC');
$stmt->execute([$paciente_id]);
$consultas = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Minhas Consultas</title>
</head>
<body>
  <h1>Minhas Consultas</h1>
  <?php if (count($consultas) > 0): ?>
    <table>
  <thead>
    <tr>
      <th>Data</th>
      <th>Horário</th>
      <th>Nutricionista</th>
      <th>Descrição</th>
      <th>Já foi realizada?</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($consultas as $consulta): ?>
    <tr>
      <td><?php echo $consulta['data']; ?></td>
      <td><?php echo $consulta['horario']; ?></td>
      <td><?php echo $consulta['nutricionista_nome']; ?></td>
      <td><?php echo $consulta['descricao']; ?></td>
      <td><?php echo $consulta['realizada'] ? 'Sim' : 'Não'; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <?php else: ?>
    <p>Você não possui consultas agendadas.</p>
    <?php endif; ?>
    <br>
    <a href="inicio_paciente.php">Voltar para o início</a>
</body>
</html>
