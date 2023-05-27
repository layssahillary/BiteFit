<?php
require_once "conexao.php";

// Verifica se o usuário está logado como paciente
session_start();
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
  exit();
}

// Obtém as informações do paciente
$sql = "SELECT * FROM paciente WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['paciente_id']]);
$paciente = $stmt->fetch();

// Selecione as informações nutricionais do paciente ordenadas pela data
$stmt = $pdo->prepare("
    SELECT *
    FROM info_nutri
    WHERE paciente_id = ?
    ORDER BY data DESC
");
$stmt->execute([$_SESSION['paciente_id']]);
$info_nutri = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM nutricionista WHERE id = (SELECT nutricionista_id FROM paciente WHERE id = ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['paciente_id']]);
$nutricionista = $stmt->fetch();

// Obtém o ID do paciente logado
$paciente_id = $_SESSION['paciente_id'];

// Obtém as consultas do paciente ordenadas pela data em que foram agendadas
$stmt = $pdo->prepare('SELECT consulta.*, nutricionista.nome AS nutricionista_nome, consulta.descricao FROM consulta 
INNER JOIN nutricionista ON consulta.nutricionista_id = nutricionista.id WHERE consulta.paciente_id = ? ORDER BY data ASC');
$stmt->execute([$paciente_id]);
$consultas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Nutrição | BiteFit</title>
        <meta name="description" content="Home">
        <link rel="preload" href="../css/perfilpaciente_paciente.css" as="style">
        <link rel="stylesheet" href="../css/perfilpaciente_paciente.css">
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

<div class="container ">


    <div class="info-div">
    <div class="container-conteudo">
    <div class="dados-conteudo">
    <div class="conteudo-img">
    <div class="dados">
    <button id="before" onclick="trocarDivs()" >Perfil nutricionsita</button>
	<h2>Seu perfil</h2>
    <p><strong>Nome completo:</strong> <?php echo $paciente['nome']; ?></p>
    <p><strong>Sexo:</strong> <?php echo $paciente['sexo']; ?></p>
    <p><strong>Data de Nascimento:</strong> <?php echo $paciente['data_nascimento']; ?></p>
    <p><strong>Idade:</strong> <?php echo $paciente['idade'] . " anos"; ?></p>
    <p><strong>Altura:</strong> <?php echo $paciente['altura']; ?> m</p>
    <p><strong>Peso:</strong> <?php echo $paciente['peso']; ?> kg</p>
    <p><strong>Seu objetivo:</strong> <?php echo $paciente['objetivo']; ?></p>
    </div>
    <div class="dados-img">
        <img src="../imagens/illustrations/comendo.gif" alt="mulher comendo">
    </div>
    <div class="consultas">
    <h2>Minhas Consultas</h2>
  <?php if (count($consultas) > 0): ?>
    <table>
  <thead>
    <tr>
      <th>Data</th>
      <th>Horário</th>
      <th>Nutricionista</th>
      <th>Já foi realizada?</th>
      
    </tr>
  </thead>
  <tbody>
    <?php foreach ($consultas as $consulta): ?>
    <tr>
      <td><?php echo $consulta['data']; ?></td>
      <td><?php echo $consulta['horario']; ?></td>
      <td><?php echo $consulta['nutricionista_nome']; ?></td>
      <td><?php echo $consulta['realizada'] ? 'Sim' : 'Não'; ?></td>
      
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <?php else: ?>
    <p>Você não possui consultas agendadas.</p>
    <?php endif; ?>
    
    </div>
    </div>
    
    </div>
    

    <div class="dados-nutricionais">
    <h2>Histórico de Suas Informações nutricionais:</h2>
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
    
    </div>

    </div>

    </div>

    <div class="form-div" style="display: none;" id="form-div">
    <div class="container-conteudo">
    <div class="dados-conteudo-nutri">
    <div class="conteudo-img">
    <div class="dados">
    <a id="before2" href="perfilpaciente_paciente.php">Seu Perfil</a>
    <h2>Perfil do seu Nutricionista</h2>
	<p><strong>Nome:</strong><?php echo $nutricionista['nome']; ?></p>
	<p><strong>E-mail:</strong> <?php echo $nutricionista['email']; ?></p>
	<p><strong>Telefone:</strong> <?php echo $nutricionista['telefone']; ?></p>
	<p><strong>Celular:</strong> <?php echo $nutricionista['celular']; ?></p>	
	<p><strong>Endereço:</strong> <?php echo $nutricionista['endereco']; ?></p>
	<p><strong>CRN:</strong> <?php echo $nutricionista['crn']; ?></p>
    </div>
    <div class="dados-img">
        <img src="../imagens/illustrations/doctor.gif" alt="mulher comendo">
    </div>
    </div>
    </div>
    
    <div class="dados-nutricionais">
    <h2>Histórico de Suas Informações nutricionais:</h2>
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
