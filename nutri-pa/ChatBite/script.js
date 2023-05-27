const input = document.querySelector('input')
const send = document.querySelector('button')
const chatContainer = document.querySelector('.chats')

send.onclick = () => {
    if(input.value){
        const message = `
            <div class="message">
                <div>
                    ${input.value}
                </div>
            </div>
        `
        chatContainer.innerHTML += message
        scrollDown();
        bot()
        input.value = null
    }
}

// when click enter
input.addEventListener("keypress", function(e){
    if(e.key === "Enter"){
        e.preventDefault();
        send.click();
    }
})

// scroll down when new message added
function scrollDown(){
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

// bot response
function bot(){
    var http = new XMLHttpRequest()
    var data = new FormData()
    data.append('prompt', input.value)
    http.open('POST', 'request.php', true)
    http.send(data)
    setTimeout(() => {
        chatContainer.innerHTML += `
            <div class="message response">
                <div>
                    <img src="img/preloader.svg" alt="preloader">
                </div>
            </div>
        `
        scrollDown();
    }, 1000);
    http.onload = () => {
        var response = JSON.parse(http.response)
        var replyText = processResponse(response.choices[0].text)
        var replyContainer = document.querySelectorAll('.response')
        replyContainer[replyContainer.length-1].querySelector('div').innerHTML = replyText
        scrollDown();
    }
}

function processResponse(res){
    var arr = res.split(':')
    return arr[arr.length-1]
        .replace(/(\r\n|\r|\n)/gm, '')
        .trim()
}

function showOverlay() {
    document.getElementById("overlay").style.display = "flex";
  }
  
  function hideOverlay() {
    document.getElementById("overlay").style.display = "none";
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