<?php
require_once "conexao.php";

// Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
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
        <link rel="preload" href="../css/inicio-paciente.css" as="style">
        <link rel="stylesheet" href="../css/inicio-paciente.css">
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
        <a href="./inicio_paciente.php">
            <img src="../imagens/icons/logoBiteFit.svg" alt="BiteFit">
        </a>
        
        <nav class="links header-menu" aria-label="primaria">

<li><a href="perfilpaciente_paciente.php">Perfil</a></li>
<li><a href="dieta.php">Dietas</a></li>
<li><a href="./sobre_nutricionista.html">Sobre</a></li>
<li><button class="deslogar" onclick="showOverlay()"><img src="../imagens/icons/logout-icon.svg" alt="descrição da imagem"></button>


</nav>
</div>
</header>

<main>
    <div class="card-inicio">
        <div class="card-titulo">
            <h1>Explore<br>seu<br>perfil</h1>
            <p>Bem-vindo(a) ao seu portal de cuidados nutricionais personalizados! Aqui você terá acesso a recursos que o ajudarão a alcançar uma <strong>alimentação saudável e equilibrada</strong>. Através deste portal, você pode visualizar o seu <strong>perfil</strong>, acompanhar seus <strong>cálculos nutricionais </strong> individuais, explorar planos de <strong>dieta personalizados</strong> e manter o controle das suas <strong>consultas agendadas</strong> com o nutricionista.</p>
            <a class="button-68" href="./perfilpaciente_paciente.php">Perfil</a>
        </div>
        <div class="card-imagem">
            <img class="imagem-card" src="../imagens/icons/paciente.svg" alt="paciente">
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
                <h2 class="font-2-xl">Perfil</h2>
                <p class="font-2-s cor-8">Explore seu perfil e visualize suas informaçoes de forma personalizada</p>

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
                <p class="font-2-s cor-8">Verifique seus agendamentos de consultas com seu nutri!</p>
            </div>
            </div>
            </a>

            <a href="./">
            <div class="conteudo-imagem">
            <div class="circulo-imagem">
                <div class="introducao-imagem">
                    <img src="../imagens/icons/dieta (2).png" alt="icon dieta">
                </div>
            </div>
                <div class="introducao-conteudo">
                    <h2 class="font-2-xl">Dietas</h2>
                    <p class="font-2-s cor-8">Acompanhe suas dietas para uma alimentação saudavel!</p>
                </div>
                </div>
            </a>
        </div>

        
    
    
    <div id="overlay" style="display: none;">
      <div id="overlay-content">
        <p>Você está prestes a deslogar da sua conta de nutricionista. Deseja continuar?</p>
        <div id="botoes-overlay">
        <button onclick="hideOverlay()">Não, voltar para a página anterior</button>
        <button onclick="logoutP()">Sim, deslogar</button>
        
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
                    <li><a href="./perfilpaciente_paciente.php">Perfil</a></li>
                    <li><a href="./dieta.php">Dietas</a></li>
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