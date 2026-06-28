(function(){
    const form = document.getElementById('form-contato');
    const feedback = document.getElementById('feedback');
    const botaoLimpar = document.getElementById('limpar');

    if (!form) return;

    function mostrarErro(field, mensagem){
        const span = form.querySelector('.erro[data-for="' + field + '"]');
        if(span) span.textContent = mensagem;
    }

    function limparErros(){
        form.querySelectorAll('.erro').forEach( e => e.textContent = '' );
    }

    function validarEmail(email){
        return /^\S+@\S+\.\S+$/.test(email);
    }

    function validar(data){
        let valido = true;
        limparErros();

        if(!data.nome || data.nome.trim().length < 2){
            mostrarErro('nome', '(3 caracteres no mínimo).');
            valido = false;
        }

        if(!data.email || !validarEmail(data.email)){
            mostrarErro('email', 'Email inválido.');
            valido = false;
        }

        if(!data.mensagem || data.mensagem.trim().length < 10){
            mostrarErro('mensagem', 'Mensagem muito curta (mínimo 10 caracteres).');
            valido = false;
        }

        return valido;
    }

    form.addEventListener('submit', function(l){
        const formData = {
            nome: document.getElementById('nomeContato').value.trim(),
            email: document.getElementById('emailContato').value.trim(),
            mensagem: document.getElementById('mensagemContato').value.trim()
        };
        if(!validar(formData)){
            l.preventDefault(); // Impede o envio do formulário
            feedback.style.color = '#b00020';
            feedback.textContent = 'Corrija os campos destacados antes de enviar.';
            return;
        }
        feedback.style.color = '#0a7';
        feedback.textContent = 'Enviando sua mensagem...';
    });

    botaoLimpar.addEventListener('click', function(){
        limparErros();
        form.reset();
        feedback.textContent = '';
    });
})();