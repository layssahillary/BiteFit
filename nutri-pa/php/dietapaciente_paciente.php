<?php
require_once 'conexao.php';

// Verifica se o usuário está logado como paciente
session_start();
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
  exit();
}

// Recupera as informações do paciente
$stmt = $pdo->prepare('SELECT * FROM paciente psc
                      INNER JOIN dieta AS det ON det.id_paciente=psc.id
                      INNER JOIN refeicao AS ref ON ref.dieta_id=det.id_dieta
                      INNER JOIN alimento AS ali ON ali.refeicao_id=ref.id_refeicao
                      WHERE id = ? ');
$stmt->execute([$_SESSION['paciente_id']]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Sua dieta</title>
</head>
<body>
  <h1>Sua dieta atual:</h1>

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
  <a href="inicio_paciente.php">Voltar para o início</a>
</body>
</html>