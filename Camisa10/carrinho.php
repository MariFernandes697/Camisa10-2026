<?php 
// Garante que a sessão está ativa para gerenciar a sacola de compras
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "conexaoBD.php";
// TRAVA DE SEGURANÇA: NÃO DEIXA ENTRAR NO CARRINHO SE NÃO TIVER LOGADO
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['url_retorno'] = $_SERVER['REQUEST_URI'];
    echo "<script>
            alert('Para acessar o seu carrinho ou comprar, faça login primeiro!'); 
            window.location.href='loginCliente.php';
          </script>";
    exit;
}

// Inicializa a sacola do carrinho caso ela ainda não exista na sessão
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}
// PROCESSAMENTO DAS AÇÕES (ADICIONAR, REMOVER, ENTRADAS VIA POST)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    $idProdPost = (int)$_POST['idProduto'];
    $qtdPost = (int)$_POST['quantidade'];

    if ($idProdPost > 0 && $qtdPost > 0) {
        if ($_POST['acao'] === 'adicionar') {
            // Se o produto já existia no carrinho, soma a nova quantidade
            if (isset($_SESSION['carrinho'][$idProdPost])) {
                $_SESSION['carrinho'][$idProdPost] += $qtdPost;
            } else {
                $_SESSION['carrinho'][$idProdPost] = $qtdPost;
            }
            
            // FLUXO DO BOTÃO COMPRAR AGORA: Se o clique veio dele, pula o carrinho e vai pro checkout/finalizar
            if (isset($_POST['botao_acao']) && $_POST['botao_acao'] === 'comprar_agora') {
                echo "<script>window.location.href='finalizarCompra.php';</script>";
                exit;
            }
        }
    }
}

// Ação de Alterar Quantidades ou Remover via Links (GET)
if (isset($_GET['op'])) {
    $idProdGet = (int)$_GET['id'];
    
    if ($_GET['op'] === 'remover') {
        unset($_SESSION['carrinho'][$idProdGet]);
    } elseif ($_GET['op'] === 'somar') {
        $_SESSION['carrinho'][$idProdGet]++;
    } elseif ($_GET['op'] === 'subtrair') {
        $_SESSION['carrinho'][$idProdGet]--;
        if ($_SESSION['carrinho'][$idProdGet] < 1) {
            unset($_SESSION['carrinho'][$idProdGet]); // Se zerar a qtd, remove do carrinho
        }
    }
    // Recarrega a página limpa para atualizar os valores visuais
    echo "<script>window.location.href='carrinho.php';</script>";
    exit;
}

include "header.php";
?>
<link rel="stylesheet" href="css/carrinho.css">

<main>
    <div class="info">
        <div class="caixas-produtos">
            
            <?php
            $totalGeralProdutos = 0;

            // Se o carrinho possuir algum item guardado
            if (!empty($_SESSION['carrinho'])):
            
                $idsParaBuscar = implode(',', array_keys($_SESSION['carrinho']));
                
                $buscarProdutosCarrinho = "SELECT idProduto, tituloProduto, imgProduto, precoProduto, produtos.idCategoria, tituloCategoria FROM produtos, categorias WHERE idProduto IN ($idsParaBuscar) AND produtos.idCategoria = categorias.idCategoria";
                $resultadoCarrinho = mysqli_query($conn, $buscarProdutosCarrinho);

                while ($item = mysqli_fetch_assoc($resultadoCarrinho)):
                    $idItem = $item['idProduto'];
                    $qtdItem = $_SESSION['carrinho'][$idItem];
                    $subtotalItem = $item['precoProduto'] * $qtdItem;
                    $totalGeralProdutos += $subtotalItem; // Soma o valor ao total acumulado da compra
            ?>
                    <div class="caixa-produto">
                        <div class="img-produto-carrinho">
                            <img src="<?php echo htmlspecialchars($item['imgProduto']); ?>" alt="<?php echo htmlspecialchars($item['tituloProduto']); ?>" width="100px">
                        </div>
                        <div class="info-produto-carrinho">
                            <h3><?php echo htmlspecialchars($item['tituloProduto']); ?></h3>
                            <p>Tamanho: Padrão</p> 
                            <p>Categoria ID: <?php echo $item['tituloCategoria']; ?></p>
                        </div>
                        <div class="seletores-carrinho">
                            <a href="carrinho.php?op=subtrair&id=<?php echo $idItem; ?>" class="text-decoration-none text-dark"><i class="bi bi-dash"></i></a>
                            
                            <h4><?php echo $qtdItem; ?></h4>
                            
                            <a href="carrinho.php?op=somar&id=<?php echo $idItem; ?>" class="text-decoration-none text-dark"><i class="bi bi-plus"></i></a>
                            
                            <a href="carrinho.php?op=remover&id=<?php echo $idItem; ?>" class="text-danger ms-3" title="Remover item"><i class="bi bi-trash"></i></a>
                        </div>
                        <div class="preco-produto-carrinho">
                            <h4>R$ <?php echo number_format($subtotalItem, 2, ',', '.'); ?></h4>
                        </div>
                    </div>
            <?php 
                endwhile;
            else:
            ?>
                <div class="alert alert-secondary text-center py-5">
                    <i class="bi bi-cart-x display-4 d-block mb-3"></i>
                    Sua sacola de compras está vazia! <br>
                    <a href="produtos.php" class="btn btn-sm btn-dark mt-3">Ver Produtos</a>
                </div>
            <?php 
            endif; 
            
            // Definição de regras de descontos fixas do seu layout original
            $valorDesconto = ($totalGeralProdutos > 0) ? 30.00 : 0.00;
            $totalFinalComDesconto = $totalGeralProdutos - $valorDesconto;
            if ($totalFinalComDesconto < 0) $totalFinalComDesconto = 0;
            ?>
        </div>

        <div class="info-compras">
            <div class="info-compras2">
                <div class="info-text">
                    <p>Total Produtos</p>
                    <hr style="color: #fff;">
                    <p>Descontos</p>
                    <hr style="color: #fff;">
                    <p>Meio de pagamento</p>
                    <hr style="color: #fff;">
                    <h4>Total</h4>
                </div>
                <div class="info-preco">
                    <p>R$ <?php echo number_format($totalGeralProdutos, 2, ',', '.'); ?></p>
                    <hr style="color: transparent;">
                    <p>R$ <?php echo number_format($valorDesconto, 2, ',', '.'); ?></p>
                    <hr style="color: transparent;">
                    <p>Pix</p>
                    <hr style="color: transparent;">
                    <h4>R$ <?php echo number_format($totalFinalComDesconto, 2, ',', '.'); ?></h4>
                </div>
            </div>

            <div class="btn-compras">
                <button <?php echo empty($_SESSION['carrinho']) ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                    <a href="<?php echo empty($_SESSION['carrinho']) ? '#' : 'finalizarCompra.php'; ?>" class="text-decoration-none text-white">Confirmar compra</a>
                </button>
            </div>
        </div>
    </div>

    <div class="info-cupom">
        <label for="cupom" class="cupom-label">Cupom de desconto</label>
        <input type="text" id="cupom" name="cupom" placeholder="Adicionar cupom">
    </div>

    <div class="info-frete">
        <label for="frete" class="frete-label">Calcular Frete</label>
        <input type="text" id="frete" name="frete" placeholder="Digite o seu CEP">
    </div>
</main>

<?php include "footer.php"; ?>
