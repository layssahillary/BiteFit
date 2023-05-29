<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado como nutricionista
if (!isset($_SESSION['nutricionista_id'])) {
  header('Location: login_nutricionista.php');
  exit();
}

// Obtém o ID do nutricionista logado
$nutricionista_id = $_SESSION['nutricionista_id'];

// Obtém os pacientes do nutricionista
$stmt_pacientes = $pdo->prepare('SELECT * FROM paciente WHERE nutricionista_id = ?');
$stmt_pacientes->execute([$nutricionista_id]);
$pacientes = $stmt_pacientes->fetchAll();

// Verifica se o formulário de filtro de pacientes foi submetido
$paciente_id = null;
if (isset($_POST['filtro_pacientes'])) {
  $paciente_id = $_POST['paciente_id'];
}

// Obtém as consultas do nutricionista com o filtro de paciente, se houver
if ($paciente_id) {
  $stmt_consultas = $pdo->prepare('SELECT consulta.id, consulta.data, consulta.horario, consulta.realizada, paciente.nome as paciente_nome FROM consulta JOIN paciente ON consulta.paciente_id = paciente.id WHERE consulta.nutricionista_id = ? AND paciente.id = ?');
  $stmt_consultas->execute([$nutricionista_id, $paciente_id]);
} else {
  $stmt_consultas = $pdo->prepare('SELECT consulta.id, consulta.data, consulta.horario, consulta.realizada, paciente.nome as paciente_nome FROM consulta JOIN paciente ON consulta.paciente_id = paciente.id WHERE consulta.nutricionista_id = ?');
  $stmt_consultas->execute([$nutricionista_id]);
}

$consultas = $stmt_consultas->fetchAll();

// Verifica se o paciente selecionado possui consultas
if ($paciente_id && count($consultas) == 0) {
    echo 'Este paciente não possui consultas.';
  }  

// Verifica se o formulário de exclusão de consulta foi submetido
if (isset($_POST['excluir_consulta'])) {
  $consulta_id = $_POST['consulta_id'];

  // Remove a consulta do banco de dados
  $stmt = $pdo->prepare('DELETE FROM consulta WHERE id = ?');
  $stmt->execute([$consulta_id]);

  // Redireciona para a página de consultas
  header('Location: consultas_nutri.php');
  exit();
}

// Verifica se o formulário de marcação de consulta como realizada foi submetido
if (isset($_POST['marcar_realizada'])) {
  $consulta_id = $_POST['consulta_id'];

  // Marca a consulta como realizada no banco de dados
  $stmt = $pdo->prepare('UPDATE consulta SET realizada = 1 WHERE id = ?');
  $stmt->execute([$consulta_id]);

  // Redireciona para a página de consultas
  header('Location: consultas_nutri.php');
  exit();
}


// Verifica se o nutricionista já está logado e redireciona para a página de início do nutricionista
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
  exit();
}


// Obtém os dados do nutricionista
$stmt = $pdo->prepare('SELECT * FROM nutricionista WHERE id = ?');
$stmt->execute([$nutricionista_id]);
$nutricionista = $stmt->fetch();

// Verifica se o formulário de agendamento de consulta foi submetido
if (isset($_POST['agendar_consulta'])) {
  $data = $_POST['data'];
  $horario = $_POST['horario'];
  $paciente_id = $_POST['paciente_id'];

  // Verifica se já existe uma consulta agendada para o mesmo horário e dia
  $stmt = $pdo->prepare('SELECT COUNT(*) FROM consulta WHERE data = ? AND horario = ? AND nutricionista_id = ?');
  $stmt->execute([$data, $horario, $nutricionista_id]);
  $count = $stmt->fetchColumn();

  if ($count > 0) {
    // Consulta já agendada para o mesmo horário e dia
    $erro = "Já existe uma consulta agendada para o mesmo horário e dia.";
  } else {
    // Insere a consulta no banco de dados
    $stmt = $pdo->prepare('INSERT INTO consulta (data, horario, paciente_id, nutricionista_id) VALUES (?, ?, ?, ?)');
    $stmt->execute([$data, $horario, $paciente_id, $nutricionista_id]);
    
    // Redireciona para a página de consultas
    header('Location: consultas_nutri.php');
    exit();
  }
}

