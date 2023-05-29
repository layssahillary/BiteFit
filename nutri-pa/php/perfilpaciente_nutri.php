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


    // Verifica se foi passado um ID de paciente válido
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        header("Location: pacientes.php");
        exit();
      }
      
          // Busca as informações do paciente no banco de dados
      $stmt = $pdo->prepare("SELECT * FROM paciente WHERE id = ?");
      $stmt->execute([$_GET["id"]]);
      $paciente = $stmt->fetch(PDO::FETCH_ASSOC);
      
          // Verifica se o paciente existe no banco de dados
      if (!$paciente) {
        header("Location: pacientes.php");
        exit();
      }
      
          // Define as variáveis que serão usadas no formulário de edição
      $nome           = $paciente["nome"];
      $email          = $paciente["email"];
      $peso           = $paciente["peso"];
      $altura         = $paciente["altura"];
      $sexo           = $paciente["sexo"];
      $dataNascimento = $paciente["data_nascimento"];
      
          // Verifica se o formulário foi submetido
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Define as variáveis com os dados enviados pelo formulário
        $nome           = trim($_POST["nome"]);
        $email          = trim($_POST["email"]);
        $peso           = $_POST["peso"];
        $altura         = $_POST["altura"];
        $sexo           = $_POST["sexo"];
        $dataNascimento = $_POST["dataNascimento"];
      
            // Verifica se o nome foi preenchido
        if (empty($nome)) {
          $nomeErro = "Por favor, informe o nome do paciente.";
        }
      
            // Verifica se o email foi preenchido e se é válido
        if (empty($email)) {
          $emailErro = "Por favor, informe o email do paciente.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErro = "Por favor, informe um email válido.";
        }
      
            // Verifica se o peso foi preenchido e se é um número válido
        if (empty($peso)) {
          $pesoErro = "Por favor, informe o peso do paciente.";
        } elseif (!is_numeric($peso)) {
          $pesoErro = "Por favor, informe um peso válido.";
        }
      
            // Verifica se a altura foi preenchida e se é um número válido
        if (empty($altura)) {
          $alturaErro = "Por favor, informe a altura do paciente.";
        } elseif (!is_numeric($altura)) {
          $alturaErro = "Por favor, informe uma altura válida.";
        }
      
            // Verifica se o sexo foi preenchido
        if (empty($sexo)) {
          $sexoErro = "Por favor, informe o sexo do paciente.";
        }
      
            // Verifica se a data de nascimento foi preenchida e se é uma data válida
        if (empty($dataNascimento)) {
          $dataNascimentoErro = "Por favor, informe a data de nascimento do paciente.";
          } else {
          $dataNascimento = date("Y-m-d", strtotime($dataNascimento));
          $dataAtual      = date("Y-m-d");
          if ($dataNascimento >= $dataAtual) {
          $dataNascimentoErro = "Por favor, informe uma data de nascimento válida.";
          }
          }
          
          // Se não houver erros no preenchimento do formulário, atualiza as informações do paciente no banco de dados
          if (empty($nomeErro) && empty($emailErro) && empty($pesoErro) && empty($alturaErro) && empty($sexoErro) && empty($dataNascimentoErro)) {
          $stmt = $pdo->prepare("UPDATE paciente SET nome = ?, email = ?, peso = ?, altura = ?, sexo = ?, data_nascimento = ? WHERE id = ?");
          $stmt->execute([$nome, $email, $peso, $altura, $sexo, $dataNascimento, $_GET["id"]]);
          
              // Redireciona para a página de pacientes com mensagem de sucesso
          header("Location: pacientes.php?msg=success_edit");
          exit();
      }
      }
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Nutrição | BiteFit</title>
        <meta name="description" content="Home">
        <link rel="preload" href="../css/perfilpaciente_nutri.css" as="style">
        <link rel="stylesheet" href="../css/perfilpaciente_nutri.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500&display=swap" rel="stylesheet">

        <script>
function exibirDivExcluir(idPaciente) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("conteudoExcluir").innerHTML = this.responseText;
      document.getElementById("conteudoExcluir").classList.add("div-excluir");
    }
  };
  xhttp.open("GET", "excluir_paciente.php?id=" + idPaciente, true);
  xhttp.send();
}

