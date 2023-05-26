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
  $celular = $_POST['celular'];
  $crn = $_POST['crn'];
  
  $sql = "UPDATE nutricionista SET nome = ?, email = ?, celular = ?, crn = ? WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$nome, $email, $celular, $crn, $_SESSION['nutricionista_id']]);
  
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
        <link rel="preload" href="../css/perfil-nutri.css" as="style">
        <link rel="stylesheet" href="../css/perfil-nutri.css">
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

<main>

<div class="container container-conteudo">
  <div class="container-conteudo-img">
  <img src="../imagens/icons/editar-informacao.png" alt="">
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
  <p><strong>Celular:</strong> <?php echo $nutricionista['celular']; ?></p>


  <div class="atendimento">
  <h3><strong>Atendimento:</strong></h3>
  <div class="horario">
  <h3>Horário de início:  </h3> <?php echo $nutricionista['horario_inicio']; ?>    |   
  <h3>Horário de fim:  </h3> <?php echo $nutricionista['horario_fim']; ?>
  </div>
  <p>
<?php 
$semana = explode(',', $nutricionista['dias_semana']);
foreach($semana as $dia) {
    echo $dia . ' ';
}
?>
</p>
</div>
  
<div class="botao-editar">
<button onclick="trocarDivs()" class="button-68">Editar</button>
</div>
  </div>
</div>


<div class="form-div" style="display: none;" id="form-div">
<h1>Edite suas informações:</h1>
	<form method="POST">

    <div class="col-2">
		<label for="nome">Nome:</label>
		<input type="text" id="nome" name="nome" value="<?php echo $nutricionista['nome']; ?>">
    </div>
		
    <div class="col-2">
		<label for="email">Email:</label>
		<input type="email" id="email" name="email" value="<?php echo $nutricionista['email']; ?>">
		</div>

    <div class="col-1">
		<label for="celular">Celular:</label>
		<input type="text" id="celular" name="celular" value="<?php echo $nutricionista['celular']; ?>">
    </div>

    <div class="col-1">
		<label for="crn">CRN:</label>
		<input type="text" id="crn" name="crn" value="<?php echo $nutricionista['crn']; ?>">
		</div>

    <div class="col-1">
    <label for="horario_inicio">Horário de Início:</label>
	  <input type="time" id="horario_inicio" name="horario_inicio" value="<?php echo $nutricionista['horario_inicio']; ?>">
	  </div>

    <div class="col-1">
    <label for="horario_fim">Horário de Fim:</label>
    <input type="time" id="horario_fim" name="horario_fim" value="<?php echo $nutricionista['horario_fim']; ?>">
    </div>

  <div class="col-2">
	<label for="dias_semana">Dias da Semana:</label>
  </div>

  <div class="checkbox col-2">
	<input type="checkbox" id="segunda" name="dias_semana[]" value="Segunda-Feira" <?php if (in_array("Segunda-Feira", explode(',', $nutricionista['dias_semana']))) echo 'checked'; ?>>
	<label for="segunda">Seg</label>

	<input type="checkbox" id="terca" name="dias_semana[]" value="Terça-Feira" <?php if (in_array("Terça-Feira", explode(',', $nutricionista['dias_semana']))) echo 'checked'; ?>>
	<label for="terca">Ter</label>

	<input type="checkbox" id="quarta" name="dias_semana[]" value="Quarta-Feira" <?php if (in_array("Quarta-Feira", explode(',', $nutricionista['dias_semana']))) echo 'checked'; ?>>
	<label for="quarta">Qua</label>

	<input type="checkbox" id="quinta" name="dias_semana[]" value="Quinta-Feira" <?php if (in_array("Quinta-Feira", explode(',', $nutricionista['dias_semana']))) echo 'checked'; ?>>
	<label for="quinta">Qui</label>

	<input type="checkbox" id="sexta" name="dias_semana[]" value="Sexta-Feira" <?php if (in_array("Sexta-Feira", explode(',', $nutricionista['dias_semana']))) echo 'checked'; ?>>
	<label for="sexta">Sex</label>

	<input type="checkbox" id="sabado" name="dias_semana[]" value="Sábado" <?php if (in_array("Sábado", explode(',', $nutricionista['dias_semana']))) echo 'checked'; ?>>
	<label for="sabado">Sáb</label>

	<input type="checkbox" id="domingo" name="dias_semana[]" value="Domingo" <?php if (in_array("Domingo", explode(',', $nutricionista['dias_semana']))) echo 'checked'; ?>>
	<label for="domingo">Dom</label>



    
</div>
<div class="btn-voltar col-2">
		<button class="button-68" type="submit" value="Salvar"> Salvar</button>
    <a href="perfil_nutricionista.php"><button class="button-68">Voltar</button></a>
</div>
</form>



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
