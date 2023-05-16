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

function logoutP() {
  fetch('logout_paciente.php')
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

function trocarDiv() {
  var div1 = document.getElementById("conteudo1");
  var div2 = document.getElementById("conteudo2");
  if (div1.style.display !== "none") {
    div1.style.display = "none";
    div2.style.display = "block";
  } else {
    div1.style.display = "block";
    div2.style.display = "none";
  }
}

function toggleSenha(inputId) {
  var senhaInput = document.getElementById(inputId);
  var toggleIcon = senhaInput.nextElementSibling;

  if (senhaInput.type === "password") {
      senhaInput.type = "text";
      toggleIcon.classList.remove("fa-eye");
      toggleIcon.classList.add("fa-eye-slash");
  } else {
      senhaInput.type = "password";
      toggleIcon.classList.remove("fa-eye-slash");
      toggleIcon.classList.add("fa-eye");
  }
}

const tabLinks = document.querySelectorAll('.tab-link');
const tabContents = document.querySelectorAll('.tab-content');

tabLinks.forEach(link => {
  link.addEventListener('click', () => {
    const tab = link.dataset.tab;

    tabLinks.forEach(link => link.classList.remove('active'));
    link.classList.add('active');

    tabContents.forEach(content => content.classList.remove('active'));
    document.getElementById(tab).classList.add('active');
  });
});

