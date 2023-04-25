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
    <meta charset = "utf-8">
    <title>Login do paciente</title>
  </head>
  <body>
    <h1>Login do paciente</h1>
    <?php if ($erro): ?>
      <p style = "color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form method = "post">
      <label>
        Email: 
        <input type = "email" name = "email" value = "<?php echo $email; ?>">
      </label><br><br>
      <label>
        Senha: 
        <input type = "password" name = "senha">
      </label><br><br>
      <button type = "submit">Entrar</button>
    </form>
    <br>
    <a href="index.html"><button>Voltar para o Início</button></a>
  </body>
</html>
