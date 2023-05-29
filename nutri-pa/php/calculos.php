<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado como nutricionista
if (!isset($_SESSION['nutricionista_id'])) {
  header('Location: login_nutricionista.php');
  exit();
}

// Obtém o ID do nutricionista logado
$nutricionista_id = $_SESSION['nutricionista_id'];


// Obtém os pacientes do nutricionista
$stmt_pacientes = $pdo->prepare('SELECT * FROM paciente WHERE nutricionista_id = ?');
$stmt_pacientes->execute([$nutricionista_id]);
$pacientes = $stmt_pacientes->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Nutrição | BiteFit</title>
        <meta name="description" content="Home">
        <link rel="preload" href="../css/pacientes.css" as="style">
        <link rel="stylesheet" href="../css/calculos.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500&display=swap" rel="stylesheet">
      

       
    </head>

<body id="bitefit">

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
        <li><a href="./cadastro_dieta.php">Criar nova dieta</a></li>
  </ul>
</div>
<li><a href="./perfil_nutricionista.php">Perfil</a></li>
<li><a href="./sobre_nutricionista.html">Sobre</a></li>
<li><button class="deslogar" onclick="showOverlay()"><img src="../imagens/icons/logout-icon.svg" alt="descrição da imagem"></button>


</nav>
</div>
</header>
<div class="titulo-bg">
        <div class="titulo container">
        <h1>Calculos nutricionais></h1>
        </div>        
    </div>

    
    <?php
        require_once 'conexao.php';

        // Verifica se o usuário está logado como nutricionista
        if (!isset($_SESSION['nutricionista_id'])) {
          header('Location: login_nutricionista.php');
          exit();
        }

        // Obtém o ID do nutricionista logado
        $nutricionista_id = $_SESSION['nutricionista_id'];

        // Obtém os pacientes do nutricionista
        $stmt_pacientes = $pdo->prepare('SELECT * FROM paciente WHERE nutricionista_id = ?');
        $stmt_pacientes->execute([$nutricionista_id]);
        $pacientes = $stmt_pacientes->fetchAll();
    ?>
    <script>
        function confirmSubmit() {
             return confirm("Tem certeza que deseja salvar as informações para o paciente selecionado?");
        }
    </script>

<div class="container">
<div class="container-calculos">


<?php
$imc = null;
$gcd = null;
$peso = null;
$genero = null;
$altura = null;
$idade = null;
$nivel_atividade = null;


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_paciente'])) {
    $id_paciente = $_POST['id_paciente'];
    $peso = $_POST["peso"];
    $altura = $_POST["altura"];
    $idade = $_POST["idade"];
    $genero = $_POST["sexo"];
    $imc = $peso / (($altura/100)**2);
    $imc_formatado = number_format($imc, 2, ',', '');

    $nivel_atividade = $_POST["atividade"];

    // Calcula o metabolismo basal
    if ($genero == 'Feminino') {
        $taxa_metabolica = (655 + (9.6 * $peso) + (1.8 * $altura) - (4.7 * $idade));
    } elseif ($sexo == 'Masculino') {
        $taxa_metabolica = (66 + (13.7 * $peso) + (5 * $altura) - (6.8 * $idade));
    } else {
        $taxa_metabolica = (88.36 + (13.4 * $peso) + (4.8 * $altura) - (5.7 * $idade));
    }

    $gcd = $taxa_metabolica * $nivel_atividade;
    $proteinas = $peso * 2.2;
    $gorduras = $taxa_metabolica * 0.25 / 9;
    $carboidratos = ($gcd - ($proteinas * 4) - ($gorduras * 9)) / 4;

    echo "<div class='container-resultado'>";
    echo "<table>";
    echo "<tr>";
    echo "<th>Taxa Metabólica Basal(cal)</th>";
    echo "<th>O seu Gasto Calórico Diário é de(cal)</th>";
    echo "<th>Proteínas(g)</th>";
    echo "<th>Gorduras(g)</th>";
    echo "<th>Carboidratos(g)</th>";
    echo "<th>Seu IMC é:</th>";
    echo "</tr>";
    
    echo "<tr>";
    echo "<td>" . number_format($taxa_metabolica, 2) . "</td>";
    echo "<td>" . number_format($gcd, 2) . "</td>";
    echo "<td>" . number_format($proteinas, 2) . "</td>";
    echo "<td>" . number_format($gorduras, 2) . "</td>";
    echo "<td>" . number_format($carboidratos, 2) . "</td>";
    echo "<td>" . number_format($imc, 2) . "</td>";
    echo "</tr>";
    echo "</table>";
    
    
    
    
    echo "<div class='resultados'>";
    if($imc < 18.5){
        $resultado = "Paciente abaixo do peso.";
        echo "<p 
        style='color: #161616;   
        background-color: #caeefa;
        display: flex;
        padding: 8px;
        border-radius: 10px;
        margin-top: 10px;
        justify-content: center;
        border: 1px solid #2e76a3;
        text-align: justify;
        '>Paciente abaixo do peso. </p> ";

    }if($imc >= 18.5 && $imc <= 24.9){
        $resultado = "Paciente com IMC normal.";
        echo "<p 
        style='color: #161616;   
        background-color:  #bbf5b0;
        display: flex;
        padding: 8px;
        border-radius: 10px;
        margin-top: 10px;
        justify-content: center;
        border: 1px solid #316328;
        text-align: justify;
         '>Paciente com IMC normal.</p>";
    }if($imc >= 25 && $imc <= 29.9){
        $resultado = "Paciente com sobrepeso.";
        echo "<p
        style='color: #161616;   
        background-color: #f5f0b0;
        display: flex;
        padding: 8px;
        border-radius: 10px;
        margin-top: 10px;
        justify-content: center;
        border: 1px solid #9e9628;
        text-align: justify;
        '>Paciente com sobrepeso.</p>";
    }if($imc >= 30 && $imc <= 34.9){
        $resultado = "Paciente com obesidade grau I.";
        echo "<p 
        style='color: #161616;   
        background-color: #f5cdb0;
        display: flex;
        padding: 8px;
        border-radius: 10px;
        margin-top: 10px;
        justify-content: center;
        border: 1px solid #965627;
        text-align: justify;
        '>Paciente com obesidade grau I.</p>";
    }if($imc >= 35 && $imc <= 39.9){
        $resultado = "Paciente com besidade grau II.";
        echo "<p 
        style='color: #161616;   
        background-color: #f5b0b0;
        display: flex;
        padding: 8px;
        border-radius: 10px;
        margin-top: 10px;
        justify-content: center;
        border: 1px solid #8f3838;
        text-align: justify;
        '>Paciente com besidade grau II.</p>";
    }if($imc >39.9){
        $resultado = "Paciente com obesidade grau III.";
        echo "<p 
        style='color: #161616;   
        background-color: #60aae6;
        display: flex;
        padding: 8px;
        border-radius: 10px;
        margin-top: 10px;
        justify-content: center;
        border: 1px solid #cfb0f5;
        text-align: justify;
        '>Paciente com obesidade grau III.</p>";
    }
    echo "</div>";
    echo "</div>";
    // Defina a data atual
    $data = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO info_nutri (IMC, proteinas, carboidratos, gorduras, taxa_metabolica, GCD, resultado, paciente_id, data) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $params = array(
        $imc,
        $proteinas,
        $carboidratos,
        $gorduras,
        $taxa_metabolica,
        $gcd,
        $resultado,
        $id_paciente,
        $data
    );

    // Executando a consulta SQL
    $stmt->execute($params);
}

