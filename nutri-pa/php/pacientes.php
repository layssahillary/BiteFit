<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Verifica se o usuário é nutricionista antes de continuar
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header('Location: login_nutricionista.php');
  exit();
}

// Recupera o ID do nutricionista logado na sessão
$nutricionista_id = $_SESSION['nutricionista_id'];

// Recupera a lista de pacientes cadastrados pelo nutricionista logado
$stmt = $pdo->prepare('SELECT * FROM paciente WHERE nutricionista_id = ?');
$stmt->execute([$nutricionista_id]);
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Lista de Pacientes</title>
</head>
<body>
  <h1>Seus pacientes cadastrados:</h1>
  <table>
    <thead>
      <tr>
        <th>Pacientes:</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pacientes as $paciente): ?>
        <tr>
          <td><?php echo $paciente['nome']; ?></td>
          <td><a href="perfilpaciente_nutri.php?id=<?php echo $paciente['id']; ?>">Ver Perfil</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="cadastro_paciente.php">Cadastrar novo paciente</a>
  <a href="inicio_nutricionista.php">Voltar para o início</a>
</body>
</html>
