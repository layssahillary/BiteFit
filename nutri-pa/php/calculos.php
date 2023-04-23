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
<html>
<head>
    <title>Calculadora Nutricional</title>
</head>
<body>
    <h1>Calculadora Nutricional</h1>
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
    <form method="post">
        <label for="id_paciente">Paciente:</label>
        <select name="id_paciente" id="id_paciente">
            <option value="">Selecione</option>
            <?php foreach ($pacientes as $paciente): ?>
                <option value="<?php echo $paciente['id'] ?>"><?php echo $paciente['nome'] ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label for="peso">Peso (kg):</label>
        <input type="number" name="peso" id="peso" value="">
        <br><br>
        <label for="altura">Altura (cm):</label>
        <input type="number" name="altura" id="altura" required>
        <br><br>
        <label for="idade">Idade:</label>
        <input type="number" name="idade" id="idade" required>
        <br><br>
        <label for="sexo">Gênero:</label>
        <input type="text" name="sexo" id="sexo" required>


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
                if (paciente.id === selectedPacienteId) {
                    inputPeso.value = paciente.peso;
                    inputAltura.value = paciente.altura;
                    inputIdade.value = paciente.idade;
                    selectGenero.value = paciente.sexo;
                    break;
                }
            }
        });
    </script>
        <label>Nível de atividade física:</label>
            <select name="atividade">
                <option value="1.2" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.2') ? 'selected' : '' ?>>Sedentário</option>
                <option value="1.375" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.375') ? 'selected' : '' ?>>Exercício leve (1-3 dias por semana)</option>
                <option value="1.55" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.55') ? 'selected' : '' ?>>Exercício moderado (3-5 dias por semana)</option>
                <option value="1.725" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.725') ? 'selected' : '' ?>>Exercício intenso (6-7 dias por semana)</option> 
                <option value="1.9" <?php echo (isset($nivel_atividade) && $nivel_atividade == '1.9') ? 'selected' : '' ?>>Exercício muito intenso (2 vezes por dia)</option>
            </select><br><br>
        <input type="submit" value="Calcular">
    </form>
</body>
</html>
<?php
$imc = null;
$gcd = null;
$peso = null;
$genero = null;
$altura = null;
$idade = null;
$nivel_atividade = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $peso = $_POST["peso"];
    $altura = $_POST["altura"] / 100;
    $idade = $_POST["idade"];
    $genero = $_POST["sexo"];
    $imc = $peso / ($altura * $altura);
    $nivel_atividade = $_POST["atividade"];

    if ($genero == "Masculino") {
        $taxa_metabolica = 88.36 + (13.4 * $peso) + (4.8 * $altura * 100) - (5.7 * $idade);
    } else {
        $taxa_metabolica = 447.6 + (9.2 * $peso) + (3.1 * $altura * 100) - (4.3 * $idade);
    }

    $gcd = $taxa_metabolica * $nivel_atividade;
    $proteinas = $peso * 2.2;
    $gorduras = $taxa_metabolica * 0.25 / 9;
    $carboidratos = ($gcd - ($proteinas * 4) - ($gorduras * 9)) / 4;

    echo "Taxa Metabólica Basal: " . number_format($taxa_metabolica, 2) . " calorias<br>";
    echo "<p>O seu Gasto Calórico Diário é de: " . number_format($gcd, 2) . " calorias por dia.</p>";
    echo "Proteínas: " . number_format($proteinas, 2) . " gramas<br>";
    echo "Gorduras: " . number_format($gorduras, 2) . " gramas<br>";
    echo "Carboidratos: " . number_format($carboidratos, 2) . " gramas";
    echo "<p>Seu IMC é: " . number_format($imc, 2) . "</p>";
    if($imc < 18.5){
        echo "<p>Você está abaixo do peso.</p>";
    }if($imc >= 18.5 && $imc <= 24.9){
        echo "<p>Seu peso está normal.</p>";
    }if($imc >= 25 && $imc <= 29.9){
        echo "<p>Você está com sobrepeso.</p>";
    }if($imc >= 30 && $imc <= 34.9){
        echo "<p>Você está com obesidade grau I.</p>";
    }if($imc >= 35 && $imc <= 39.9){
        echo "<p>Você está com obesidade grau II.</p>";
    }if($imc >39.9){
        echo "<p>Você está com obesidade grau III.</p>";
    }
    
}
?>