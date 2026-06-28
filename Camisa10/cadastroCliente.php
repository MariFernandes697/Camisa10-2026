<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastre-se - Camisa 10</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/menu-rodape.css">

<style>
.banner-login {
    width: 50%;
    height: 110vh; /* Acompanha dinamicamente a altura do formulário */
    background: #620b0b;
    background: linear-gradient(rgba(0, 0, 0, 0.693), rgba(0, 0, 0, 0.293)), url('assets/banners/bannerContato.png');
    background-size: cover;
    background-position: center;
}
.login {
    text-align: justify;
    background-color: transparent;
    padding: 30px 40px;
}
.login h2 {
    font-size: 1.5em;
    text-align: center;
    font-weight: 700;
    color: var(--cor-fonte);
    margin-bottom: 20px;
}
.row-input {
    display: flex;
    gap: 16px;
    width: 100%;
}
.login .input-login {
    width: 100%; 
    margin-bottom: 10px; 
}
.input-login label {
    display: block;
    color: var(--cor-fonte);
    font-size: 14px;
    margin-bottom: 6px;
    font-weight: 600;
}
.input-login input {
    color: var(--cor-fonte);
    width: 100%;
    height: 50px;
    border: none;
    outline: none;
    border: 1px solid #000000b5;
    border-radius: 16px;
    font-size: 16px;
    padding: 0 20px;
    box-sizing: border-box;
}
/* Ajuste para o input de arquivo do Bootstrap */
.input-login input[type="file"] {
    padding-top: 12px;
}
.container-btn {
    width: 100%;
    display: flex;
    justify-content: center;
    margin: 20px 0;
}
.login .btn {
    width: 70%;                
    height: 45px;
    background-color: #ff0000;
    border: none;
    outline: none;
    border-radius: 36px;
    box-shadow: 0 0 8px #2501015c;
    cursor: pointer;
    font-size: 16px;
    color: #fff;
    font-weight: 700;
    margin: 8px 0;
}
.login .btn:hover {
    background-color: #000;
    color: #fff;
}
.login .link-registro {
    font-size: 14.5px;
    text-align: center;
    margin-top: 20px;
    line-height: 40px;
    font-size: 0.9em;
    color: var(--cor-fonte);
}
.link-registro p > a {
    text-decoration: underline;
    margin-top: 10px;
    font-weight: 700;
    color: var(--cor-destaque);
}
.error-message {
    color: var(--cor-destaque);
    font-size: 0.85rem;
    margin-top: 4px;
    min-height: 18px;
    text-align: left;
    padding-left: 6px;
}
input.is-invalid {
    box-shadow: 0 0 0 3px rgba(176, 0, 32, 0.08);
    border-color: var(--cor-destaque);
}
footer {
    margin-top: 0px;
}

/* === RESPONSIVIDADE PARA CELULAR === */
@media (max-width: 900px) {
    main {
        margin-top: 70px;
    }
    .row-input {
        flex-direction: column; 
        gap: 0;
    }
}
</style>
<main class="container-fluid p-0 min-vh-100 d-flex flex-column justify-content-between">
    <?php

        //Verifica se há algum parâmetro chamado 'erroLogin' sendo recebido por GET
        if(isset($_GET['erroLogin'])){
            $erroLogin = $_GET['erroLogin'];

            if($erroLogin == 'dadosInvalidos'){
                echo "<div class='alert alert-warning text-center'><strong>USUÁRIO ou SENHA</strong> inválidos!</div>";
            }
        }

    ?>
    <div class="row g-0 flex-grow-1 align-items-stretch">
        
        <div class="col-md-6 d-none d-md-block banner-login">
            <a href="index.php">
                <img src="assets/logos/logo1.png" alt="Logo Camisa 10" width="180px" class="mt-5 ms-5">
            </a>
        </div>
        
        <div class="col-12 col-md-6 d-flex justify-content-center align-items-center p-4">
            <div class="login w-100" style="max-width: 550px;">
                <form id="loginForm" action="actionCadastroCliente.php" method="POST" class="was-validated" enctype="multipart/form-data">
                    <h2>Cadastre-se</h2>

                    <div class="input-login">
                        <label for="fotoCliente">Foto de Perfil</label>
                        <input type="file" class="form-control" id="fotoCliente" name="fotoCliente" required>
                        <div class="error-message" id="error-cliente" aria-live="polite"></div>
                    </div>

                    <div class="row-input">
                        <div class="input-login">
                            <label for="nomeCliente">Nome Completo</label>
                            <input id="nomeCliente" name="nomeCliente" type="text" placeholder="Nome" required>
                            <div class="error-message" id="error-nomeCliente" aria-live="polite"></div>
                        </div>

                        <div class="input-login">
                            <label for="cpfCliente">CPF</label>
                            <input id="cpfCliente" name="cpfCliente" type="text" placeholder="000.000.000-00" required>
                            <div class="error-message" id="error-cpfCliente" aria-live="polite"></div>
                        </div>
                    </div>

                    <div class="row-input">
                        <div class="input-login">
                            <label for="telefoneCliente">Telefone</label>
                            <input id="telefoneCliente" name="telefoneCliente" type="text" placeholder="(00) 00000-0000" required>
                            <div class="error-message" id="error-telefoneCliente" aria-live="polite"></div>
                        </div>

                        <div class="input-login">
                            <label for="emailCliente">Email</label>
                            <input id="emailCliente" name="emailCliente" type="email" placeholder="seu@email.com" required>
                            <div class="error-message" id="error-emailCliente" aria-live="polite"></div>
                        </div>
                    </div>

                    <div class="row-input">
                        <div class="input-login">
                            <label for="senhaCliente">Senha</label>
                            <input id="senhaCliente" name="senhaCliente" type="password" placeholder="Insira sua senha" required>
                            <div class="error-message" id="error-senhaCliente" aria-live="polite"></div>
                        </div>

                        <div class="input-login">
                            <label for="confirmarSenhaCliente">Confirmar Senha</label>
                            <input id="confirmarSenhaCliente" name="confirmarSenhaCliente" type="password" placeholder="Confirmar senha" required>
                            <div class="error-message" id="error-confirmarSenhaCliente" aria-live="polite"></div>
                        </div>
                    </div>
                    
                    <div class="container-btn">
                         <button id="submitBtn" type="submit" class="btn">Cadastrar</button>
                    </div>

                    <div class="link-registro">
                        <p>Já possui conta com a gente? <br><a href="loginCliente.php">Fazer Login</a></p>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</main>

<?php include 'footer.php'; ?>