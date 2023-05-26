<?php
require_once "conexao.php";

// Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
  exit();
}

// Verifica se o ID do paciente foi informado
if (!isset($_GET['id'])) {
  header("Location: pacientes.php");
  exit();
}

$id_paciente = $_GET['id'];

// Verifica se o paciente pertence ao nutricionista logado
$sql = "SELECT * FROM paciente WHERE id = ? AND nutricionista_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_paciente, $_SESSION['nutricionista_id']]);
$paciente = $stmt->fetch();

if (!$paciente) {
  header("Location: pacientes.php");
  exit();
}

// Recupera as informações do paciente
$stmt = $pdo->prepare('SELECT * FROM paciente psc
                      INNER JOIN dieta AS det ON det.id_paciente=psc.id
                      INNER JOIN refeicao AS ref ON ref.dieta_id=det.id_dieta
                      INNER JOIN alimento AS ali ON ali.refeicao_id=ref.id_refeicao
                      WHERE id = ? AND nutricionista_id = ?');
$stmt->execute([$id_paciente, $_SESSION['nutricionista_id']]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o paciente existe e pertence ao nutricionista
if (!$paciente) {
  header('Location: dieta_indisponivel.php');
  exit();
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Exclui a dieta do banco de dados
  $sql  = "DELETE FROM dieta WHERE id_paciente = :id_paciente";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
  $stmt->execute();

  header("Location: pacientes.php");
  exit();
}

?>


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Nutrição | BiteFit</title>
        <meta name="description" content="Home">
        <link rel="preload" href="../css/dietapaciente_nutri.css" as="style">
        <link rel="stylesheet" href="../css/dietapaciente_nutri.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500&display=swap" rel="stylesheet">
      

       
    </head>

<body id="bitefit">

<header class="header-bg">
    <div class="header container">
        <a href="./inicio_nutricionista.php">
            <img src="../imagens/icons/logoBiteFit.svg" alt="BiteFit">
        </a>
        
        <nav class="links header-menu" aria-label="primaria">

	<a href="#" onmouseover="showMenu()">Pacientes</a>
<div id="menu" onmouseout="hideMenu()" onmouseover="keepMenu()">
  <ul class="header-menu  font-2-l cor-0">
		<li><a class="link-cadastro" href="./cadastro_paciente.php">Cadastrar Paciente</a></li>
		<li><a href="./pacientes.php">Lista de Pacientes</a></li>
		<li><a href="./consultas_nutri.php">Consultas</a></li>
		<li><a href="./calculos.php">Cáculos Nutricionais</a></li>
		<li><a href="cadastro_dieta.php">Criar nova dieta</a></li>
  </ul>
</div>
<li><a href="./perfil_nutricionista.php">Perfil</a></li>
<li><a href="./sobre_nutricionista.html">Sobre</a></li>
<li><button class="deslogar" onclick="showOverlay()"><img src="../imagens/icons/logout-icon.svg" alt="descrição da imagem"></button>


</nav>
</div>
</header>
<div class="container">
  <div class="container-dieta">
    <div class="container-geral">

    <div class="container-perfil">
    <div class="perfil-img">
    <img src="../imagens/icons/perfil-dieta.svg" alt="perfil dieta">
  </div>
      <div class="perfil">
  <h2>Perfil dieta do paciente</h2>
  <p>ID: <?php echo $paciente['id'];?> </P>
  <p>Nome completo: <?php echo $paciente['nome']; ?></p>
  </div>
  
  </div>

  <?php

        
$refeicoes = array(); // Array para armazenar as refeições e seus alimentos

// Preenche o array de refeições
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $refeicaoId = $row['id_refeicao'];

  // Verifica se a refeição já está no array
  if (!array_key_exists($refeicaoId, $refeicoes)) {
    $refeicoes[$refeicaoId] = array(
      'id' => $refeicaoId,
      'nome' => $row['nome_refeicao'],
      'dia_semana' => $row['dia_semana'],
      'horario' => $row['horario'],
      'alimentos' => array()
    );
  }

  // Adiciona o alimento à refeição correspondente
  $refeicoes[$refeicaoId]['alimentos'][] = array(
    'nome' => $row['nome_alimento'],
    'quantidade' => $row['quantidade'],
    'medidas' => $row['medidas'],
    'calorias' => $row['calorias'],
    'proteina' => $row['proteina'],
    'carboidrato' => $row['carboidrato'],
    'gordura' => $row['gordura']
  );
}

// Exibe as informações das refeições e seus alimentos
foreach ($refeicoes as $refeicao) {
?>
 
 <div class="container-refeicao-geral">
            <div class="container-refeicao">
              <p><strong> Refeição:</strong> <?php echo $refeicao['nome']; ?></p>
              <p><strong> Dia da semana:</strong> <?php echo $refeicao['dia_semana']; ?></p>
              <p><strong> Horário:</strong> <?php echo $refeicao['horario']; ?></p>
            </div>
            <div class="container-alimento">
              <table>
                <thead>
                  <tr>
                    <th>Alimento</th>
                    <th>Quantidade</th>
                    <th>Unidade de medida</th>
                    <th>Calorias</th>
                    <th>Proteína</th>
                    <th>Carboidrato</th>
                    <th>Gordura</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($refeicao['alimentos'] as $alimento) { ?>
                    <tr>
                      <td><?php echo $alimento['nome']; ?></td>
                      <td><?php echo $alimento['quantidade']; ?></td>
                      <td><?php echo $alimento['medidas']; ?></td>
                      <td><?php echo $alimento['calorias']; ?></td>
                      <td><?php echo $alimento['proteina']; ?></td>
                      <td><?php echo $alimento['carboidrato']; ?></td>
                      <td><?php echo $alimento['gordura']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php } ?>
      </div>
      <div class="dieta-botao">
      <button class="button-68" onclick="window.location.href='pacientes.php'">Voltar</button>
      <form id="excluirForm" method="POST">
  <button class="botao-excluir" type="submit" onclick="return confirmarExclusao()"><img src="../imagens/icons/lixo-branco.png" alt="lixeira branca"> Excluir dieta</button>
</form>

<script>
  function confirmarExclusao() {
    return confirm("Tem certeza de que deseja excluir a dieta?"); // Exibe o aviso de confirmação
  }
</script>


      </div>
    </div>
  </div>


</div>
</div>
  </div>
<div id="overlay" style="display: none;">
      <div id="overlay-content">
        <p>Você está prestes a deslogar da sua conta de nutricionista. Deseja continuar?</p>
        <div id="botoes-overlay">
        <button onclick="hideOverlay()">Não, voltar para a página anterior</button>
        <button onclick="logout()">Sim, deslogar</button>
        
        </div>
      </div>
    </div>
    

<footer class="footer-bg">
    <div class="footer container">
        <img src="../imagens/icons/logoBiteFit.svg" alt="BiteFit">
        <div class="footer-contato">
            <h3 class="font-2-l-b cor-0">Contato</h3>
            <ul class="font-2-m cor-5">
                <li><a href="tel:+5521999999999">+55 21 99999-9999</a></li>
                <li><a href="mailto:contato@bitefit.com">contato@biteFit.com</a></li>
            </ul>

            <div class="footer-redes">
                <a href="./">
                <img src="../imagens/redes/instagram.png" alt="Instagram"></a>
                <a href="./">
                    <img src="../imagens/icons/linkedin.png" alt="Linkedin"></a>
            </div>
        </div>
        <div class="footer-informacoes">
            <h3 class="font-2-l-b cor-0">Informações</h3>
            <nav>
                <ul class="font-1-m cor-5">
                    <li><a href="./perfil_nutricionista.php">Perfil</a></li>
                    <li><a href="./cadastro_paciente.php">Cadastrar Paciente</a></li>
                    <li><a href="./pacientes.php">Lista de Pacientes</a></li>
                    <li><a href="./consultas_nutri.php">Consultas</a></li>
                    <li><a href="./calculos.php">Cáculos Nutricionais</a></li>
                    
                    <li><a href="./sobre_nutricionista.html">Sobre</a></li>
                </ul>
            </nav>
        </div>
        <p class="footer-copy font-2-m cor-6"> Copyright © 2023 BiteFit. Todos os direitos reservados.</p>
    </div>
</footer>
    
<script src="../js/index.js"></script>
    
        </body>
    </html>