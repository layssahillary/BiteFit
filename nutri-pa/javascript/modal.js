const camposFormulario = {
        nome: document.getElementById('nome'),
        email: document.getElementById('email'),
        cpf: document.getElementById('cpf'),
        senha: document.getElementById('senha'),
        data_nascimento: document.getElementById('data_nascimento'),
        sexo: document.getElementById('sexo'),
        altura: document.getElementById('altura'),
        peso: document.getElementById('peso')
        };
        var meuDialog = document.getElementById("meu-dialog");
        var abrirDialog = document.getElementById("abrir-dialog");
        var fecharDialog = document.getElementById("fechar-dialog");
        var formulario = document.getElementById("formulario");

        formulario.addEventListener('submit', (event) => {
        event.preventDefault();
        let formularioValido = true;
        Object.values(camposFormulario).forEach(campo => {
            if (!campo.checkValidity()) {
            formularioValido = false;
            }
        });
        if (formularioValido) {
            meuDialog.showModal();
        }
        });

        abrirDialog.addEventListener('click', () => {
        fecharDialog.focus();
        });

        fecharDialog.onclick = function() {
        meuDialog.close();
        formulario.submit(); // envia o formulário quando o diálogo é fechado
        };