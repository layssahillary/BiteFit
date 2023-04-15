<?php
require_once "conexao.php";

// Verifica se o usuário está logado como nutricionista
session_start();
if (!isset($_SESSION['nutricionista_id'])) {
  header("Location: login_nutricionista.php");
  exit();
}

// Busca os dados do nutricionista
$sql = "SELECT * FROM nutricionista WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['nutricionista_id']]);
$nutricionista = $stmt->fetch();

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Atualiza os dados do nutricionista no banco de dados
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $telefone = $_POST['telefone'];
  $celular = $_POST['celular'];
  $crn = $_POST['crn'];
  $endereco = $_POST['endereco'];
  
  $sql = "UPDATE nutricionista SET nome = ?, email = ?, telefone = ?, celular = ?, crn = ?, endereco = ? WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$nome, $email, $telefone, $celular, $crn, $endereco, $_SESSION['nutricionista_id']]);
  
  // Redireciona para a página de perfil do nutricionista
  header("Location: perfil_nutricionista.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Editar Nutricionista</title>
    <meta charset="UTF-8">
</head>
<body>
	<h1>Edite suas informações:</h1>
	<form method="POST">
		<label for="nome">Nome:</label>
		<input type="text" id="nome" name="nome" value="<?php echo $nutricionista['nome']; ?>">
		<br>
		<label for="email">Email:</label>
		<input type="email" id="email" name="email" value="<?php echo $nutricionista['email']; ?>">
		<br>
		<label for="telefone">Telefone:</label>
		<input type="text" id="telefone" name="telefone" value="<?php echo $nutricionista['telefone']; ?>">
		<br>
		<label for="celular">Celular:</label>
		<input type="text" id="celular" name="celular" value="<?php echo $nutricionista['celular']; ?>">
		<br>
		<label for="crn">CRN:</label>
		<input type="text" id="crn" name="crn" value="<?php echo $nutricionista['crn']; ?>">
		<br>
		<label for="endereco">Endereço:</label>
		<input type="text" id="endereco" name="endereco" value="<?php echo $nutricionista['endereco']; ?>">
		<br>
		<input type="submit" value="Salvar">
	</form>
    <a href="perfil_nutricionista.php"><button>Voltar</button></a>
</body>
</html>
