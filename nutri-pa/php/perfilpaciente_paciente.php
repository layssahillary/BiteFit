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
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Nutrição | BiteFit</title>
        <meta name="description" content="Home">
        <link rel="preload" href="../cssCerto/perfilpaciente_paciente.css" as="style">
        <link rel="stylesheet" href="../cssCerto/perfilpaciente_paciente.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


      

       
    </head>

<body id="bicicletas">

<header class="header-bg">
    <div class="header container">
        <a href="./inicio_nutricionista.php">
            <img src="../imagens/logoBiteFit.svg" alt="BiteFit">
        </a>
        
        <nav class="links header-menu" aria-label="primaria">

	<a href="#" onmouseover="showMenu()">Pacientes</a>
<div id="menu" onmouseout="hideMenu()" onmouseover="keepMenu()">
  <ul class="header-menu  font-2-l cor-0">
      <li><a href="perfilpaciente_paciente.php">Perfil</a></li>
      <li><a href="dieta.php">Dietas</a></li>
      <li><a href="perfilnutricionista_paciente.php">Seu Nutricionista</a></li>
      <li><a href="consultas_paciente.php">Consultas</a></li>
      <li><a href="logout_paciente.html">Sair</a></li>
  </ul>
</div>
<li><a href="./perfil_nutricionista.php">Perfil</a></li>
<li><a href="./sobre-nutricionista.html">Sobre</a></li>
<li><button class="deslogar" onclick="showOverlay()"><img src="../imagens/logout-icon.svg" alt="descrição da imagem"></button>


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
        <img src="../imagens/comendo.gif" alt="mulher comendo">
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

    <div id="overlay" style="display: none;">
      <div id="overlay-content">
        <p>Você está prestes a deslogar da sua conta de nutricionista. Deseja continuar?</p>
        <div id="botoes-overlay">
        <button onclick="hideOverlay()">Não, voltar para a página anterior</button>
        <button onclick="logout()">Sim, deslogar</button>
        
        </div>
      </div>
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
        <img src="../imagens/doctor.gif" alt="mulher comendo">
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
    


    <footer class="footer-bg">
    <div class="footer container">
        <img src="../imagens/logoBiteFit.svg" alt="BiteFit">
        <div class="footer-contato">
            <h3 class="font-2-l-b cor-0">Contato</h3>
            <ul class="font-2-m cor-5">
                <li><a href="tel:+5521999999999">+55 21 99999-9999</a></li>
                <li><a href="mailto:contato@bitefit.com">contato@bitefit.com</a></li>
            </ul>

            <div class="footer-redes">
                <a href="./">
                <img src="../imagens/instagram.png" alt="Instagram"></a>
                <a href="./">
                    <img src="../imagens/linkedin.png" alt="Linkedin"></a>
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
                    <li><a href="./dietas.php">Dietas e Receitas</a></li>
                    
                    <li><a href="./sobre-nutricionista.html">Sobre</a></li>
                </ul>
            </nav>
        </div>
        <p class="footer-copy font-2-m cor-6"> Copyright © 2023 BiteFit. Todos os direitos reservados.</p>
    </div>
</footer>
    
<script src="js.js"></script>
    
        </body>
    </html>