function excluirPaciente() {
  var idPaciente = <?php echo $paciente['id']; ?>;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      window.location.href = "pacientes.php";
    }
  };
  xhttp.open("POST", "excluir_paciente.php?id=" + idPaciente, true);
  xhttp.send();
}
</script>


      

       
    </head>
<body>

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
        <h1>Perfil paciente</h1>
        </div>        
</div>

<div class="container">
<div class="container-pefil-paciente">
  <div class="container-pequeno">

  <div id="conteudo1">
  <div class="container-campos-img">
  <div class="container-campos">
  <h2>Dados do paciente</h2>
  <p><strong>Nome completo:</strong>  <?php echo $paciente['nome']; ?></p>
  <p><strong>Sexo:</strong>  <?php echo $paciente['sexo']; ?></p>
  <p><strong>Data de Nascimento:</strong>  <?php echo $paciente['data_nascimento']; ?></p>
  
  <p><strong>Idade:</strong>  <?php echo $paciente['idade'] . " anos"; ?></p>
  <p><strong>Altura:</strong>  <?php echo $paciente['altura']; ?> cm</p>
  <p><strong>Peso:</strong>  <?php echo $paciente['peso']; ?> kg</p>
  <p><strong>Objetivo do paciente:</strong>  <?php echo $paciente['objetivo']; ?></p>

  </div>
  <img src="../imagens/illustrations/profile.gif" alt="profile">
  </div>
  
  <div class="botoes-juntos">
  <div class="botao-agendar">
  <a class="button-68" href="./consultas_nutri.php"><img src="../imagens/icons/calendario.png" alt="">Agendar Consulta</a>
  
  
  <a class="button-68" href="./calculos.php"><img src="../imagens/icons/calculadora.png" alt="">Calculo Nutricional</a>
  </div>

  <div class="botoes">
  <a href="#" onclick="trocarDiv()">
  <img src="../imagens/icons/editar.png" alt="Descrição da imagem">
</a>

<div id="conteudoExcluir"></div>

<a href="#" onclick="exibirDivExcluir(<?php echo $paciente['id']; ?>)"><img src="../imagens/icons/lixeira.png" alt="excluir"></a>


  </div>
  </div>
  </div>
  
  

  <div id="conteudo2" style="display: none;">
  
    <form class="form-div" action="" method="POST">
    <h2>Editar paciente</h2>

      <div class="col-2">
      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>">
      </div>

      <div class="col-2">
      <label for="email">E-mail:</label>
      <input type="email" id="email" name="email" value="<?php echo $email; ?>">
      </div>
      
      <div class="col-1">
      <label for="peso">Peso (kg):</label>
      <input type="number" id="peso" name="peso" value="<?php echo $peso; ?>">
      </div>

      <div class="col-1">
      <label for="altura">Altura (cm):</label>
      <input type="number" id="altura" name="altura" value="<?php echo $altura; ?>">
      </div>
      
      <div class="col-1">
      <label for="sexo">Sexo:</label>
      <select id="sexo" name="sexo">
        <option name="">Selecione</option>
        <option value="Masculino" <?php if ($sexo == "Masculino") echo "selected"; ?>>Masculino</option>
        <option value="Feminino" <?php if ($sexo == "Feminino") echo "selected"; ?>>Feminino</option>
        <option value="Outro" <?php if ($sexo == "Outro") echo "selected"; ?>>Outro</option>
      </select>
      </div>
      
      <div class="col-1">
      <label for="dataNascimento">Data de nascimento:</label>
      <input type="date" id="dataNascimento" name="dataNascimento" value="<?php echo $dataNascimento; ?>">
      </div>

      <div class="botoes-editar col-2">
    <button class="button-68" type="submit">Salvar</button>
    <button class="button-68" type="button" onclick="window.history.back()">Voltar</button>
    </div>

    </form>
    </div>
    </div>
    </div>


  <div class="container-pequeno">
  <h2>Informações nutricionais</h2>
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
    
<script src="./../js/index.js"></script>
</body>
</html>
