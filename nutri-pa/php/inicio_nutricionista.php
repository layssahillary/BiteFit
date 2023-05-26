<!-- <?php
require_once "conexao.php";

 //Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
  exit();
}
?> -->


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Nutrição | BiteFit</title>
        <meta name="description" content="Home">
        <link rel="preload" href="../css/inicio-nutricionista.css" as="style">
        <link rel="stylesheet" href="../css/inicio-nutricionista.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500&display=swap" rel="stylesheet">
      

       
    </head>

<body id="bicicletas">

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
<li><a href="./sobre-nutricionista.html">Sobre</a></li>
<li><button class="deslogar" onclick="showOverlay()"><img src="../imagens/icons/logout-icon.svg" alt="descrição da imagem"></button>


</nav>
</div>
</header>

<main>
    <div class="card-inicio">
        <div class="card-titulo">
            <h1>Registre <br> seus <br> pacientes</h1>
            <p>Bem-vindo(a), nutricionista! Este é o seu portal de gerenciamento de pacientes e consultas. Aqui você pode facilmente cadastrar novos pacientes, criar planos de dieta personalizados, realizar cálculos nutricionais e gerenciar suas consultas agendadas.</p>
            <a class="button-68" href="./cadastro_paciente.php">Registrar</a>
        </div>
        <div class="card-imagem">
            <img class="imagem-card" src="../imagens/illustrations/inicio-nutri-img.svg" alt="">
        </div>
    </div>

    
        <div class="introducao">
        <a href="./perfilpaciente_nutri.php">
            <div class="conteudo-imagem">
            <div class="circulo-imagem">
            <div class="introducao-imagem">
                <img src="../imagens/icons/avatar-de-perfil.png" alt="avatar perfil">
            </div>
            </div>
            <div class="introducao-conteudo">
                <h2 class="font-2-xl">Perfil de pacientes</h2>
                <p class="font-2-s cor-8">Explore e edite cada perfil de paciente de forma personalizada</p>

            </div>
            </div>
        </a>

            <a href="./consultas_nutri.php">
            <div class="conteudo-imagem">
            <div class="circulo-imagem">
            <div class="introducao-imagem">
                <img src="../imagens/icons/consulta-medica.png" alt="medica consulta">
            </div>
            </div>
            <div class="introducao-conteudo">
                <h2 class="font-2-xl">Consultas</h2>
                <p class="font-2-s cor-8">Registre todas as consultas com seus pacientes de forma rápida</p>
            </div>
            </div>
            </a>

            <a href="./cadastro_dieta.php">
            <div class="conteudo-imagem">
            <div class="circulo-imagem">
                <div class="introducao-imagem">
                    <img src="../imagens/icons/dieta (2).png" alt="icone dieta">
                </div>
            </div>
                <div class="introducao-conteudo">
                    <h2 class="font-2-xl">Dietas</h2>
                    <p class="font-2-s cor-8">Personalize dietas para cada paciente de forma eficaz</p>
                </div>
                </div>
            </a>
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
    

</main>

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
                    
                    <li><a href="./sobre-nutricionista.html">Sobre</a></li>
                </ul>
            </nav>
        </div>
        <p class="footer-copy font-2-m cor-6"> Copyright © 2023 BiteFit. Todos os direitos reservados.</p>
    </div>
</footer>
    
<script src="../js/index.js"></script>
    
        </body>
    </html>
