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

// Obtém todas as dietas
$stmt_dieta = $pdo->prepare('SELECT * FROM dieta');
$stmt_dieta->execute();
$dieta = $stmt_dieta->fetchAll();

?>


<!DOCTYPE html>
<html>
<head>
  <title>Cadastro de Dieta</title>
</head>
<body>
  <h1>Cadastro de Dieta</h1>
  <form method="post" action="processa.php">
    <label for="id_paciente">Paciente:</label>
    <select name="id_paciente" id="id_paciente" onchange="checkID()">
      <option value="">Selecione</option>
      <?php foreach ($pacientes as $paciente): ?>
      <option value="<?php echo $paciente['id'] ?>" <?php if ('id_paciente' == $paciente['id']) echo 'selected' ?>><?php echo $paciente['nome'] ?></option>
      <?php endforeach; ?>
    </select><br><br>   
    <label for="data_validade">Data de Validade:</label>
    <input type="date" name="data_validade" required><br>
    
    <!-- Sendo adicionado pelo formulario no Javascript -->
    <div id="formulario"></div>

    <div class="form-group">
      <button type="button" onclick="adicionarAlimento()">Adicionar Alimento</button>
      <button type="button" onclick="adicionarRefeicao(false)">Adicionar Refeição</button>
      <button type="button" onclick="removerRefeicao()">Remover Refeição</button>
      <input type="submit" href="dietapaciente_nutri.php?id=<?php echo $paciente['id']; ?>" value="cadastrar" id="cadastrar" name="cadUsuario">
      <a href="inicio_nutricionista.php">Voltar para o início</a>
    </div>
    <div id="aviso" style="font-size: 80px; visibility: hidden">
      <mark style = "color: red"> ESTE PACIENTE JÁ POSSUI UMA DIETA! <mark>
      <img src = "https://media.tenor.com/7dygzcR-cvIAAAAC/danger-sign.gif">
    </div>
  </form>

  <script>
    let refTotal = [];
    let refContAli = [];
    var contRef = 0;
    

    function adicionarRefeicao(first)
    {
      if (!first)
      {
        contRef++;
      }
      refTotal.push(0);
      refContAli.push(0);
      console.log(contRef);

      let html = `
      <div class="form-group" id="ref` + contRef + `">
        <table>
          <label  for="refeicao"> <br><br><br>Nome das refeições: </label>
          <input type="text" name="nome_refeicao[]" required>
          <br><br>
          <label for="dia">Dia da Semana:</label>
          <select id="dia_semana" name="dia_semana[]">
            <option value="Segunda-feira">Segunda-feira</option>
            <option value="Terça-feira">Terça-feira</option>
            <option value="Quarta-feira">Quarta-feira</option>
            <option value="Quinta-feira">Quinta-feira</option>
            <option value="Sexta-feira">Sexta-feira</option>
            <option  value="Sábado">Sábado</option>
            <option value="Domingo">Domingo</option>
          </select>
          <input type="time" name="horario[]">
        </table>
      </div>`

      document.getElementById('formulario').insertAdjacentHTML('beforeend', html);
      
      if (contRef == 0)
      {
        adicionarAlimento(true);
      }

      adicionarAlimento();
    }


    function adicionarAlimento(hidden = false)
    {
      refTotal [contRef]++;
      console.log(refContAli[contRef]);
      var ind = 'ref' + contRef + 'ali' + refContAli[contRef];

      if(!hidden)
      {
        document.getElementById('formulario').insertAdjacentHTML('beforeend', '<div class="form-group" id="' + ind + '"><table><thead><tr><th></th><th>Alimento</th><th>Quantidade</th><th>Unidade de medida</th><th>Calorias</th><th>Proteina</th><th>Carboidrato</th><th>Gordura</th></tr></thead><tbody><tr><td><input hidden type="text" value="' + ind + '" name="ind[]"></td><td><input type="text" name="nome_alimento[]"></td><td><input type="number" name="quantidade[]"></td> <td><input type="radio" name="'+ ind + 'medidas[]" value="g" checked><span>g</span><input  type="radio" name="'+ ind + 'medidas[]" value="Un"><span>Un</span><input type="radio" name="'+ ind + 'medidas[]" value="ml"><span>ml</span>  </td><td><input type="number"  name="calorias[]"></td><td><input type="number" name="proteina[]"></td><td><input type="number" name="carboidrato[]"></td><td><input   type="number" name="gordura[]"><input type="hidden" name="ref[]" value="' + contRef + '"></td><td><button type="button" onclick="removerAlimento('+ contRef + ',' + refContAli[contRef] +')">Remover</ button></td></tr></tbody></table></div>');
      }
      else
      {
        document.getElementById('formulario').insertAdjacentHTML('beforeend', '<div hidden class="form-group" id="' + ind + '"><table><thead><tr><th></th><th>Alimento</th><th>Quantidade</th><th>Medida</th><th>Calorias</th><th>Proteina</th><th>Carboidrato</th><th>Gordura</th></tr></  thead><tbody><tr><td><input hidden type="text" value="' + ind + '" name="ind[]"><input type="text" value="dummy" name="nome_alimento[]"></td><td><input type="number" name="quantidade[]"><td><input type="radio" name="'+ ind + 'medidas[]" value="g" checked><span>g</span></td><td><input type="radio" name="'+ ind + 'medidas[]" value="Un"><span>Un</span></td><td><input type="radio" name="'+ ind + 'medidas[]" value="ml"><span>ml</span></td><td><input type="number"  name="calorias[]"></td><td><input type="number" name="proteina[]"></td><td><input type="number" name="carboidrato[]"></td><td><input   type="number" name="gordura[]"><input type="hidden" name="ref[]" value="' + contRef + '"></td><td><button type="button" onclick="removerAlimento('+ contRef + ',' + refContAli[contRef] +')">Remover</ button></td></tr></tbody></table></div>');
      }
      refContAli[contRef]++;
    }


    function removerRefeicao()
    {
      if (contRef <= 0)
        return;

      document.getElementById('ref' + contRef).remove();
      
      // Removendo todos os alimentos
      for (let i = 0; i < refContAli[contRef]; i++) 
      {
        removerAlimento(contRef, i, true);
      }

      // Resetando ultima refeição
      refTotal.pop();
      refContAli.pop();
      contRef--;
    }


    function removerAlimento(idRef, idAli, onDelete = false)
    {
      if (((idRef > 0 && (refTotal[idRef] > 1) || (idRef == 0 && refTotal[idRef] > 2)) || onDelete) && document.getElementById('ref'  + idRef + 'ali' + idAli) != null)
      {
        refTotal [idRef]--;
        document.getElementById('ref'  + idRef + 'ali' + idAli).remove();
      }
    }

    function checkID(){
      var idPaciente = document.getElementById('id_paciente').value;
      var dietas = <?php echo json_encode($dieta);?>;
      var botaoCadastrar = document.getElementById('cadastrar');
      var aviso = document.getElementById('aviso');

      for (let index = 0; index < dietas.length; index++) 
      {
        const element = dietas[index];
        
        if(idPaciente == dietas[index]['id_paciente'])
        {
          botaoCadastrar.disabled = true;
          aviso.style.visibility = "visible";
          return;
        }
      }
      botaoCadastrar.disabled = false;
      aviso.style.visibility = "hidden";
    }

    // Adicionando a primeira refeição
    adicionarRefeicao(true);
  </script>
</body>
</html>