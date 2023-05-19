<?php
require_once "conexao.php";

// Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
  exit();
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Recebe os dados do formulário
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $data_nascimento = $_POST['data_nascimento'];
  $idade = calcularIdade($data_nascimento);
  $sexo = $_POST['sexo'];
  $altura = $_POST['altura'];
  $peso = $_POST['peso'];
  $confirmarSenha = $_POST['confirmarSenha'];
  $senha = $_POST['senha'];
  $objetivo = $_POST['objetivo'];

  // Verifica se os campos obrigatórios foram preenchidos
  if (empty($nome) || empty($email) || empty($data_nascimento) || empty($sexo) || empty($altura) || empty($peso) || empty($senha) || empty($objetivo)) {
    $mensagem = "Por favor, preencha todos os campos obrigatórios.";
  } else {
    // Verifica se a confirmação de senha foi preenchida e se é igual à senha informada
  if (empty($confirmarSenha)) {
    $confirmarSenhaErro = "Por favor, confirme sua senha.";
  } elseif ($senha !== $confirmarSenha) {
    $confirmarSenhaErro = "As senhas não são iguais.";
  }
    // Verifica se o email já está cadastrado
    $sql_verificar_email = "SELECT * FROM paciente WHERE email = ?";
    $stmt_verificar_email = $pdo->prepare($sql_verificar_email);
    $stmt_verificar_email->execute([$email]);

    if ($stmt_verificar_email->rowCount() > 0) {
      $mensagem = "O email informado já está cadastrado.";
    } else if (strlen($senha) < 8) {
      $mensagem = "A senha deve ter no mínimo 8 caracteres.";
    } else {
      // Insere os dados do paciente no banco de dados
      $sql  = "INSERT INTO paciente (nutricionista_id, nome, email, data_nascimento, idade, sexo, altura, peso, senha, objetivo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$_SESSION['nutricionista_id'], $nome, $email, $data_nascimento, $idade, $sexo, $altura, $peso, $senha, $objetivo]);

      // Define uma mensagem de sucesso e redireciona de volta para a lista de pacientes
      $mensagem = "Paciente cadastrado com sucesso.";
      header("Location: pacientes.php?novo_paciente=1");
      exit();
    }
  }
} else {
  // $mensagem = "Preencha os dados do paciente abaixo.";
}

// Função para calcular a idade a partir da data de nascimento
function calcularIdade($data_nascimento) {
  $agora = new DateTime();
  $nascimento = new DateTime($data_nascimento);
  $intervalo = $nascimento->diff($agora);
  return $intervalo->y;
}

// Exibe a mensagem de erro ou sucesso
if (isset($mensagem)) {
  echo "<p>{$mensagem}</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Cadastro de Paciente</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Termos e Condições">
  <link rel="preload" href="../css/cadastro-paciente.css" as="style">
  <link rel="stylesheet" href="../css/cadastro-paciente.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body >

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
		<li><a href="./dietas.php">Dietas e Receitas</a></li>
  </ul>
</div>
<li><a href="./perfil_nutricionista.php">Perfil</a></li>
<li><a href="./sobre-nutricionista.html">Sobre</a></li>
<li><button class="deslogar" onclick="showOverlay()"><img src="../imagens/icons/logout-icon.svg" alt="descrição da imagem"></button>
</nav>
</div>

</header>
  

  <main>
    <div class="titulo-bg">
        <div class="titulo container">
            <h1 class="font-1-xxl cor-0">Cadastrar Paciente<span class="cor-p1">.</span></h1>
        </div>        
    </div>

    <form class="container form" method="POST" action="cadastro_paciente.php">
    
    <div class="col-2">
    <h2> Dados pessoais </h2>
    </div>

    <div class="col-2">
    <label  for="nome">Nome completo:</label>
    <input  type="text" id="nome" name="nome" required><br>
    </div>

    <div class="col-1">
    <label  for="data_nascimento">Data de Nascimento:</label>
    <input  type="date" id="data_nascimento" name="data_nascimento" required><br>
    </div>

    <div class="col-1">
    <label for="sexo">Sexo:</label>
      <select id="sexo" name="sexo">
        <option value="">Selecione</option>
        <option value="Masculino">Masculino</option>
        <option value="Feminino">Feminino</option>
        <option value="Outro">Outro</option>
      </select><br>
      </div>

    <div class="col-1">
    <label for="altura">Altura(cm):</label>
    <input type="number" id="altura" name="altura" required><br>
    </div>

    <div class="col-1">
    <label for="peso">Peso(kg):</label>
    <input type="number" id="peso" name="peso" required><br>
    </div>

    <div class="col-2">
    <label for="objetivo">Qual o objetivo do paciente?:</label>
    <input type="text" id="objetivo" name="objetivo" required><br>
    </div>

    <div class="col-2">
    <h2>Informações de usuário</h2>
    </div>

    <div class="col-2">
    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required><br>
    </div>
    

    <div class="col-1">
    <label for="senha">Senha:</label>
    <div class="password-toggle">
        <input type="password" id="senha" name="senha" required>
        <i class="toggle-icon fas fa-eye" onclick="toggleSenha('senha')"></i>
    </div>
</div>

<div class="col-1">
    <label for="senha">Confirme a Senha:</label>
    <div class="password-toggle">
        <input type="password" id="confirmarSenha" name="confirmarSenha" required>
        <i class="toggle-icon fas fa-eye" onclick="toggleSenha('confirmarSenha')"></i>
    </div>
</div>



    <div class="botoes-cadastro col-2">
    <button class="button-68" type="submit"><img src="../imagens/icons/cadastro-icon.svg" alt="descrição da imagem">Cadastrar</button>
    <button class="button-68" type="button" onclick="window.history.back()">Voltar</button>
    </div>
  </form>

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
                <li><a href="mailto:contato@.com">contato@bitefit.com</a></li>
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
                    <li><a href="./dietas.php">Dietas e Receitas</a></li>
                    
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

