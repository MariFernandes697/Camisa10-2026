<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['emailAdmin'] ?? '';
        $senha = $_POST['senhaAdmin'] ?? '';

        if ($email === 'admin@camisa10.com' && $senha === 'camisa10admin') {
            session_start();
            $_SESSION['admin_logado'] = true;
            
            // Redireciona direto para o painel que recuperamos
            header('Location: adminPainel.php');
            exit;
        } else {
            // Se errar, recarrega a página passando o parâmetro de erro
            header('Location: loginAdmin.php?erroLogin=dadosInvalidos');
            exit;
        }
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel Administrativo - Camisa 10</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<link rel="shortcut icon" href="assets/favicon_io/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/menu-rodape.css">

<style>
.banner-login {
        width: 50%;
        height: 100vh;
        background: #620b0b;
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
.login .btn {
    width: 70%;                
    height: 45px;
    background-color: #000;
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
    background-color: #ff0000;
    color: #fff;
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
                <form id="loginForm" action="" method="POST" class="was-validated">
                    <h2>Camisa 10</h2>

                    <div class="input-login">
                        <label for="emailAdmin">Admin User</label>
                        <input id="emailAdmin" name="emailAdmin" type="email" placeholder="seu@email.com" required>
                        <div class="error-message" id="error-emailAdmin" aria-live="polite"></div>
                    </div>

                    <div class="input-login">
                        <label for="senhaAdmin">Senha</label>
                        <input id="senhaAdmin" name="senhaAdmin" type="password" placeholder="Insira sua senha" required>
                        <div class="error-message" id="error-senhaAdmin" aria-live="polite"></div>
                    </div>

                    <div class="container-btn">
                         <button id="submitBtn" type="submit" class="btn">Autenticar</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</main>

<?php include 'footer.php'; ?>