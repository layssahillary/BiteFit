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
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Nutrição | BiteFit</title>
        <meta name="description" content="Home">
        <link rel="preload" href="../css/dietapaciente_paciente.css" as="style">
        <link rel="stylesheet" href="../css/dietapaciente_paciente.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


      

       
    </head>

<body id="bitefit">

<header class="header-bg">
    <div class="header container">
        <a href="./inicio_paciente.php">
            <img src="../imagens/icons/logoBiteFit.svg" alt="BiteFit">
        </a>
        
        <nav class="links header-menu" aria-label="primaria">
<li><a href="perfilpaciente_paciente.php">Perfil</a></li>
<li><a href="dietapaciente_paciente.php">Dietas</a></li>
<li><a href="../ChatBite/index.html">ChatBot</a></li>
<li><a href="./sobre_paciente.html">Sobre</a></li>
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
          <h2>Sua Dieta</h2>
          <p>Nome: <?php echo $paciente['nome']; ?></p>
          <p>Objetivo: <?php echo $paciente['objetivo']; ?> </p>
        </div>
      </div>

      <?php
      $refeicoes = array();

      while ($refeicao = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $refeicao_id = $refeicao['refeicao_id'];

        if (!isset($refeicoes[$refeicao_id])) {
          $refeicoes[$refeicao_id] = array(
            'nome_refeicao' => $refeicao['nome_refeicao'],
            'dia_semana' => $refeicao['dia_semana'],
            'horario' => $refeicao['horario'],
            'alimentos' => array()
          );
        }

        $refeicoes[$refeicao_id]['alimentos'][] = $refeicao;
      }

      foreach ($refeicoes as $refeicao_id => $refeicao) {
      ?>
        <div class="container-refeicao">
          <strong>Refeição: </strong><?php echo $refeicao['nome_refeicao']; ?><br>
          <strong>Dia da semana: </strong><?php echo $refeicao['dia_semana']; ?><br>
          <strong>Horário: </strong><?php echo $refeicao['horario']; ?>
        </div>
        <div class="container-alimento">
          <table style="margin-top: 5px">
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
            <tbody>
              <?php foreach ($refeicao['alimentos'] as $alimento) { ?>
                <tr>
                  <td><?php echo $alimento['nome_alimento']; ?></td>
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
      <?php } ?>
      <div class="botao">
      <a class="button-68" href="inicio_paciente.php">Voltar</a>
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
                <li><a href="mailto:contato@bitefit.com">contato@bitefit.com</a></li>
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
                    <li><a href="./perfilpaciente_paciente.php">Perfil</a></li>
                    <li><a href="./dietapaciente_paciente.php">Dietas</a></li>
                    <li><a href="../ChatBite/index.html">ChatBot</a></li>
                    <li><a href="./sobre_paciente.html">Sobre</a></li>
                </ul>
            </nav>
        </div>
        <p class="footer-copy font-2-m cor-6"> Copyright © 2023 BiteFit. Todos os direitos reservados.</p>
    </div>
</footer>
    
<script src="../js/index.js"></script>
    
        </body>
    </html>
