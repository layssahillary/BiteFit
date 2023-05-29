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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Termos e Condições">
  <link rel="preload" href="../css/cadastro-dieta.css" as="style">
  <link rel="stylesheet" href="../css/cadastro-dieta.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        
</head>
<body >

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
            <h1 class="font-1-xxl cor-0">Cadastrar Dieta</h1>
        </div>        
    </div>
   
  <form class="container-geral container" method="post" action="processa_dieta.php">
    <div class="container-info-geral">
    <h2>Selecione o <strong>paciente</strong> e <strong>data de validade</strong> da dieta</h2>
    <div class="container-info">
    <img src="../imagens/icons/perfil-escuro.png" alt="icon-perfil">
    <select name="id_paciente" id="id_paciente" onchange="checkID()">
      <option value="">Selecione</option>
      <?php foreach ($pacientes as $paciente): ?>
      <option value="<?php echo $paciente['id'] ?>" <?php if ('id_paciente' == $paciente['id']) echo 'selected' ?>><?php echo $paciente['nome'] ?></option>
      <?php endforeach; ?>
    </select>  
    <img src="../imagens/icons/calendario-escuro.png" alt="icon-calendario">
    <input type="date" name="data_validade" required>
    
    </div>
    <div id="aviso" style="visibility: hidden">
      <span><strong>Esse paciente já possui uma dieta!</strong>  <span>
    </div>
    <p class="fundo">Crie um plano de alimentação para seu paciente abaixo:</p>
    </div>
    
    <!-- Sendo adicionado pelo formulario no Javascript -->
    <div  id="formulario">
    </div>

    <div class="button-adicionar">
      <button class="button-img" type="button" onclick="adicionarRefeicao(false)"><img src="../imagens/icons/adicionar.png" alt="">Refeição</button>
      <button class="button-img " type="button" onclick="removerRefeicao()"><img src="../imagens/icons/fechar.png" alt="">Refeição</button>
      <button class="button-img button-last"  type="button" onclick="adicionarAlimento()"><img src="../imagens/icons/adicionar.png" alt="">Alimento</button>
      </div>
    <div class="form-group">
    
      <div class="form-buttons">
      <a class="button-68" href="pacientes.php">Voltar</a>
      <input  class="button-68"  type="submit" href="dietapaciente_nutri.php?id=<?php echo $paciente['id']; ?>" value="cadastrar" id="cadastrar" name="cadUsuario">
      </div>
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
      <div class=" form-group container-pequeno" id="ref` + contRef + `">
        <table >
          <label  for="refeicao"> Nome da refeição: </label>
          <input type="text" name="nome_refeicao[]" required>
          <div class="dia-horario">
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
          </div>
        </table>
      </div>`

      document.getElementById('formulario').insertAdjacentHTML('beforeend', html);
      
      if (contRef == 0)
      {
        adicionarAlimento(true);
      }

      adicionarAlimento();
    }


    function adicionarAlimento(hidden = false) {
  refTotal[contRef]++;
  console.log(refContAli[contRef]);
  var ind = 'ref' + contRef + 'ali' + refContAli[contRef];

  if (!hidden) {
    document.getElementById('formulario').insertAdjacentHTML('beforeend',
    '<div class=" borda form-group" id="' + ind + '">' +
      '<div class=" tables-container ">' +
      '<table>' +
      '<thead>' +
      '<tr>' +
      '<th class="invisivel"></th>' +
      '<th>Alimento</th>' +
      '<th>Quantidade</th>' +
      '<th>Unidade de medida</th>' +
      '<th>Calorias</th>' +
      '<th>Proteina</th>' +
      '<th>Carboidrato</th>' +
      '<th>Gordura</th>' +
      '<th>Remover</th>' +
      '</tr>' +
      '</thead>' +
      '<tbody>' +
      '<tr class="formulario-input" >' +
      '<td class="invisivel"><input hidden type="text" value="' + ind + '" name="ind[]"></td>' +
      '<td><input type="text" name="nome_alimento[]"></td>' +
      '<td><input type="number" name="quantidade[]"></td>' +
      '<td class="radio-buttons">' +
      '<input type="radio" name="' + ind + 'medidas[]" value="g" checked><span>g</span>' +
      '<input type="radio" name="' + ind + 'medidas[]" value="Un"><span>Un</span>' +
      '<input type="radio" name="' + ind + 'medidas[]" value="ml"><span>ml</span>' +
      '</td>' +
      '<td><input type="number" name="calorias[]"></td>' +
      '<td><input type="number" name="proteina[]"></td>' +
      '<td><input type="number" name="carboidrato[]"></td>' +
      '<td><input type="number" name="gordura[]"><input type="hidden" name="ref[]" value="' + contRef + '"></td>' +
      '<td><button  type="button" onclick="removerAlimento(' + contRef + ',' + refContAli[contRef] +')"><img src="../imagens/icons/excluir.png" alt="lixeira icone"></button></td>' +
      '</tr>' +
      '</tbody>' +
      '</table>' +
      '</div>' +
      '</div>'
    );
  } else {
    document.getElementById('formulario').insertAdjacentHTML('beforeend',
      '<div hidden class="form-group" id="' + ind + '">' +
      '<table>' +
      '<thead>' +
      '<tr>' +
      '<th></th>' +
      '<th>Alimento</th>' +
      '<th>Quantidade</th>' +
      '<th>Medida</th>' +
      '<th>Calorias</th>' +
      '<th>Proteina</th>' +
      '<th>Carboidrato</th>' +
      '<th>Gordura</th>' +
      '</tr>' +
      '</thead>' +
      '<tbody>' +
      '<tr>' +
      '<td><input hidden type="text" value="' + ind + '" name="ind[]"><input type="text" value="dummy" name="nome_alimento[]"></td>' +
      '<td><input type="number" name="quantidade[]"></td>' +
      '<td><input type="radio" name="' + ind + 'medidas[]" value="g" checked><span>g</span></td>' +
      '<td><input type="radio" name="' + ind + 'medidas[]" value="Un"><span>Un</span></td>' +
      '<td><input type="radio" name="' + ind + 'medidas[]" value="ml"><span>ml</span></td>' +
      '<td><input type="number" name="calorias[]"></td>' +
      '<td><input type="number" name="proteina[]"></td>' +
      '<td><input type="number" name="carboidrato[]"></td>' +
      '<td><input type="number" name="gordura[]"><input type="hidden" name="ref[]" value="' + contRef + '"></td>' +
      '<td><button  type="button" onclick="removerAlimento(' + contRef + ',' + refContAli[contRef] +')"><img src="../imagens/icons/excluir.png" alt="lixeira icone"></button></td>' +
      '</tr>' +
      '</tbody>' +
      '</table>' +
      '</div>'
    );
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

    function showMenu() {
  document.getElementById("menu").style.display = "block";
}

function hideMenu() {
  document.getElementById("menu").style.display = "none";
}

function keepMenu() {
  document.getElementById("menu").style.display = "block";
}


function showOverlay() {
  document.getElementById("overlay").style.display = "flex";
}

function hideOverlay() {
  document.getElementById("overlay").style.display = "none";
}

function logout() {
  fetch('logout_nutricionista.php')
    .then(response => {
      if (response.redirected) {
        window.location.href = response.url; // redireciona para a página de login
      } else {
        // exibe mensagem de erro ou faz outra coisa
      }
    })
    .catch(error => {
      console.error(error);
      // exibe mensagem de erro ou faz outra coisa
    });
}
  </script>
  
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
                <li><a href="mailto:contato@.com">contato@bitefit.com</a></li>
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

</body>
</html>