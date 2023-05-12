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
$horario_inicio = "";
$horario_fim    = "";
$dias_semana    = [];

// Define as variáveis que serão usadas para exibir mensagens de erro
$nomeErro           = "";
$emailErro          = "";
$senhaErro          = "";
$confirmarSenhaErro = "";
$telefoneErro       = "";
$celularErro        = "";
$crnErro            = "";
$horarioInicioErro  = "";
$horarioFimErro     = "";
$diasSemanaErro     = "";

  // Verifica se o formulário foi submetido
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Define as variáveis com os dados enviados pelo formulário
  $nome = trim($_POST["nome"]);
  $email = trim($_POST["email"]);
  $telefone = trim($_POST["telefone"]);
  $celular = trim($_POST["celular"]);
  $crn = trim($_POST["crn"]);
  $senha = $_POST["senha"];
  $confirmarSenha = $_POST["confirmarSenha"];
  $horario_inicio = $_POST["horario_inicio"];
  $horario_fim = $_POST["horario_fim"];
  $dias_semana = $_POST["dias_semana"];

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

  // Verifica se o email já existe no banco de dados
  $sql_verificar_email = "SELECT id FROM nutricionista WHERE email = ?";
  $stmt = $pdo->prepare($sql_verificar_email);
  $stmt->execute([$email]);

  if ($stmt->rowCount() > 0) {
  $emailErro = "Este email já está sendo utilizado.";
  } else{

 
  // Verifica se o CRN foi preenchido
  if (empty($crn)) {
    $crnErro = "Por favor, informe seu CRN.";
  }



  // Verifica se o horário de início e fim foram preenchidos
  if (empty($horario_inicio)) {
    $horarioInicioErro = "Por favor, informe o horário de início.";
  }

  if (empty($horario_fim)) {
    $horarioFimErro = "Por favor, informe o horário de fim.";
  }

  // Verifica se os dias da semana foram selecionados
  if (empty($dias_semana)) {
    $diasSemanaErro = "Por favor, selecione pelo menos um dia da semana.";
  }

  // Se não houver erros, insere o nutricionista no banco de dados
  if (empty($nomeErro) && empty($emailErro) && empty($senhaErro) && empty($confirmarSenhaErro) && empty($crnErro)  && empty($horarioInicioErro) && empty($horarioFimErro) && empty($diasSemanaErro)) {

    // Criptografa a senha
    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Insere o nutricionista no banco de dados
    $sql_inserir_nutricionista = "INSERT INTO nutricionista (nome, email, senha, telefone, celular, crn, horario_inicio, horario_fim, dias_semana) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql_inserir_nutricionista);
    $stmt->execute([$nome, $email, $senhaCriptografada, $telefone, $celular, $crn, $horario_inicio, $horario_fim, implode(',', $dias_semana)]);

    // Redireciona o nutricionista para a página de login
    header("Location: login_nutricionista.php");
    exit();
  }
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../cssCerto/cadastro-nutri.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500&display=swap" rel="stylesheet">
    
    <title>Cadastro nutricionista | BiteFit</title>
</head>
<body>
	
<div class= "container-nutri">

  <img src="./cadastro-nutricionista.svg" alt="ilustracao">


  <form class= "form-nutri"  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  
  <h1 class= " col-2" >Cadastro de Nutricionista</h1>
  <h2 class= " col-2">Dados Pessoais</h2>

  <div class= "col-2" >
  <label for="nome">Nome completo:</label>
  <input type="text" name="nome" id="nome" value="<?php echo $nome; ?>">
	<span><?php echo $nomeErro; ?></span>
  </div>

  <div class= "col-1">
  <label  for="telefone">Telefone:</label>
	<input type = "text" name = "telefone" id = "telefone" value = "<?php echo $telefone; ?>">
	<span><?php echo $telefoneErro; ?></span>
  </div>

  <div class= "col-1">
	<label   = "celular">Celular:</label>
	<input type = "text" name = "celular" id = "celular" value = "<?php echo $celular; ?>">
	<span><?php echo $celularErro; ?></span>
  </div>

  <div class= "col-2">
	<label for  = "crn">CRN:</label>
	<input type = "text" name = "crn" id = "crn" value = "<?php echo $crn; ?>">
	<span><?php echo $crnErro; ?></span>
  </div>


  <h2>Informações de usuário</h2>

  <div class= "col-2">
  <label for  = "email">E-mail:</label>
	<input type = "email" name = "email" id = "email" value = "<?php echo $email; ?>">
	<span><?php echo $emailErro; ?></span>
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
        <span><?php echo $confirmarSenhaErro;?></span>
    </div>
</div>
  
	

  <h2 class="col-2">Informações sobre as consultas</h2>

  <div class="col-1">
  <label for="horario_inicio">Horário de início:</label>
  <input type="time" id="horario_inicio" name="horario_inicio" value="<?php echo $horario_inicio; ?>">
  <span class="erro"><?php echo $horarioInicioErro; ?></span>
  </div>
  
  <div class="col-1">
  <label for="horario_fim">Horário de fim:</label>
  <input type="time" id="horario_fim" name="horario_fim" value="<?php echo $horario_fim; ?>">
  <span class="erro"><?php echo $horarioFimErro; ?></span>
  </div>
  
  
  <h2 class="col-2">Selecione os dias de atendimento</h2>
  <div class="checkbox col-2">
  <input type="checkbox" id="segunda" name="dias_semana[]" value="Segunda" <?php if(in_array('segunda', $dias_semana)) echo "checked"; ?>>

  <label for="segunda">Seg</label>

  <input type="checkbox" id="terca" name="dias_semana[]" value="Terça" <?php if(in_array('terca', $dias_semana)) echo "checked"; ?>>
  <label for="terca">Ter</label>

  <input type="checkbox" id="quarta" name="dias_semana[]" value="Quarta" <?php if(in_array('quarta', $dias_semana)) echo "checked"; ?>>
  <label for="quarta">Qua</label>

  <input type="checkbox" id="quinta" name="dias_semana[]" value="Quinta" <?php if(in_array('quinta', $dias_semana)) echo "checked"; ?>>
  <label for="quinta">Qui</label>

  <input type="checkbox" id="sexta" name="dias_semana[]" value="Sexta" <?php if(in_array('sexta', $dias_semana)) echo "checked"; ?>>
  <label for="sexta">Sex</label>

  <input type="checkbox" id="sabado" name="dias_semana[]" value="Sábado" <?php if(in_array('sabado', $dias_semana)) echo "checked"; ?>>
  <label for="sabado">Sáb</label>

  <input type="checkbox" id="domingo" name="dias_semana[]" value="Domingo" <?php if(in_array('domingo', $dias_semana)) echo "checked"; ?>>
  <label for="domingo">Dom</label>
  </div>
  
  <div class= "botao-submit col-2">
	<button class= "button-68" type="submit" > Cadastrar </button>
  </div>
  </div>

  

  
</form>


</div>

</body>
</html>
