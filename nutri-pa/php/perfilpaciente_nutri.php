<?php
require_once 'conexao.php';

// Verifica se o usuário é nutricionista antes de continuar
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header('Location: login_nutricionista.php');
  exit();
}

// Verifica se foi passado o ID do paciente
if (!isset($_GET['id'])) {
  header('Location: pacientes.php');
  exit();
}

// Recupera as informações do paciente
$stmt = $pdo->prepare('SELECT * FROM paciente WHERE id = ? AND nutricionista_id = ?');
$stmt->execute([$_GET['id'], $_SESSION['nutricionista_id']]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o paciente existe e pertence ao nutricionista
if (!$paciente) {
  header('Location: pacientes.php');
  exit();
}

// Selecione as informações nutricionais do paciente ordenadas pela data
$stmt = $pdo->prepare("
    SELECT *
    FROM info_nutri
    WHERE paciente_id = ?
    ORDER BY data DESC
");

$stmt->execute([$_GET['id']]);
$info_nutri = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Nutrição | BiteFit</title>
        <meta name="description" content="Home">
        <link rel="preload" href="../cssCerto/perfilpaciente_nutri.css" as="style">
        <link rel="stylesheet" href="../cssCerto/perfilpaciente_nutri.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500&display=swap" rel="stylesheet">
      

       
    </head>
<body>

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

<div class="titulo-bg">
        <div class="titulo container">
        <h1>Perfil paciente</h1></h1>
        </div>        
</div>

<div class="container">
<div class="container-pefil-paciente">
  <div class="container-pequeno">
  <h2>Dados do paciente</h2>
  <p><strong>Nome completo:</strong> <br> <?php echo $paciente['nome']; ?></p>
  <p><strong>Sexo:</strong> <br> <?php echo $paciente['sexo']; ?></p>
  <p><strong>Data de Nascimento:</strong> <br> <?php echo $paciente['data_nascimento']; ?></p>
  <p><strong>Idade:</strong> <br> <?php echo $paciente['idade'] . " anos"; ?></p>
  <p><strong>Altura:</strong> <br> <?php echo $paciente['altura']; ?> cm</p>
  <p><strong>Peso:</strong> <br> <?php echo $paciente['peso']; ?> kg</p>
  <p><strong>Objetivo do paciente:</strong> <br> <?php echo $paciente['objetivo']; ?></p>
  <div class="botoes">
  <a href="editar_paciente.php?id=<?php echo $paciente['id']; ?>"><img src="../imagens/editar.png" alt="Editar"></a>

  <a href="excluir_paciente.php?id=<?php echo $paciente['id']; ?>"><img src="../imagens/lixeira.png" alt="excluir"></a>
  </div>
  </div>

  <div class="container-pequeno">
  <h2>Histórico das Informações nutricionais do paciente:</h2>
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
    <div class="botao-voltar">
    <a class="button-68" href="pacientes.php">Voltar</a>
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
    
<script src="./js.js"></script>
</body>
</html>
