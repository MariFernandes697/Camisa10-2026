<?php
include "header.php";
include "conexaoBD.php";

$idPromocao = intval($_GET['idPromocao']);

// Buscando os dados da promoção
$queryPromo = "SELECT * FROM Promocoes WHERE idPromocao = $idPromocao";
$resPromo = mysqli_query($conn, $queryPromo);
$promocao = mysqli_fetch_assoc($resPromo);

// Buscando os produtos já vinculados
$queryVinculados = "SELECT idProduto FROM produtosPromocao WHERE idPromocao = $idPromocao";
$resVinculados = mysqli_query($conn, $queryVinculados);

$produtosSelecionados = [];
while ($vinculo = mysqli_fetch_assoc($resVinculados)) {
    $produtosSelecionados[] = $vinculo['idProduto']; 
}
?>
<style>
    .login h2 {
        margin-top: 5vh;
        font-size: 1.5em;
        text-align: center;
        font-weight: 700;
        color: var(--cor-fonte);
        margin-bottom: 20px;
    }
</style>
<header>
    <nav>
        <a href="promocoesAdmin.php">
            <img src="assets/logos/logo1.png" alt="" width="180px">
        </a>
        <ul>
            <li class="nav-item"><a href="#" class="nav-link" role="button"><i class="bi bi-person-circle"></i></a></li>
        </ul>
    </nav>
</header>

<main class="container py-5 d-flex justify-content-center">
    <div class="card p-4 shadow-sm col-md-6 login">
        <h2>Editar Promoção</h2>
        
        <form action="actionCrud.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idPromocao" value="<?php echo $idPromocao; ?>">
            <input type="hidden" name="acao" value="editarPromocao">
            
            <div class="text-center mb-3">
                <p class="small text-muted mb-1">Imagem Atual:</p>
                <img src="<?php echo $promocao['imgPromocao']; ?>" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
            </div>

            <div class="form-floating mb-3">
                <input type="file" class="form-control" id="imgPromocao" name="imgPromocao">
                <label for="imgPromocao">Substituir Foto (Opcional)</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="tituloPromocao" name="tituloPromocao" 
                        value="<?php echo htmlspecialchars($promocao['tituloPromocao']); ?>" required>
                <label for="tituloPromocao">Título da Promoção</label>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Selecione os Produtos da Promoção:</strong></label>
                <div class="row row-cols-1 g-2" style="max-height: 200px; overflow-y: auto;">
                    <?php
                    $queryTodosProdutos = "SELECT idProduto, tituloProduto FROM produtos ORDER BY tituloProduto ASC";
                    $resTodos = mysqli_query($conn, $queryTodosProdutos);

                    while ($prod = mysqli_fetch_assoc($resTodos)) {
                        // Corrigido de $produtosMarcados para $produtosSelecionados
                        $marcado = in_array($prod['idProduto'], $produtosSelecionados) ? "checked" : "";
                    ?>
                        <div class="col">
                            <div class="form-check border p-2 rounded bg-light">
                                <input class="form-check-input ms-1" type="checkbox" name="produtos[]" 
                                       value="<?php echo $prod['idProduto']; ?>" <?php echo $marcado; ?>>
                                <label class="form-check-label ms-4">
                                    <?php echo htmlspecialchars($prod['tituloProduto']); ?>
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="form-floating mt-3 mb-3">
                <input type="date" class="form-control" id="dataInicio" name="dataInicio" value="<?php echo $promocao['dataInicio']; ?>" required>
                <label for="dataInicio">Data de Início</label>
            </div>

            <div class="form-floating mt-3 mb-3">
                <input type="date" class="form-control" id="dataFim" name="dataFim" value="<?php echo $promocao['dataFim']; ?>" required>
                <label for="dataFim">Data de Encerramento</label>
            </div>
            
            <div class="d-flex gap-2">
                <a href="promocoesAdmin.php" class="btn btn-danger w-50 fw-bold">Cancelar</a>
                <button type="submit" class="btn btn-success w-50 fw-bold">Salvar Alterações</button>
            </div>
        </form>
    </div>
</main>
<?php include "footer.php"; ?>