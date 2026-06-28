<?php 
include "header.php";
include "conexaoBD.php";

// 1. Pega o ID da URL de forma segura
$idProduto = isset($_GET['idProduto']) ? (int)$_GET['idProduto'] : 0;

if ($idProduto === 0) {
    echo "<script>alert('Produto inválido!'); window.location.href='produtos.php';</script>";
    exit;
}

// 2. Busca apenas o produto selecionado no banco de dados
$query = "SELECT idProduto, tituloProduto, imgProduto, precoProduto, descricaoProduto, idCategoria FROM produtos WHERE idProduto = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $idProduto);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($produtoAtual = mysqli_fetch_assoc($resultado)) {
    $precoExibicao = number_format($produtoAtual['precoProduto'], 2, ',', '.');
    $categoriaAtual = $produtoAtual['idCategoria'];
} else {
    echo "<script>alert('Produto não encontrado!'); window.location.href='produtos.php';</script>";
    exit;
}
?>

<main>
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6">
                    <img class="card-img-top mb-5 mb-md-0" src="<?php echo htmlspecialchars($produtoAtual['imgProduto']); ?>" alt="<?php echo htmlspecialchars($produtoAtual['tituloProduto']); ?>" />
                </div>
                <div class="col-md-6">
                    <h1 class="display-5 fw-bolder"><?php echo htmlspecialchars($produtoAtual['tituloProduto']); ?></h1>
                    <div class="fs-5 mb-4">
                        <span class="text-success fw-bold">R$ <?php echo $precoExibicao; ?></span>
                    </div>
                    <p class="lead"><?php echo htmlspecialchars($produtoAtual['descricaoProduto']); ?></p>
                    
                    <form action="carrinho.php" method="POST" class="d-flex flex-column gap-3">
                        <input type="hidden" name="idProduto" value="<?php echo $produtoAtual['idProduto']; ?>">
                        <input type="hidden" name="acao" value="adicionar">
                        
                        <div class="d-flex align-items-center">
                            <label for="inputQuantity" class="me-2 text-muted">Qtd:</label>
                            <input class="form-control text-center me-3" id="inputQuantity" name="quantidade" type="number" value="1" min="1" style="max-width: 4rem" />
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-dark flex-grow-1 btn-verproduto" type="submit" name="botao_acao" value="adicionar_carrinho">
                                <i class="bi bi-cart2"></i> Adicionar ao carrinho
                            </button>
                            
                            <button class="btn btn-dark flex-grow-1" type="submit" name="botao_acao" value="comprar_agora">
                                <i class="bi bi-bag-check"></i> Comprar Agora
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div id="novidades" class="carousel slide novidades my-5" data-bs-ride="carousel">
        <h2 class="mb-4">Produtos Relacionados</h2>
        
        <div class="carousel-inner">
            <?php
            // Busca outros produtos da MESMA categoria
            $buscarRelacionados = "SELECT idProduto, tituloProduto, imgProduto, precoProduto, descricaoProduto, idCategoria 
                                   FROM produtos 
                                   WHERE idCategoria = ? AND idProduto != ? 
                                   ORDER BY idProduto DESC LIMIT 8";
            
            $stmtRelacionados = mysqli_prepare($conn, $buscarRelacionados);
            mysqli_stmt_bind_param($stmtRelacionados, "ii", $categoriaAtual, $idProduto);
            mysqli_stmt_execute($stmtRelacionados);
            $resultadoRelacionados = mysqli_stmt_get_result($stmtRelacionados);

            $contProdutos = 0; 
            $primeiraPagina = true; 

            if (mysqli_num_rows($resultadoRelacionados) > 0) {
                while($relacionado = mysqli_fetch_assoc($resultadoRelacionados)) {
                    
                    if ($contProdutos == 0) {
                        $active = $primeiraPagina ? 'active' : '';
                        echo '<div class="carousel-item ' . $active . '">';
                        echo '  <div class="produtos2 container my-3 produtosMain">';
                        echo '    <div class="cards">';
                        $primeiraPagina = false;
                    }
                    
                    $precoRelacionado = number_format($relacionado['precoProduto'], 2, ',', '.');
                    ?>
                    
                    <div class="card" data-nome="<?php echo htmlspecialchars($relacionado['tituloProduto']) ?>" data-categoria="<?php echo htmlspecialchars($relacionado['idCategoria']) ?>" data-preco="<?php echo htmlspecialchars($relacionado['precoProduto']) ?>">
                        <div class="img-produto">
                            <img src="<?php echo htmlspecialchars($relacionado['imgProduto']) ?>" alt="<?php echo htmlspecialchars($relacionado['tituloProduto']) ?>">
                        </div>
                        <h3><?php echo htmlspecialchars($relacionado['tituloProduto']) ?></h3>
                        <h6><?php echo htmlspecialchars($relacionado['descricaoProduto']) ?></h6>
                        <p class="preco">R$ <?php echo $precoRelacionado; ?></p>
                        <button class="btn-card"><a href="verProduto.php?idProduto=<?php echo $relacionado['idProduto']; ?>">Ver mais</a></button>
                    </div>

                    <?php
                    $contProdutos++; 

                    if ($contProdutos == 4) {
                        echo '    </div>'; 
                        echo '  </div>';   
                        echo '</div>';     
                        $contProdutos = 0;     
                    }
                } // Fecha o while

                if ($contProdutos > 0) {
                    echo '    </div>';
                    echo '  </div>';
                    echo '</div>';
                }
            } else { 
            ?>
                <div class="carousel-item active">
                    <div class="text-center text-muted py-5 fs-5">
                        Nenhum produto relacionado encontrado.
                    </div>
                </div>
            <?php 
            } // Fecha o else 
            ?>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#novidades" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark" style="border-radius: 50%;"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#novidades" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark" style="border-radius: 50%;"></span>
        </button>
    </div>
</main>

<?php include "footer.php"; ?>