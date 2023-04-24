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

// Selecione as informações nutricionais do paciente ordenadas pela data
$stmt = $pdo->prepare("
    SELECT *
    FROM info_nutri
    WHERE paciente_id = ?
    ORDER BY data DESC
");

$stmt->execute([$_GET['id']]);
$info_nutri = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <p><strong>Altura:</strong> <?php echo $paciente['altura']; ?> cm</p>
  <p><strong>Peso:</strong> <?php echo $paciente['peso']; ?> kg</p>
  <p><strong>Objetivo do paciente:</strong> <?php echo $paciente['objetivo']; ?></p>
  <a href="editar_paciente.php?id=<?php echo $paciente['id']; ?>">Editar informações</a>
  <a href="excluir_paciente.php?id=<?php echo $paciente['id']; ?>">Excluir paciente</a>

  <h2>Histórico das Informações nutricionais do paciente:</h2>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>IMC</th>
                <th>Proteínas</th>
                <th>Carboidratos</th>
                <th>Gorduras</th>
                <th>Taxa Metabólica</th>
                <th>GCD</th>
                <th>Resultado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($info_nutri as $info): ?>
                <tr>
                    <td><?php echo $info['data']; ?></td>
                    <td><?php echo $info['IMC']; ?></td>
                    <td><?php echo $info['proteinas']; ?></td>
                    <td><?php echo $info['carboidratos']; ?></td>
                    <td><?php echo $info['gorduras']; ?></td>
                    <td><?php echo $info['taxa_metabolica']; ?></td>
                    <td><?php echo $info['GCD']; ?></td>
                    <td><?php echo $info['resultado']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="pacientes.php">Voltar para a lista de pacientes</a>
</body>
</html>
