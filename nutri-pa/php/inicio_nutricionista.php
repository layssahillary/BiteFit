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
    <div class="titulo-bg">
        <div class="titulo container">
            <h1 class="font-1-xxl cor-0">Pacientes<span class="cor-p1">.</span></h1>
            <p class="font-1-m-b cor-5">Gerencie seus pacientes, acesso a informações</p>
            
        </div>

    </div>

    
        <div class="introducao conteinerhome">
            <div class="conteudo-imagem">
            <div class="introducao-imagem">
                <img src="img/salada.jpg" alt="bicicleta preta">
            </div>
            <div class="introducao-conteudo">
                <h2 class="font-2-xl">Perfil</h2>
                <p class="font-2-s cor-8">Explore seu perfil nutricional personalizado e tenha acesso a medidas importantes como IMC, além de informações sobre seus hábitos alimentares e necessidades nutricionais específicas e descubra como uma alimentação saudável e equilibrada pode ajudá-lo(a) a atingir seus objetivos de saúde e bem-estar.</p>
                <ul class="font-1-m cor-8">
                    <li>
                        <img src="imc.svg" alt="">
                        IMC
                    </li>
                    <li>
                        <img src="bmi.svg" alt="">
                        Medidas
                        </li>
                        
                </ul>
                <a class="botao seta" href="./bicicletas/nimbus.html">Mais Sobre</a>
            </div>
            </div>

            <div class="conteudo-imagem">
                <div class="introducao-imagem">
                    <img src="img/ello-AEU9UZstCfs-unsplash.jpg" alt="bicicleta preta">
                </div>
                <div class="introducao-conteudo">
                    <h2 class="font-2-xl">Dieta</h2>
                    <p class="font-2-s cor-8">Visualize todas as suas dietas personalizadas com dicas de receitas para melhorar seu dia a dia! Lorem ipsum dolor sit, amet consectetur adipisicing elit. Totam aperiam enim officia illum reprehenderit est tempore fugit facilis molestiae rem. Repudiandae soluta culpa dolores libero et similique voluptatibus obcaecati id.</p>
                    <ul class="font-1-m cor-8">
                        <li>
                            <img src="imc.svg" alt="">
                            IMC
                        </li>
                        <li>
                            <img src="bmi.svg" alt="">
                            Medidas
                            </li>
                            
                    </ul>
                    <a class="botao seta" href="./bicicletas/nimbus.html">Mais Sobre</a>
                </div>
                </div>
         
        </div>

        

        <div class="carousel-container">
            <div class="carousel">
              <img src="img/folhas.jpg">
              <img src="img/salada.jpg">
              <img src="img/avinash-kumar-JaoHjL6t0RM-unsplash.jpg">
              <img src="img/dan-gold-4_jhDO54BYg-unsplash.jpg">
              <img src="img/ello-AEU9UZstCfs-unsplash.jpg">
              <img src="img/folhas.jpg">
            </div>
            <button class="carousel-btn prev">&#10094;</button>
            <button class="carousel-btn next">&#10095;</button>
          </div>
        


          <article class="artigos container">
            <h2 class="font-1-xxl">Artigos <span class="cor-p1">.</span></h2>
            <div class="artigos-item">
                <img src="./img/folhas.jpg" alt="mapa marcando o endereço em rua ali perto, 25 - Rio de janeiro - RJ">
                <div class="artigos-conteudo">
                <div class="artigos-dados font-2-s cor-8">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestias libero blanditiis amet inventore debitis illum at pariatur ipsam assumenda. Provident consectetur, repudiandae at impedit commodi debitis aliquid iure? Reiciendis, dignissimos.</p>
                </div>
            </div>
         </div>

         <div class="artigos-item">
            <img src="./img/folhas.jpg" alt="mapa marcando o endereço em rua ali perto, 25 - Rio de janeiro - RJ">
            <div class="artigos-conteudo">
            <div class="artigos-dados font-2-s cor-8">
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestias libero blanditiis amet inventore debitis illum at pariatur ipsam assumenda. Provident consectetur, repudiandae at impedit commodi debitis aliquid iure? Reiciendis, dignissimos.</p>
            </div>
        </div>
     </div>
     </div>
        </article>
    


    

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
