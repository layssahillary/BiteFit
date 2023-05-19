<?php
  require_once "conexao.php";

    // Verifica se o usuário já está logado como paciente
  session_start();
  if (isset($_SESSION['paciente_id'])) {
    header("Location: inicio_paciente.php");
    exit();
  }

    // Inicializa as variáveis para exibição de erros e valores do formulário
  $email = "";
  $senha = "";
  $erro  = "";

    // Verifica se o formulário foi enviado
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

      // Obtém os valores do formulário
    $email = $_POST["email"];
    $senha = $_POST["senha"];

      // Verifica se o email e senha estão corretos
    $sql  = "SELECT id, nome FROM paciente WHERE email = ? AND senha = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $senha]);
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($paciente) {
        // Define a sessão de login para o paciente
      $_SESSION["paciente_id"]   = $paciente["id"];
      $_SESSION["paciente_nome"] = $paciente["nome"];

        // Redireciona para a dashboard do paciente
      header("Location: inicio_paciente.php");
      exit();
    } else {
      $erro = "Email ou senha inválidos.";
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500&display=swap" rel="stylesheet">
    <title>Login | BiteFit</title>
</head>
<body>
  <div class="container-login-nutri">

<div class="div">
    <a class="botao-voltar" href="index.html">
          <img src="../imagens/icons/seta-esquerda.png" alt="Meu botão">
    </a>
    

    <form method = "post">
    <img src="../imagens/icons/logoFila.svg" alt="logoBiteFit">
    <h1></h1>
    <div class="textfield">
      <label> Email: </label>
        <input type = "email" name = "email" value = "<?php echo $email; ?>">
    </div>

    <div class="textfield">
      <label> Senha:</label> 
        <input type = "password" name = "senha">
    </div>

    <?php if ($erro): ?>
      <p><?php echo $erro; ?></p>
    <?php endif; ?>

    <button class="botao-login-nutri" type = "submit">Entrar</button>

    </form>
  </div>
    <img class="img" src="../imagens/illustrations/Login.svg" alt="helth">
</div>
  </body>
</html>
