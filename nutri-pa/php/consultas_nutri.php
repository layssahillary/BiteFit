<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado como nutricionista
if (!isset($_SESSION['nutricionista_id'])) {
  header('Location: login_nutricionista.php');
  exit();
}

// Obtém o ID do nutricionista logado
$nutricionista_id = $_SESSION['nutricionista_id'];

// Obtém os pacientes do nutricionista
$stmt_pacientes = $pdo->prepare('SELECT * FROM paciente WHERE nutricionista_id = ?');
$stmt_pacientes->execute([$nutricionista_id]);
$pacientes = $stmt_pacientes->fetchAll();

// Verifica se o formulário de filtro de pacientes foi submetido
$paciente_id = null;
if (isset($_POST['filtro_pacientes'])) {
  $paciente_id = $_POST['paciente_id'];
}

// Obtém as consultas do nutricionista com o filtro de paciente, se houver
if ($paciente_id) {
  $stmt_consultas = $pdo->prepare('SELECT consulta.id, consulta.data, consulta.horario, consulta.realizada, paciente.nome as paciente_nome FROM consulta JOIN paciente ON consulta.paciente_id = paciente.id WHERE consulta.nutricionista_id = ? AND paciente.id = ?');
  $stmt_consultas->execute([$nutricionista_id, $paciente_id]);
} else {
  $stmt_consultas = $pdo->prepare('SELECT consulta.id, consulta.data, consulta.horario, consulta.realizada, paciente.nome as paciente_nome FROM consulta JOIN paciente ON consulta.paciente_id = paciente.id WHERE consulta.nutricionista_id = ?');
  $stmt_consultas->execute([$nutricionista_id]);
}

$consultas = $stmt_consultas->fetchAll();

// Verifica se o paciente selecionado possui consultas
if ($paciente_id && count($consultas) == 0) {
    echo 'Este paciente não possui consultas.';
  }  

// Verifica se o formulário de exclusão de consulta foi submetido
if (isset($_POST['excluir_consulta'])) {
  $consulta_id = $_POST['consulta_id'];

  // Remove a consulta do banco de dados
  $stmt = $pdo->prepare('DELETE FROM consulta WHERE id = ?');
  $stmt->execute([$consulta_id]);

  // Redireciona para a página de consultas
  header('Location: consultas_nutri.php');
  exit();
}

// Verifica se o formulário de marcação de consulta como realizada foi submetido
if (isset($_POST['marcar_realizada'])) {
  $consulta_id = $_POST['consulta_id'];

  // Marca a consulta como realizada no banco de dados
  $stmt = $pdo->prepare('UPDATE consulta SET realizada = 1 WHERE id = ?');
  $stmt->execute([$consulta_id]);

  // Redireciona para a página de consultas
  header('Location: consultas_nutri.php');
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Consultas - Nutricionista</title>
</head>
<body>
  <h1>Consultas - Nutricionista</h1>

  <form method="post">
    <label for="paciente_id">Filtrar por paciente:</label>
    <select name="paciente_id" id="paciente_id">
      <option value="">Todos</option>
      <?php foreach ($pacientes as$paciente): ?>
<option value="<?php echo $paciente['id'] ?>" <?php if ($paciente_id == $paciente['id']) echo 'selected' ?>><?php echo $paciente['nome'] ?></option>
<?php endforeach; ?>
</select>
<button type="submit" name="filtro_pacientes">Filtrar</button>

  </form>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Data</th>
        <th>Horário</th>
        <th>Paciente</th>
        <th>Realizada?</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($consultas as $consulta): ?>
        <tr>
          <td><?php echo $consulta['id'] ?></td>
          <td><?php echo date('d/m/Y', strtotime($consulta['data'])) ?></td>
          <td><?php echo date('H:i', strtotime($consulta['horario'])) ?></td>
          <td><?php echo $consulta['paciente_nome'] ?></td>
          <td><?php echo $consulta['realizada'] ? 'Sim' : 'Não' ?></td>
          <td>
            <form method="post" style="display: inline-block;">
              <input type="hidden" name="consulta_id" value="<?php echo $consulta['id'] ?>">
              <button type="submit" name="excluir_consulta" onclick="return confirm('Tem certeza que deseja excluir esta consulta?')">Excluir</button>
            </form>
            <?php if (!$consulta['realizada']): ?>
              <form method="post" style="display: inline-block;">
                <input type="hidden" name="consulta_id" value="<?php echo $consulta['id'] ?>">
                <button type="submit" name="marcar_realizada">Marcar como realizada</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="inicio_nutricionista.php">Voltar para o início</a>

</body>
</html>
