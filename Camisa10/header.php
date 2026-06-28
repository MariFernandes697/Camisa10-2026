<?php 
// Garante que a sessão está ativa para ler os dados do usuário logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/menu-rodape.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/produtos.css">
    <title>Camisa 10</title>
    <style>
        /* Foto do usuário logado na navbar */
    .foto-perfil-nav {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--cor-fonte);
        vertical-align: middle;
    }

    /* Container do texto/link do perfil */
    .nav-link-user {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--cor-fonte) !important;
        font-size: 1rem !important;
        font-weight: 500;
    }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">
                <img src="assets/logos/logo1.png" alt="Logo Camisa 10" width="150px">
            </a>

        <!-- Barra de pesquisa -->
            <form class="d-flex barra-de-pesquisa" id="formBusca">
                <input class="form-control me-2" type="text" placeholder="Pesquisar" id="barraPesquisa">
                <button class="btn bg-light" type="submit"><i class="bi bi-search"></i></button>
            </form>


            <div class="mobile-menu">
                <i class="bi bi-list"></i>
            </div>
            
            <ul class="nav-list">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                
                <?php if(isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                    <li><a href="#" class="perfil">Meu Perfil</a></li>
                <?php else: ?>
                    <li><a href="loginCliente.php" class="perfil">Entrar</a></li>
                <?php endif; ?>

                <li><a href="carrinho.php" class="carrinho"><i class="bi bi-cart2"></i></a></li>
                
                <li class="nav-item dropdown">
                <?php if(isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                    <a class="nav-link dropdown-toggle nav-link-user" href="#" id="userDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        
                        <?php if(!empty($_SESSION['fotoCliente'])): ?>
                            <img src="<?php echo $_SESSION['fotoCliente']; ?>" class="foto-perfil-nav">
                        <?php else: ?>
                            <i class="bi bi-person-circle" style="font-size: 1.3em; color: #fff; vertical-align: middle;"></i>
                        <?php endif; ?>

                        <span>Olá, <?php echo $_SESSION['nomeCliente']; ?></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Seu perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">Sair...</a></li>
                    </ul>
                <?php else: ?>
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle" style="font-size: 1.3em; color: #fff;"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="loginCliente.php">Entrar</a></li>
                        <li><a class="dropdown-item" href="cadastroCliente.php">Cadastre-se</a></li>
                    </ul>
                <?php endif; ?>
            </li>
            </ul>
        </nav>
    </header>