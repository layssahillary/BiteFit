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

function trocarDivs() {
  var infoDiv = document.querySelector(".info-div");
  var formDiv = document.querySelector(".form-div");

  infoDiv.style.display = "none";
  formDiv.style.display = "block";
}

function confirmSubmit() {
  return confirm("Tem certeza que deseja salvar as informações para o paciente selecionado?");
}