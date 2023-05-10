<?php
require_once "conexao.php";

// Verifica se o usuário está logado como paciente
session_start();
if (!isset($_SESSION['paciente_id'])) {
  header("Location: login_paciente.php");
  exit();
}

// Obtém as informações do paciente
$sql = "SELECT * FROM paciente WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['paciente_id']]);
$paciente = $stmt->fetch();

// Selecione as informações nutricionais do paciente ordenadas pela data
$stmt = $pdo->prepare("
    SELECT *
    FROM info_nutri
    WHERE paciente_id = ?
    ORDER BY data DESC
");
$stmt->execute([$_SESSION['paciente_id']]);
$info_nutri = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Perfil do Paciente</title>
    <meta charset="UTF-8">
</head>
<body>
	<h1>Seu perfil como paciente:</h1>
    <p><strong>Nome completo:</strong> <?php echo $paciente['nome']; ?></p>
    <p><strong>Sexo:</strong> <?php echo $paciente['sexo']; ?></p>
    <p><strong>Data de Nascimento:</strong> <?php echo $paciente['data_nascimento']; ?></p>
    <p><strong>Idade:</strong> <?php echo $paciente['idade'] . " anos"; ?></p>
    <p><strong>Altura:</strong> <?php echo $paciente['altura']; ?> m</p>
    <p><strong>Peso:</strong> <?php echo $paciente['peso']; ?> kg</p>
    <p><strong>Seu objetivo:</strong> <?php echo $paciente['objetivo']; ?></p>

    <h2>Histórico de Suas Informações nutricionais:</h2>
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

    <a href="inicio_paciente.php">Voltar para o início</a>
</body>
</html>
