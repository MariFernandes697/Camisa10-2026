<?php include 'header.php';?>
<!-- O header de algumas páginas são diferentes da página principal -->
<header>
    <nav>
        <a href="index.php">
            <img src="assets/logos/logo1.png" alt="Logo Camisa 10" width="150px">
        </a>
        
        <div class="mobile-menu">
            <i class="bi bi-list"></i>
        </div>
        
        <ul class="nav-list">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="#sobre-nos">Sobre nós</a></li>
            <li><a href="produtos.php">Produtos</a></li>
            <li><a href="#contato">Contato</a></li>
            
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
            </li> </ul>
    </nav>
</header>
    <main>
        <!--BANNER PRINCIPAL-->
        <div class="escopo">
            <div class="text-escopo">
                <h1>Bem Vindo a Camisa 10</h1>
                <p>Conheça os nossos produtos</p>
            </div>
        </div>

        <!-- EXPOSIÇÃO BRASÃO DAS SELEÇÕES DOS PRODUTOS NA PÁGINA PRINCIPAL -->
        <!-- APROVEITAMENTO DO CÓDIGO DA SEÇÃO CATEGORIAS -->
        <div class="categorias">
            <h2>Copa do Mundo da FIFA na Camisa 10</h2>
            <!-- CARDS DOS BRASÕES-->
            <div class="cards-categorias">
                <article class="product-card">
                <figure class="card-figure">
                    <img src="assets\brasoesSelecoes\1.png" alt="Seleções Fifa 2026" class="card-image">
                </figure>
                </article>
                <article class="product-card">
                <figure class="card-figure">
                    <img src="assets\brasoesSelecoes\2.png" alt="Seleções Fifa 2026" class="card-image">
                </figure>
                </article>
                <article class="product-card">
                <figure class="card-figure">
                    <img src="assets\brasoesSelecoes\3.png" alt="Seleções Fifa 2026" class="card-image">
                </figure>
                </article>
                <article class="product-card">
                <figure class="card-figure">
                    <img src="assets\brasoesSelecoes\4.png" alt="Seleções Fifa 2026" class="card-image">
                </figure>
                </article>
                <article class="product-card">
                <figure class="card-figure">
                    <img src="assets\brasoesSelecoes\5.png" alt="Seleções Fifa 2026" class="card-image">
                </figure>
                </article>
                <article class="product-card">
                <figure class="card-figure">
                    <img src="assets\brasoesSelecoes\6.png" alt="Seleções Fifa 2026" class="card-image">
                </figure>
                </article>
                <article class="product-card">
                <figure class="card-figure">
                    <img src="assets\brasoesSelecoes\7.png" alt="Seleções Fifa 2026" class="card-image">
                </figure>
                </article>
            </div>
        </div>

        <!-- Sobre Nós -->
        <!-- Vou utilizar o mesmo estilo do banner principal -->
        <div class="sobreNos" id="sobre-nos">
            <div class="text-sobreNos">
                <h1>Deixe o esporte fazer parte da sua vida</h1>
                <p><span>Venha ser um de nós! Seja Camisa 10!</span></p>
            </div>
        </div>

        <!-- NOVIDADES -->
        <div id="novidades" class="carousel slide novidades" data-bs-ride="carousel">

            <h2>Novidades</h2>

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#novidades" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#novidades" data-bs-slide-to="1"></button>
            </div>

            <div class="carousel-inner">

            <?php
            include "conexaoBD.php";

            // Busca os produtos e inclui o id_categoria que usamos nos atributos data-*
            $buscarProdutos = "SELECT produtos.idProduto, tituloProduto, imgProduto, precoProduto, descricaoProduto, produtos.idCategoria 
                               FROM produtos, produtosPromocao 
                               WHERE produtos.idProduto = produtosPromocao.idProduto
                               AND produtosPromocao.idPromocao = 2
                               ORDER BY produtosPromocao.idProduto DESC";
            $resultadoProdutos = mysqli_query($conn, $buscarProdutos);

            $contProdutos = 0; // Contador para controlar a divisão de 4 em 4
            $primeiraPagina = true; 

            while($produto = mysqli_fetch_assoc($resultadoProdutos)):
                // Quando contProdutos for 0, abre uma nova página no carrossel
                if ($contProdutos == 0) {
                    $active = $primeiraPagina ? 'active' : '';
                    echo '<div class="carousel-item ' . $active . '">';
                    echo '  <div class="produtos2 container my-5 produtosMain">';
                    echo '    <div class="cards">';
                    $primeiraPagina = false; // As próximas páginas não terão a classe 'active'
                }
                ?>

                <div class="card" 
                    data-nome="<?php echo htmlspecialchars($produto['tituloProduto']) ?>" 
                    data-categoria="<?php echo htmlspecialchars($produto['idCategoria'] ?? '') ?>" 
                    data-preco="<?php echo htmlspecialchars($produto['precoProduto']) ?>">
                    
                    <div class="img-produto">
                        <img src="<?php echo htmlspecialchars($produto['imgProduto']) ?>" alt="<?php echo htmlspecialchars($produto['tituloProduto']) ?>">
                    </div>
                    <h3><?php echo htmlspecialchars($produto['tituloProduto']) ?></h3>
                    <h6><?php echo htmlspecialchars($produto['descricaoProduto']) ?></h6>
                    <p class="preco"><?php echo htmlspecialchars($produto['precoProduto']) ?></p>
                    <button class="btn-card"><a href="verProduto.php?idProduto=<?php echo $produto['idProduto']; ?>">Ver mais</a></button>
                </div>

                <?php
                $contProdutos++; 

                // Se já exibiu 4 produtos, fecha as estruturas dessa página e reseta o contador
                if ($contProdutos == 4) {
                    echo '    </div>'; // Fecha a div class="cards"
                    echo '  </div>';   // Fecha a div class="produtos2..."
                    echo '</div>';     // Fecha a div class="carousel-item"
                    $contProdutos = 0;     
                }
            endwhile;

            if ($contProdutos > 0) {
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
            }
            ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#novidades" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#novidades" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark"></span>
            </button>
        </div>
        
        <!-- botão ir para a loja -->
        <div class="botao-shop-caixa">
            <a href="produtos.php" class="btn-shop">Ir para a Loja</a>
        </div>

        <!-- Contato -->
        <div class="contato" id="contato">
            <div class="left"></div>
            <div class="right">
                <div class="contato-secao">
                    <div class="card-contato">
                        <h3 class="titulo-formulario">Fale Conosco</h3>
                        <form id="form-contato" class="form-contato" action="actionContato.php" method="POST">
                            <div class="linha">
                                <label for="nome">Nome</label>
                                <input type="text" name="nomeContato" id="nomeContato" placeholder="Digite seu nome" required>
                                <span class="erro" data-for="nome"></span>
                            </div>
                            <div class="linha">
                                <label for="email">Email</label>
                                <input type="email" name="emailContato" id="emailContato" placeholder="email@exemplo.com" required>
                                <span class="erro" data-for="email"></span>
                            </div>
                             <div class="linha">
                                <label for="mensagem">Assunto</label>
                                <textarea name="mensagemContato" id="mensagemContato" required></textarea>
                                <span class="erro" data-for="mensagem"></span>
                            </div>
                            <div class="linha acao">
                                <button type="submit" class="btn enviar">Enviar</button>
                                <button type="reset" class="btn limpar" id="limpar">Resetar</button>
                                <small class="obs">Sua dúvida será enviada. Aguarde em breve nosso retorno!</small>
                            </div>
                            <div id="feedback" class="feedback" role="status"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Homenagem aos atletas-->
        <div class="homenagem">
            <h2>Inspirações do nosso esporte brasileiro</h2>

            <div class="cards-homenagens">
                <div class="card-homenagem">
                    <img src="assets/atletas/pele.png" alt="" width="270px" height="395px">
                </div>
                <div class="card-homenagem">
                    <img src="assets/atletas/fegaray.png" alt="" width="270px" height="395px">
                </div>
                <div class="card-homenagem">
                    <img src="assets/atletas/giba.png" alt="" width="270px" height="395px">
                </div>
                <div class="card-homenagem">
                    <img src="assets/atletas/marta.png" alt="" width="270px" height="395px">
                </div>
                <div class="card-homenagem">
                    <img src="assets/atletas/serginho.png" alt="" width="270px" height="395px">
                </div>
                <div class="card-homenagem">
                    <img src="assets/atletas/messi.png" alt="" width="270px" height="395px">
                </div>
                <div class="card-homenagem">
                    <img src="assets/atletas/rebeca.png" alt="" width="270px" height="395px">
                </div>
            </div>
         </div>

<?php include 'footer.php';?>