<?php

require_once "conexao.php";
session_start();
if (!isset($_SESSION['nutricionista_id'])) 
{
  header("Location: login_nutricionista.php");
  exit();
}

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
var_dump($dados);


if(!empty($dados['cadUsuario']))
{
    // Dieta

    $query_verificar = "SELECT COUNT(*) FROM dieta WHERE id_paciente = :id_paciente";
    $verificar_dieta = $pdo->prepare($query_verificar);
    $verificar_dieta->bindParam(':id_paciente', $dados['id_paciente'], PDO::PARAM_STR);
    $verificar_dieta->execute();
    $count = $verificar_dieta->fetchColumn();

    if ($count == 0)
    {
    // Inserindo os valores na tabela 
    $query_dieta = "INSERT INTO dieta
                    ( id_paciente, data_validade) VALUES
                    (:id_paciente, :data_validade)";

    $cad_dieta = $pdo->prepare($query_dieta);

    $cad_dieta->bindParam(':id_paciente', $dados['id_paciente'], PDO::PARAM_STR);
    $cad_dieta->bindParam(':data_validade', $dados['data_validade'], PDO::PARAM_STR);
    $cad_dieta->execute();

    // Salvando id_dieta para refeição
    var_dump($pdo->lastInsertId());
    $id_dieta = $pdo->lastInsertId();
    }
    else
    {
      Echo '
      <script type="text/javascript">
           returnToPreviousPage();
      
      function returnToPreviousPage()
      {
          window.history.back();
      }
      </script>';
    }

    // Refeição

    $query_refeicao = "INSERT INTO refeicao
    (nome_refeicao, dia_semana, horario, dieta_id) VALUES
    (:nome_refeicao, :dia_semana, :horario, :dieta_id)";

    $cad_refeicao = $pdo->prepare($query_refeicao);
    $count = count($dados['nome_refeicao']);
    
    
    for ($i = 0; $i < $count; $i++) 
    {
      $cad_refeicao->bindParam(':nome_refeicao', $dados['nome_refeicao'][$i], PDO::PARAM_STR);
      $cad_refeicao->bindParam(':dia_semana', $dados['dia_semana'][$i], PDO::PARAM_STR);
      $cad_refeicao->bindParam(':horario', $dados['horario'][$i], PDO::PARAM_STR);
      $cad_refeicao->bindParam(':dieta_id', $id_dieta, PDO::PARAM_INT);
      $cad_refeicao->execute();
    }

    var_dump($pdo->lastInsertId());
    $id_ult_refeicao = $pdo->lastInsertId();
    $id_refeicao = $id_ult_refeicao - $count + 1;

    // Alimento

    $query_alimento = "INSERT INTO alimento
    (nome_alimento, quantidade, medidas, calorias, proteina, carboidrato, gordura, refeicao_id) VALUES
    (:nome_alimento, :quantidade, :medidas, :calorias, :proteina, :carboidrato, :gordura, :refeicao_id)";

    $cad_alimento = $pdo->prepare($query_alimento);
    $count = count($dados['nome_alimento']);

    for ($a = 0; $a < $count; $a++) 
    {
      $ali_id_ref = $id_refeicao + $dados['ref'][$a];
      $ind_medidas = $dados['ind'][$a] . 'medidas';
      
      $cad_alimento->bindParam(':nome_alimento', $dados['nome_alimento'][$a], PDO::PARAM_STR);
      $cad_alimento->bindParam(':quantidade', $dados['quantidade'][$a], PDO::PARAM_INT);
      $cad_alimento->bindParam(':medidas', $dados[$ind_medidas][0], PDO::PARAM_STR);
      $cad_alimento->bindParam(':calorias', $dados['calorias'][$a], PDO::PARAM_INT);
      $cad_alimento->bindParam(':proteina', $dados['proteina'][$a], PDO::PARAM_INT);
      $cad_alimento->bindParam(':carboidrato', $dados['carboidrato'][$a], PDO::PARAM_INT);
      $cad_alimento->bindParam(':gordura', $dados['gordura'][$a], PDO::PARAM_INT);
      $cad_alimento->bindParam(':refeicao_id', $ali_id_ref, PDO::PARAM_INT);
      $cad_alimento->execute();
    }

    $mensagem = "Dieta cadastrada com sucesso.";
    header("Location: pacientes.php?novo_paciente=1");
    exit();

}
else
{
  echo "Erro";
}

?>