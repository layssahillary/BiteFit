<?php
require_once "conexao.php";

// Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
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
        <link rel="preload" href="../cssCerto/inicio-nutricionista.css" as="style">
        <link rel="stylesheet" href="../cssCerto/inicio-nutricionista.css">
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
        <a href="home-nutricionista.html">
            <img src="../imagens/logoBiteFit.svg" alt="BiteFit">
        </a>
        
        <nav class="links header-menu" aria-label="primaria">

	<a href="#" onmouseover="showMenu()">Pacientes</a>
<div id="menu" onmouseout="hideMenu()" onmouseover="keepMenu()">
  <ul class="header-menu  font-2-l cor-0">
		<li><a class="link-cadastro" href="./cadastro_paciente.php">Cadastrar Paciente</a></li>
		<li><a href="./pacientes.php">Lista de Pacientes</a></li>
		<li><a href="./consultas_nutri.php">Consultas</a></li>
		<li><a href="./calculos.php">Cáculos Nutricionais</a></li>
		<li><a href="./dietas.php">Dietas e Receitas</a></li>
  </ul>
</div>
<li><a href="./perfil_nutricionista.php">Perfil</a></li>
<li><a href="./sobre-nutricionista.html">Sobre</a></li>
<li><a href="./logout_nutricionista.html">Sair</a></li>


</nav>
</div>
</header>

<main>
    <div class="card-inicio">
        <div class="card-titulo">
            <h1>Registre <br> seus <br> pacientes</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe tenetur omnis, laboriosam possimus vero commodi sequi excepturi exercitationem optio obcaecati dolor nobis. Autem doloremque cumque, natus rem quasi vel porro.</p>
            <a class="button-68" href="./bicicletas/nimbus.html">Registrar</a>
        </div>
        <div class="card-imagem">
            <img class="imagem-card" src="../imagens/inicio-nutri-img.svg" alt="">
        </div>
    </div>

    
        <div class="introducao">
        <a href="./perfilpaciente_nutri.php">
            <div class="conteudo-imagem">
            <div class="circulo-imagem">
            <div class="introducao-imagem">
                <img src="../imagens/avatar-de-perfil.png" alt="bicicleta preta">
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
                <img src="../imagens/consulta-medica.png" alt="bicicleta preta">
            </div>
            </div>
            <div class="introducao-conteudo">
                <h2 class="font-2-xl">Consultas</h2>
                <p class="font-2-s cor-8">Registre todas as consultas com seus pacientes de forma rápida</p>
            </div>
            </div>
            </a>

            <a href="./">
            <div class="conteudo-imagem">
            <div class="circulo-imagem">
                <div class="introducao-imagem">
                    <img src="../imagens/dieta (2).png" alt="bicicleta preta">
                </div>
            </div>
                <div class="introducao-conteudo">
                    <h2 class="font-2-xl">Dietas</h2>
                    <p class="font-2-s cor-8">Personalize dietas para cada paciente de forma eficaz</p>
                </div>
                </div>
            </a>
        </div>

        
    

</main>

<footer class="footer-bg">
    <div class="footer container">
        <img src="../imagens/logoBiteFit.svg" alt="BiteFit">
        <div class="footer-contato">
            <h3 class="font-2-l-b cor-0">Contato</h3>
            <ul class="font-2-m cor-5">
                <li><a href="tel:+5521999999999">+55 21 99999-9999</a></li>
                <li><a href="mailto:contato@bikcraft.com">contato@bikcraft.com</a></li>
                <li>Rua Ali Perto, 42 - Botafogo</li>
                <li>Rio de Janeiro - RJ</li>
            </ul>

            <div class="footer-redes">
                <a href="./">
                <img src="img/redes/instagram.svg" alt="Instagram"></a>
                <a href="./">
                    <img src="img/redes/facebook.svg" alt="facebook"></a>
                    <a href="./">
                        <img src="img/redes/youtube.svg" alt="youtube"></a>
            </div>
        </div>
        <div class="footer-informacoes">
            <h3 class="font-2-l-b cor-0">Informações</h3>
            <nav>
                <ul class="font-1-m cor-5">
                    <li><a href="./bicicletas.html">Inicio</a></li>
                    <li><a href="./seguros.html">Perfil</a></li>
                    <li><a href="./contato.html">Receitas</a></li>
                    <li><a href="./termos.html">Contato</a></li>
                </ul>
            </nav>
        </div>
        <p class="footer-copy font-2-m cor-6"> BiteFit Alguns direitos reservados.</p>
    </div>
</footer>
    
<script src="js.js"></script>
<script src="javascript/carousel.js"></script>
    
        </body>
    </html>
