<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Entrar - Camisa 10</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/menu-rodape.css">

<style>
.banner-login {
        width: 50%;
        height: 100vh;
        background: #620b0b;
        background: linear-gradient(rgba(0, 0, 0, 0.693), rgba(0, 0, 0, 0.293)), url('assets/banners/bannerContato.png');
        background-size: cover;
        background-position: center;
    }
.login {
    text-align: justify;
    background-color: transparent;
    padding: 30px 40px;
    text-align: center;
    border-radius: 36px;
}
.login h2 {
    font-size: 1.5em;
    text-align: center;
    font-weight: 700;
    color: var(--cor-fonte);
    margin-bottom: 20px;
}
.login .input-login {
    width: 100%;
    height: 50px;
    margin: 36px 0;
}
.input-login label {
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
.input-login i {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
}
.input-login .esqueceu-sua-senha {
    display: flex;
    justify-content: space-between;
    font-size: 14.5px;
    margin: -15px 0 15px;
}
.esqueceu-sua-senha a {
    margin: 3px;
    font-size: 0.9em;
    color: var(--cor-fonte);
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
    margin-top: 6px;
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
}
</style>
<main class="container-fluid p-0 vh-100 d-flex flex-column justify-content-between">
    
    <?php
    if(isset($_GET['erroLogin'])){
        $erroLogin = $_GET['erroLogin'];
        if($erroLogin == 'dadosInvalidos'){
            echo "<div class='alert alert-warning text-center m-0'><strong>USUÁRIO ou SENHA</strong> inválidos!</div>";
        }
    }
    ?>

    <div class="row g-0 flex-grow-1 align-items-center">
        
        <div class="col-md-6 d-none d-md-block banner-login">
            <a href="index.php">
                <img src="assets/logos/logo1.png" alt="Logo Camisa 10" width="180px" class="mt-5 ms-5">
            </a>
        </div>

        <div class="col-12 col-md-6 d-flex justify-content-center align-items-center p-4">
            <div class="login w-100" style="max-width: 450px;">
                <form id="loginForm" action="actionLogin.php" method="POST" class="was-validated" enctype="multipart/form-data">
                    <h2>Entre na Camisa 10</h2>

                    <div class="input-login">
                        <label for="emailCliente">Email</label>
                        <input id="emailCliente" name="emailCliente" type="email" placeholder="seu@email.com" required>
                        <div class="error-message" id="error-emailCliente" aria-live="polite"></div>
                    </div>

                    <div class="input-login">
                        <label for="senhaCliente">Senha</label>
                        <input id="senhaCliente" name="senhaCliente" type="password" placeholder="Insira sua senha" required>
                        <div class="error-message" id="error-senhaCliente" aria-live="polite"></div>
                    </div>

                    <div class="esqueceu-sua-senha">
                        <a href="redefinirSenha.php">Esqueceu sua senha</a>
                    </div>

                    <div class="container-btn">
                         <button id="submitBtn" type="submit" class="btn">Entrar</button>
                    </div>

                    <div class="link-registro">
                        <p>Já possui conta com a gente? <br><a href="cadastroCliente.php">Cadastre-se</a></p>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>

<?php include 'footer.php'; ?>