?>

    
    <form class="container form" method="post" onsubmit="return confirmSubmit()">
    <h2>Calcule os valores nutricionais</h2>
    <div class="col-2">
        <label for="id_paciente">Paciente:</label>
        <select name="id_paciente" id="id_paciente">
            <option value="">Selecione</option>
            <?php foreach ($pacientes as $paciente): ?>
                <option value="<?php echo $paciente['id'] ?>"><?php echo $paciente['nome'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-1">
        <label for="peso">Peso (kg):</label>
        <input type="number" name="peso" id="peso" value="">
    </div>

    <div class="col-1">
        <label for="altura">Altura (cm):</label>
        <input type="number" name="altura" id="altura" required>
    </div>

    <div class="col-1">
        <label for="idade">Idade:</label>
        <input type="number" name="idade" id="idade" required>
    </div>

    <div class="col-1">
        <label for="sexo">Gênero:</label>
            <select name="sexo" id="sexo" required>
                <option value="Feminino">Feminino</option>
                <option value="Masculino">Masculino</option>
                <option value="Outro">Outro</option>
            </select>
    </div>
    <script>
        const select = document.getElementById('id_paciente');
        const inputPeso = document.getElementById('peso');
        const inputAltura = document.getElementById('altura');
        const inputIdade = document.getElementById('idade');
        const selectGenero = document.getElementById('sexo');

        select.addEventListener('change', () => {
            const selectedPacienteId = select.value;

            // Percorre a lista de pacientes para encontrar as informações do paciente selecionado
            for (const paciente of <?php echo json_encode($pacientes) ?>) {
                if (+paciente.id === +selectedPacienteId) {
                    inputPeso.value = paciente.peso;
                    inputAltura.value = paciente.altura;
                    inputIdade.value = paciente.idade;
                    selectGenero.value = paciente.sexo;
                    break;
                }
            }
        });
    </script> 
        <div class="col-2">
        <label>Nível de atividade física:</label>
            <select name="atividade">
                <option value="1.2" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.2') ? 'selected' : '' ?>>Sedentário</option>
                <option value="1.375" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.375') ? 'selected' : '' ?>>Exercício leve (1-3 dias por semana)</option>
                <option value="1.55" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.55') ? 'selected' : '' ?>>Exercício moderado (3-5 dias por semana)</option>
                <option value="1.725" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.725') ? 'selected' : '' ?>>Exercício intenso (6-7 dias por semana)</option> 
                <option value="1.9" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.9') ? 'selected' : '' ?>>Exercício muito intenso (2 vezes por dia)</option>
            </select>
        </div>

        <div class="botoes-cadastro col-2">
        <button class="button-68" type="button" onclick="window.history.back()">Voltar</button>
        <button class="button-68" <input type="submit" value="Calcular">Calcular</button>
    
    </div>
    </form>

    

    </div>
    </div>

    
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
                <li><a href="mailto:contato@bitefit.com">contato@bitefit.com</a></li>
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
                    
                    <li><a href="./sobre_nutricionista.html">Sobre</a></li>
                </ul>
            </nav>
        </div>
        <p class="footer-copy font-2-m cor-6"> Copyright © 2023 BiteFit. Todos os direitos reservados.</p>
    </div>
</footer>
    
<script src="./../js/index.js"></script>
</body>
</html>
