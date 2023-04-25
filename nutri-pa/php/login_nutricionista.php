<?php
  // Inicia a sessão
session_start();

  // Verifica se o usuário já está logado
if (isset($_SESSION['nutricionista_id'])) {
  header("Location: inicio_nutricionista.php");
  exit();
}

  // Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
  $email = $_POST['email'];
  $senha = $_POST['senha'];

    // Conecta ao banco de dados
  require_once 'conexao.php';

    // Busca o nutricionista pelo email
  $sql  = 'SELECT id, nome, senha FROM nutricionista WHERE email = ?';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
  $nutricionista = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o nutricionista foi encontrado e se a senha está correta
  if ($nutricionista && password_verify($senha, $nutricionista['senha'])) {
      // Armazena os dados do nutricionista na sessão
    $_SESSION['nutricionista_id']   = $nutricionista['id'];
    $_SESSION['nutricionista_nome'] = $nutricionista['nome'];

      // Redireciona para a página inicial
    header("Location: inicio_nutricionista.php");
    exit();
  } else {
      // Exibe uma mensagem de erro
    $erro = 'Email ou senha inválidos.';
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset = "utf-8">
  <title>Login Nutricionista</title>
</head>
<body>
  <h1>Login Nutricionista</h1>

  <?php if (isset($erro)): ?>
    <p><?php echo $erro; ?></p>
  <?php endif; ?>

  <form method = "POST">
    <div>
      <label for  = "email">Email:</label>
      <input type = "email" name = "email" required>
    </div>
    <div>
      <label for  = "senha">Senha:</label>
      <input type = "password" name = "senha" required>
    </div>
    <div>
      <button type = "submit">Entrar</button>
    </div>
  </form>
  <p>Não tem uma conta? <a href = "cadastro_nutricionista.php">Cadastre-se aqui</a>.</p>
  <a href="index.html"><button>Voltar para o Início</button></a>
</body>
</html>
