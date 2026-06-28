<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "conexaoBD.php";

// Segurança: Se não está logado ou carrinho está vazio, volta pro index
if (!isset($_SESSION['logado']) || empty($_SESSION['carrinho'])) {
    header("Location: index.php");
    exit;
}

$idCliente = $_SESSION['idCliente'];
$totalGeral = 0;

// 1. PROCESSAR O FECHAMENTO DO PEDIDO (Quando o formulário for enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta dados do endereço
    $cep = mysqli_real_escape_string($conn, $_POST['cep']);
    $rua = mysqli_real_escape_string($conn, $_POST['rua']);
    $numero = mysqli_real_escape_string($conn, $_POST['numero']);
    $bairro = mysqli_real_escape_string($conn, $_POST['bairro']);
    $cidade = mysqli_real_escape_string($conn, $_POST['cidade']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $formaPagamento = mysqli_real_escape_string($conn, $_POST['formaPagamento']);
    $totalPedido = (float)$_POST['totalPedido'];

    // A. Salva o Endereço de Entrega vinculado ao cliente
    $sqlEndereco = "INSERT INTO enderecosClientes (idCliente, cep, rua, numero, bairro, cidade, estado) 
                    VALUES ('$idCliente', '$cep', '$rua', '$numero', '$bairro', '$cidade', '$estado')";
    
    if (mysqli_query($conn, $sqlEndereco)) {
        $idEnderecoCriado = mysqli_insert_id($conn); // Pega o ID do endereço que acabou de ser gerado

        // B. Salva o Pedido Principal
        $sqlPedido = "INSERT INTO pedidos (idCliente, idEndereco, formaPagamento, totalPedido) 
                      VALUES ('$idCliente', '$idEnderecoCriado', '$formaPagamento', '$totalPedido')";
        
        if (mysqli_query($conn, $sqlPedido)) {
            $idPedidoCriado = mysqli_insert_id($conn); // Pega o ID do pedido gerado

            // C. Salva os Itens do Pedido percorrendo o carrinho
            $idsParaBuscar = implode(',', array_keys($_SESSION['carrinho']));
            $buscarProdutos = "SELECT idProduto, precoProduto FROM produtos WHERE idProduto IN ($idsParaBuscar)";
            $resultadoProdutos = mysqli_query($conn, $buscarProdutos);

            while ($prod = mysqli_fetch_assoc($resultadoProdutos)) {
                $idProd = $prod['idProduto'];
                $qtdProd = $_SESSION['carrinho'][$idProd];
                $precoProd = $prod['precoProduto'];

                $sqlItem = "INSERT INTO itensPedido (idPedido, idProduto, quantidade, precoUnitario) 
                            VALUES ('$idPedidoCriado', '$idProd', '$qtdProd', '$precoProd')";
                mysqli_query($conn, $sqlItem);
            }

            // D. Limpa o carrinho após o sucesso!
            unset($_SESSION['carrinho']);

            echo "<script>
                    alert('Pedido finalizado com sucesso! Muito obrigado por comprar na Camisa 10.');
                    window.location.href='index.php'; 
                  </script>";
            exit;
        }
    } else {
        echo "<script>alert('Erro ao processar o endereço de entrega.');</script>";
    }
}

include "header.php";
?>

<link rel="stylesheet" href="css/carrinho.css"> 
<main class="container mt-5 pt-5">
    <h2 class="mt-5 mb-4" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">Finalizar Compra</h2>
    
    <form action="finalizarCompra.php" method="POST">
        <div class="row">
            <div class="col-md-7">
                <div class="card p-4 mb-4 shadow-sm text-dark">
                    <h4 class="mb-3" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">Endereço de Entrega</h4>
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">CEP</label>
                            <input type="text" name="cep" class="form-control" placeholder="00000-000" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Rua / Logradouro</label>
                            <input type="text" name="rua" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Número</label>
                            <input type="text" name="numero" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Bairro</label>
                            <input type="text" name="bairro" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cidade</label>
                            <input type="text" name="cidade" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">UF</label>
                            <input type="text" name="estado" class="form-control" maxlength="2" placeholder="PR" required>
                        </div>
                    </div>
                </div>

                <div class="card p-4 mb-4 shadow-sm text-dark">
                    <h4 class="mb-3" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">Forma de Pagamento</h4>
                    <div class="mt-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="formaPagamento" id="pagPix" value="Pix" checked>
                            <label class="form-check-label" for="pagPix">
                                <i class="bi bi-qr-code-scan"></i> Pix (Aprovação imediata)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="formaPagamento" id="pagCartao" value="Cartão de Crédito">
                            <label class="form-check-label" for="pagCartao">
                                <i class="bi bi-credit-card"></i> Cartão de Crédito
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="formaPagamento" id="pagBoleto" value="Boleto">
                            <label class="form-check-label" for="pagBoleto">
                                <i class="bi bi-file-earmark-bar-graph"></i> Boleto Bancário
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card p-4 shadow-sm text-dark bg-light">
                    <h4 class="mb-3" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">Resumo do Pedido</h4>
                    <hr>
                    <div class="lista-itens-revisao mb-4">
                        <?php
                        $idsParaBuscar = implode(',', array_keys($_SESSION['carrinho']));
                        $buscarProdutosCarrinho = "SELECT idProduto, tituloProduto, precoProduto FROM produtos WHERE idProduto IN ($idsParaBuscar)";
                        $resultadoCarrinho = mysqli_query($conn, $buscarProdutosCarrinho);

                        while ($item = mysqli_fetch_assoc($resultadoCarrinho)):
                            $idItem = $item['idProduto'];
                            $qtdItem = $_SESSION['carrinho'][$idItem];
                            $subtotalItem = $item['precoProduto'] * $qtdItem;
                            $totalGeral += $subtotalItem;
                        ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="fw-bold"><?php echo $qtdItem; ?>x</span> 
                                    <?php echo htmlspecialchars($item['tituloProduto']); ?>
                                </div>
                                <span class="text-muted">R$ <?php echo number_format($subtotalItem, 2, ',', '.'); ?></span>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <?php 
                    $valorDesconto = ($totalGeral > 0) ? 30.00 : 0.00;
                    $totalFinal = $totalGeral - $valorDesconto;
                    if ($totalFinal < 0) $totalFinal = 0;
                    ?>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Desconto Aplicado:</span>
                        <span>- R$ <?php echo number_format($valorDesconto, 2, ',', '.'); ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Total Final:</span>
                        <span class="fs-5 fw-bold text-primary">R$ <?php echo number_format($totalFinal, 2, ',', '.'); ?></span>
                    </div>

                    <input type="hidden" name="totalPedido" value="<?php echo $totalFinal; ?>">

                    <button type="submit" class="btn btn-success w-100 py-2 fs-5 fw-bold">Confirmar e Finalizar Pedido</button>
                </div>
            </div>
        </div>
    </form>
</main>

<?php include "footer.php"; ?>