// Obtém os pacientes do nutricionista
$stmt = $pdo->prepare('SELECT * FROM paciente WHERE nutricionista_id = ?');
$stmt->execute([$nutricionista_id]);
$pacientes = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Nutrição | BiteFit</title>
        <meta name="description" content="Home">
        <link rel="preload" href="../css/consultas_nutri.css" as="style">
        <link rel="stylesheet" href="../css/consultas_nutri.css">
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
    <li><a href="./cadastro_dieta.php">Criar nova dieta</a></li>
  </ul>
</div>
<li><a href="./perfil_nutricionista.php">Perfil</a></li>
<li><a href="./sobre_nutricionista.html">Sobre</a></li>
<li><button class="deslogar" onclick="showOverlay()"><img src="../imagens/icons/logout-icon.svg" alt="descrição da imagem"></button>
</nav>
</div>
</header>

<div class="titulo-bg">
        <div class="titulo container">
        <h1>Consultas</h1>
        </div>        
 </div>


<div class="conteudo container">

<div class="info-div">
<div class="consultas">
  <div class="conteudo-consultas">
<button id="before" onclick="trocarDivs()" >Agendar Consulta</button>
  <h2>Acompanhe suas consultas</h2>
  <form method="post">
    <label for="paciente_id">Filtrar por paciente:</label>
    <select name="paciente_id" id="paciente_id">
      <option value="">Todos</option>
      <?php foreach ($pacientes as $paciente): ?>
        <option value="<?php echo $paciente['id'] ?>" <?php if ($paciente_id == $paciente['id']) echo 'selected' ?>><?php echo $paciente['nome'] ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" name="filtro_pacientes"><img src="../imagens/icons/lupa.png" alt=""></button>
  </form>
  <?php if (empty($consultas)): ?>
    <p class="avisos">Não há consultas agendadas.</p>
    <?php else: ?>
      <table>
  <thead>
    <tr>
      <th>Data</th>
      <th>Horário</th>
      <th>Paciente</th>
      <th>Realizada?</th>
      <th>Marcar como concluido</th>
      <th>Excluir consulta</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($consultas as $consulta): ?>
      <tr>
        <td><?php echo date('d/m/Y', strtotime($consulta['data'])) ?></td>
        <td><?php echo date('H:i', strtotime($consulta['horario'])) ?></td>
        <td><?php echo $consulta['paciente_nome'] ?></td>
        <td><?php echo $consulta['realizada'] ? 'Sim' : 'Não' ?></td>
        <td>

          <?php if (!$consulta['realizada']): ?>
            <form method="post" style="display: inline-block;">
              <input type="hidden" name="consulta_id" value="<?php echo $consulta['id'] ?>">
              <button type="submit" name="marcar_realizada"><img src="../imagens/icons/concluido.png" alt=""></button>
            </form>
          <?php endif; ?>
        </td>
        <td>
        <form method="post" style="display: inline-block;">
            <input type="hidden" name="consulta_id" value="<?php echo $consulta['id'] ?>">
            <button type="submit" name="excluir_consulta" onclick="return confirm('Tem certeza que deseja excluir esta consulta?')"><img src="../imagens/icons/excluir.png" alt=""></button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

  </div>
  </div>
  <div class="botao-voltar">
  <button class="button-68" type="button" onclick="window.history.back()">Voltar</button>
  </div>
</div>

  <div class="form-div" style="display: none;" id="form-div">
  <div class="cadastro">
  <div class="cadastrar-consulta">
  <a id="before2" href="consultas_nutri.php">Consultas Marcadas</a>
  <h2>Agendar Consulta</h2>
  <?php if (isset($erro)): ?>
  <p style="color: red;"><?php echo $erro; ?></p>
  <?php endif; ?>
  <form class="form-cadastrar" method="post">
    <label>Paciente:</label>
    <select name="paciente_id" required>
      <option value="">Selecione um paciente</option>
      <?php foreach ($pacientes as $paciente): ?>
      <option value="<?php echo $paciente['id']; ?>"><?php echo $paciente['nome']; ?></option>
      <?php endforeach; ?>
    </select><br><br>
    <div class="form-data">
    <div class="col-1">
    <label>Data:</label>
    <input type="date" name="data" required>
    </div>
    <div class="col-1">
    <label>Horário:</label>
    <input type="time" name="horario" required>
    </div>
    </div>
    <div class="botao-agendar">
    <button class="button-68" type="submit" name="agendar_consulta" value="Agendar Consulta"> <img src="../imagens/icons/calendario.png" alt="">Agendar Consulta</button>
    </div>
  </form>
  <br>
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