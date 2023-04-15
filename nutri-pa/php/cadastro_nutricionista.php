<?php
// Inclui o arquivo de conexão com o banco de dados
require_once "conexao.php";

// Inicia a sessão
session_start();

// Verifica se o usuário já está logado e redireciona para a página de pacientes
if (isset($_SESSION['nutricionista_id'])) {
  header("Location: inicio_nutricionista.php");
  exit();
}

// Define as variáveis que serão usadas no formulário de cadastro
$nome           = "";
$email          = "";
$senha          = "";
$confirmarSenha = "";
$telefone       = "";
$celular        = "";
$crn            = "";
$endereco       = "";

// Define as variáveis que serão usadas para exibir mensagens de erro
$nomeErro           = "";
$emailErro          = "";
$senhaErro          = "";
$confirmarSenhaErro = "";
$telefoneErro       = "";
$celularErro        = "";
$crnErro            = "";
$enderecoErro       = "";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Define as variáveis com os dados enviados pelo formulário
  $nome           = trim($_POST["nome"]);
  $email          = trim($_POST["email"]);
  $telefone       = trim($_POST["telefone"]);
  $celular        = trim($_POST["celular"]);
  $crn            = trim($_POST["crn"]);
  $endereco       = trim($_POST["endereco"]);
  $senha          = $_POST["senha"];
  $confirmarSenha = $_POST["confirmarSenha"];

  // Verifica se o nome foi preenchido
  if (empty($nome)) {
    $nomeErro = "Por favor, informe seu nome.";
  }

  // Verifica se o email foi preenchido e se é válido
  if (empty($email)) {
    $emailErro = "Por favor, informe seu email.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailErro = "Por favor, informe um email válido.";
  }

  // Verifica se a senha foi preenchida e se tem pelo menos 8 caracteres
  if (empty($senha)) {
    $senhaErro = "Por favor, informe uma senha.";
  } elseif (strlen($senha) < 8) {
    $senhaErro = "Sua senha deve ter pelo menos 8 caracteres.";
  }

  // Verifica se a confirmação de senha foi preenchida e se é igual à senha informada
  if (empty($confirmarSenha)) {
    $confirmarSenhaErro = "Por favor, confirme sua senha.";
  } elseif ($senha !== $confirmarSenha) {
    $confirmarSenhaErro = "As senhas não são iguais.";
  }

  // Se não houver erros de validação, insere o nutricionista no banco de dados
  if (empty($nomeErro) && empty($emailErro) && empty($senhaErro) && empty($confirmarSenhaErro)) {
    // Cria uma consulta SQL para inserir o nutricionista no banco de dados
    $sql = "INSERT INTO nutricionista (nome, email, telefone, celular, crn, endereco, senha) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepara a consulta SQL
    $stmt = $pdo->prepare($sql);
      
    // Executa a consulta SQL, passando os valores informados pelo usuário como parâmetros
  $stmt->execute([$nome, $email, $telefone, $celular, $crn, $endereco, password_hash($senha, PASSWORD_DEFAULT)]);

  // Armazena o ID do nutricionista recém-cadastrado na sessão
  $_SESSION["nutricionista_id"] = $pdo->lastInsertId();

  // Redireciona para a página de pacientes
  header("Location: inicio_nutricionista.php");
  exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Cadastro de Nutricionista</title>
  <meta charset="UTF-8">
</head>
<body>
	<h1>Cadastro de Nutricionista</h1>
  <form  method = "post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <label for    = "nome">Nome completo:</label>
  <input type   = "text" name   = "nome" id = "nome" value = "<?php echo $nome; ?>">
	<span><?php echo $nomeErro; ?></span><br>

	<label for  = "email">E-mail:</label>
	<input type = "email" name = "email" id = "email" value = "<?php echo $email; ?>">
	<span><?php echo $emailErro; ?></span><br>

  <label for  = "telefone">Telefone:</label>
	<input type = "text" name = "telefone" id = "telefone" value = "<?php echo $telefone; ?>">
	<span><?php echo $telefoneErro; ?></span><br>

	<label for  = "celular">Celular:</label>
	<input type = "text" name = "celular" id = "celular" value = "<?php echo $celular; ?>">
	<span><?php echo $celularErro; ?></span><br>

	<label for  = "crn">CRN:</label>
	<input type = "text" name = "crn" id = "crn" value = "<?php echo $crn; ?>">
	<span><?php echo $crnErro; ?></span><br>

	<label for  = "endereco">Endereço:</label>
	<input type = "text" name = "endereco" id = "endereco" value = "<?php echo $endereco; ?>">
	<span><?php echo $enderecoErro; ?></span><br>

	<label for  = "senha">Senha:</label>
	<input type = "password" name = "senha" id = "senha">
	<span><?php echo $senhaErro; ?></span><br>

	<label for  = "confirmarSenha">Confirmar senha:</label>
	<input type = "password" name = "confirmarSenha" id = "confirmarSenha">
	<span><?php echo $confirmarSenhaErro; ?></span><br>

	<input type = "submit" value = "Cadastrar">
</form>
</body>
</html>
