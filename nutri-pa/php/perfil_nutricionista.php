<?php
require_once "conexao.php";

// Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
  exit();
}

// Recupera as informações do nutricionista logado
$sql = "SELECT * FROM nutricionista WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['nutricionista_id']]);
$nutricionista = $stmt->fetch();


// Busca os dados do nutricionista
$sql = "SELECT * FROM nutricionista WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['nutricionista_id']]);
$nutricionista = $stmt->fetch();

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Atualiza os dados do nutricionista no banco de dados
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $telefone = $_POST['telefone'];
  $celular = $_POST['celular'];
  $crn = $_POST['crn'];
  $endereco = $_POST['endereco'];
  
  $sql = "UPDATE nutricionista SET nome = ?, email = ?, telefone = ?, celular = ?, crn = ?, endereco = ? WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$nome, $email, $telefone, $celular, $crn, $endereco, $_SESSION['nutricionista_id']]);
  
  // Redireciona para a página de perfil do nutricionista
  header("Location: perfil_nutricionista.php");
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
        <link rel="preload" href="../cssCerto/perfil-nutri.css" as="style">
        <link rel="stylesheet" href="../cssCerto/perfil-nutri.css">
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
		<li><a class="link-cadastro" href="./cadastro_paciente.php">Cadastrar Paciente</a></li>
		<li><a href="./pacientes.php">Lista de Pacientes</a></li>
		<li><a href="./consultas_nutri.php">Consultas</a></li>
		<li><a href="./calculos.php">Cáculos Nutricionais</a></li>
		<li><a href="./dietas.php">Dietas e Receitas</a></li>
  </ul>
</div>
<li><a href="./perfil_nutricionista.php">Perfil</a></li>
<li><a href="./sobre-nutricionista.html">Sobre</a></li>
<li><button class="deslogar" onclick="showOverlay()">Deslogar</button>
</nav>
</div>

</header>

<main>

<div class="container container-conteudo">
  <div class="container-conteudo-img">
  <img src="../imagens/editar-informacao.png" alt="">
  <h2>Nutricionista</h2>
  <p><strong>Nome:</strong> <?php echo $nutricionista['nome']; ?></p>
  <p><strong>CRN:</strong> <?php echo $nutricionista['crn']; ?></p>
  </div>
  
<div class="container container-pefil-nutri">
<div class="info-div">
  <h1>Bem vindo ao seu perfil!</h1>
  <div class="container-pequeno">
    
    <h2>Perfil</h2>
  <p><strong>Nome completo:</strong> <?php echo $nutricionista['nome']; ?></p>
  <p><strong>Email:</strong> <?php echo $nutricionista['email']; ?></p>
  </div>
  <div class="container-pequeno">
  <h2>Dados</h2>
  <p><strong>CRN:</strong> <?php echo $nutricionista['crn']; ?></p>
  <p><strong>Telefone:</strong> <?php echo $nutricionista['telefone']; ?></p>
  <p><strong>Celular:</strong> <?php echo $nutricionista['celular']; ?></p>
  <p><strong>Endereço:</strong> <?php echo $nutricionista['endereco']; ?></p>
  
<div class="botao-editar">
<button onclick="trocarDivs()" class="btn-editar">Editar informações</button>
</div>
  </div>
</div>


<div class="form-div" style="display: none;" id="form-div">
<h1>Edite suas informações:</h1>
	<form method="POST">
		<label for="nome">Nome:</label>
		<input type="text" id="nome" name="nome" value="<?php echo $nutricionista['nome']; ?>">
		<br>
		<label for="email">Email:</label>
		<input type="email" id="email" name="email" value="<?php echo $nutricionista['email']; ?>">
		<br>
		<label for="telefone">Telefone:</label>
		<input type="text" id="telefone" name="telefone" value="<?php echo $nutricionista['telefone']; ?>">
		<br>
		<label for="celular">Celular:</label>
		<input type="text" id="celular" name="celular" value="<?php echo $nutricionista['celular']; ?>">
		<br>
		<label for="crn">CRN:</label>
		<input type="text" id="crn" name="crn" value="<?php echo $nutricionista['crn']; ?>">
		<br>
		<label for="endereco">Endereço:</label>
		<input type="text" id="endereco" name="endereco" value="<?php echo $nutricionista['endereco']; ?>">
		<br>
		<input type="submit" value="Salvar">
	</form>
    <a href="perfil_nutricionista.php"><button>Voltar</button></a>
</div>
</div>





  </main>
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
        <img src="../imagens/logoBiteFit.svg" alt="BiteFit">
        <div class="footer-contato">
            <h3 class="font-2-l-b cor-0">Contato</h3>
            <ul class="font-2-m cor-5">
                <li><a href="tel:+5521999999999">+55 21 99999-9999</a></li>
                <li><a href="mailto:contato@bikcraft.com">contato@bikcraft.com</a></li>
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
<script src="logout.js"></script>
    
        </body>
    </html>
