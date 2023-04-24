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
  $senha = $_POST['senha'];
  $objetivo = $_POST['objetivo'];

  // Verifica se os campos obrigatórios foram preenchidos
  if (empty($nome) || empty($email) || empty($data_nascimento) || empty($sexo) || empty($altura) || empty($peso) || empty($senha) || empty($objetivo)) {
    $mensagem = "Por favor, preencha todos os campos obrigatórios.";
  } else {
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
  $mensagem = "Preencha os dados do paciente abaixo.";
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
</head>
<body>
  <h1>Cadastro de Paciente</h1>
  <form method="POST" action="cadastro_paciente.php">
    <label for="nome">Nome completo:</label>
    <input type="text" id="nome" name="nome" required><br>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="data_nascimento">Data de Nascimento:</label>
    <input type="date" id="data_nascimento" name="data_nascimento" required><br>

    <label for="sexo">Sexo:</label>
      <select id="sexo" name="sexo">
        <option value="">Selecione</option>
        <option value="Masculino">Masculino</option>
        <option value="Feminino">Feminino</option>
        <option value="Outro">Outro</option>
      </select><br>

    <label for="altura">Altura (cm):</label>
    <input type="number" id="altura" name="altura" required><br>

    <label for="peso">Peso (kg):</label>
    <input type="number" id="peso" name="peso" required><br>

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required><br>

    <label for="objetivo">Qual o seu objetivo:</label>
    <input type="text" id="objetivo" name="objetivo" required><br>

    <button type="submit">Cadastrar</button>
    <button type="button" onclick="window.history.back()">Voltar</button>
  </form>
</body>
</html>

