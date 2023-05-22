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
$stmt = $pdo->prepare('SELECT * FROM paciente psc
                      INNER JOIN dieta AS det ON det.id_paciente=psc.id
                      INNER JOIN refeicao AS ref ON ref.dieta_id=det.id_dieta
                      INNER JOIN alimento AS ali ON ali.refeicao_id=ref.id_refeicao
                      WHERE id = ? AND nutricionista_id = ?');
$stmt->execute([$_GET['id'], $_SESSION['nutricionista_id']]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);


// Verifica se o paciente existe e pertence ao nutricionista
if (!$paciente) {
  header('Location: dieta_indisponivel.php');
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
  <p><strong>ID:</strong> <?php echo $paciente['id'];?> </P>
  <p><strong>Nome completo:</strong> <?php echo $paciente['nome']; ?></p>
  <h3> Dieta <h3>

<?php 
  $ref_atual = -1;

  while ($refeicao = $stmt->fetch(PDO::FETCH_ASSOC) ) 
  {
    if($ref_atual != $refeicao['refeicao_id'])
    {
      $ref_atual = $refeicao['refeicao_id'];
    
?>
  </table>
  <br>
  <td><strong>Refeição: </strong><?php echo $refeicao['nome_refeicao']; ?></td><br>
  <td><strong>Dia da semana: </strong><?php echo $refeicao['dia_semana']; ?></td><br>
  <td><strong>Horário: </strong><?php echo $refeicao['horario']; ?></td>

  <table style = "margin-top: 5px">
    <thead>
      <tr>
        <th>Alimento</th>
        <th>Quantidade</th>
        <th>Unidade de medida</th>
        <th>Calorias</th>
        <th>Proteina</th>
        <th>Carboidrato</th>
        <th>Gordura</th>
      </tr>
    </thead>
<?php 
    }  
?>
    <tr>
    <tbody>
      <tr>
        <td><?php echo $refeicao['nome_alimento']; ?></td>
        <td><?php echo $refeicao['quantidade']; ?></td>
        <td><?php echo $refeicao['medidas']; ?></td>
        <td><?php echo $refeicao['calorias']; ?></td>
        <td><?php echo $refeicao['proteina']; ?></td>
        <td><?php echo $refeicao['carboidrato']; ?></td>
        <td><?php echo $refeicao['gordura']; ?></td>
      </tr>

    </tbody>
    
<?php
  } 
?>
  </table>
  <br>
<a href="excluir_dieta.php?id=<?php echo $paciente['id']; ?>">Excluir dieta</a>
<a href="pacientes.php">Voltar para a lista de pacientes</a>
</body>
</html>