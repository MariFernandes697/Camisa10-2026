<?php include "header.php"; ?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<main>
    <!-- banners promocionais -->
    <div class="banner">
        <div id="demo" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">
                <?php
                include "conexaoBD.php";

                // Busca os banners 
                $pesquisarBanners = "SELECT idPromocao, imgPromocao FROM promocoes ORDER BY idPromocao ASC";
                $resultadoBanners = mysqli_query($conn, $pesquisarBanners);

                $primeiroItem = true;

                while ($promocao = mysqli_fetch_assoc($resultadoBanners)):
                    $classeActive = $primeiroItem ? 'active' : '';
                    ?>
                    <div class="carousel-item <?php echo $classeActive; ?>">
                        <img src="<?php echo htmlspecialchars($promocao['imgPromocao']) ?>" class="d-block w-100"
                            alt="Banner Promocional">
                    </div>
                    <?php
                    $primeiroItem = false;
                endwhile;
                ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>

    <!-- CATEGORIAS DOS PRODUTOS NA PÁGINA PRINCIPAL -->
    <div class="categorias">

        <h2>Encontre na Camisa 10</h2>

        <div class="cards-categorias">
            <?php
            include "conexaoBD.php";

            $buscarCategorias = "SELECT idCategoria, tituloCategoria, imgCategoria FROM categorias ORDER BY idCategoria ASC LIMIT 8";
            $resultado = mysqli_query($conn, $buscarCategorias);

            while ($cat = mysqli_fetch_assoc($resultado)):
                ?>
                <article class="product-card" style="cursor: pointer;">
                    <figure class="card-figure">
                        <img src="<?php echo htmlspecialchars($cat['imgCategoria']); ?>"
                            alt="Categoria <?php echo htmlspecialchars($cat['tituloCategoria']); ?>" class="card-image">
                        <figcaption class="card-title"><?php echo htmlspecialchars($cat['tituloCategoria']); ?></figcaption>
                    </figure>
                </article>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Cards Produtos -->
    <div class="todos-os-produtos">
        <div class="topo">
            <h2>Todos os produtos</h2>
            <!-- Filtros de pesquisa -->
            <div class="caixa-filtro">
                <label for="filtro" class="filtro-label">Filtrar por</label>
                <select name="filtro" id="filtro" class="filtro-select">
                    <option value="categoria">Categoria</option>
                    <option value="nome">A -> Z</option>
                    <option value="menor-preco">Menor Preço</option>
                    <option value="maior-preco">Maior Preço</option>
                </select>
                <button id="limparSeletor" class="btnLimparSeletor" title="Limpar filtro"><i
                        class="bi bi-x"></i></button>
            </div>
        </div>
        <!-- Produtos -->
        <!-- Produtos -->
        <div class="produtos container my-5 produtosMain">
            <?php
            include "conexaoBD.php";

            // Definir quantos produtos por página
            $produtosPorPagina = 8;

            // Verificar página atual (default = 1)
            $paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

            // Calcular o OFFSET
            $offset = ($paginaAtual - 1) * $produtosPorPagina;

            // Buscar total de produtos
            $totalQuery = "SELECT COUNT(*) as total FROM produtos";
            $totalResultado = mysqli_query($conn, $totalQuery);
            $totalProdutos = mysqli_fetch_assoc($totalResultado)['total'];

            // Buscar produtos da página atual
            $buscarProdutos = "SELECT idProduto, tituloProduto, imgProduto, precoProduto, descricaoProduto, idCategoria 
                   FROM produtos 
                   ORDER BY idProduto DESC 
                   LIMIT $produtosPorPagina OFFSET $offset";

            $resultado = mysqli_query($conn, $buscarProdutos);
            ?>

            <div class="cards">
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($produto = mysqli_fetch_assoc($resultado)): ?>
                        <?php $precoExibicao = number_format($produto['precoProduto'], 2, ',', '.'); ?>
                        <div class="card">
                            <div class="img-produto">
                                <img src="<?php echo htmlspecialchars($produto['imgProduto']) ?>"
                                    alt="<?php echo htmlspecialchars($produto['tituloProduto']) ?>">
                            </div>
                            <h3><?php echo htmlspecialchars($produto['tituloProduto']) ?></h3>
                            <h6><?php echo htmlspecialchars($produto['descricaoProduto']) ?></h6>
                            <p class="preco">R$ <?php echo $precoExibicao; ?></p>
                            <button class="btn-card"><a href="verProduto.php?idProduto=<?php echo $produto['idProduto']; ?>">Ver
                                    mais</a></button>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-secondary text-center w-100 py-4">
                        Nenhum produto cadastrado no momento.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Paginação Bootstrap -->
        <?php
        // Calcular o total de páginas
        $totalPaginas = ceil($totalProdutos / $produtosPorPagina);
        ?>

        <!-- Paginação Bootstrap -->
        <nav aria-label="Navegação de página">
            <ul class="pagination justify-content-center">
                <!-- Botão "Anterior" -->
                <?php if ($paginaAtual > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?php echo $paginaAtual - 1; ?>" aria-label="Anterior">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">&laquo;</span>
                    </li>
                <?php endif; ?>

                <!-- Números das páginas -->
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Botão "Próximo" -->
                <?php if ($paginaAtual < $totalPaginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?php echo $paginaAtual + 1; ?>" aria-label="Próximo">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">&raquo;</span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
</main>
<div class="clearfix mt-5 mb-5 "></div>
<!-- RODAPÉ -->
<?php include "footer.php"; ?>
</body>

</html>