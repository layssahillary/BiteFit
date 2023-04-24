<?php
    // Inclui o arquivo de conexão com o banco de dados
require_once "conexao.php";

    // Inicia a sessão
session_start();

    // Verifica se o usuário não está logado e redireciona para a página de login
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
  exit();
}

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
<html>
  <head>
    <title>Editar paciente</title>
    <meta charset="UTF-8">
  </head>
  <body>
    <h1>Editar paciente</h1>
    <form action="" method="POST">
      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>">
      <br>
      <label for="email">E-mail:</label>
      <input type="email" id="email" name="email" value="<?php echo $email; ?>">
      <br>
      <label for="peso">Peso (kg):</label>
      <input type="number" id="peso" name="peso" value="<?php echo $peso; ?>">
      <br>
      <label for="altura">Altura (cm):</label>
      <input type="number" id="altura" name="altura" value="<?php echo $altura; ?>">
      <br>
      <label for="sexo">Sexo:</label>
      <select id="sexo" name="sexo">
        <option name="">Selecione</option>
        <option value="Masculino" <?php if ($sexo == "Masculino") echo "selected"; ?>>Masculino</option>
        <option value="Feminino" <?php if ($sexo == "Feminino") echo "selected"; ?>>Feminino</option>
        <option value="Outro" <?php if ($sexo == "Outro") echo "selected"; ?>>Outro</option>
      </select>
      <br>
      <label for="dataNascimento">Data de nascimento:</label>
      <input type="date" id="dataNascimento" name="dataNascimento" value="<?php echo $dataNascimento; ?>">
      <br>
      <input type="submit" value="Salvar">
      <a href="perfilpaciente_nutri.php"><button>Voltar</button></a>
    </form>
  </body>
</html>

