<?php 
include "header.php";
include "conexaoBD.php";

// Captura o ID da Produto que será editada
if (isset($_GET['idProduto'])) {
    $idProduto = intval($_GET['idProduto']);
    
    // Busca os dados atuais dela no banco
    $buscar = "SELECT * FROM Produtos WHERE idProduto = $idProduto";
    $resultado = mysqli_query($conn, $buscar);
    $produto = mysqli_fetch_assoc($resultado);
} else {
    header("location: produtosAdmin.php");
    exit;
}
?>
<style>
    .login h2 {
    margin-top: 20vh;
    font-size: 1.5em;
    text-align: center;
    font-weight: 700;
    color: var(--cor-fonte);
    margin-bottom: 20px;
}
</style>
<header>
    <nav>
        <a href="adminPainel.php">
            <!-- Logo -->
            <img src="assets/logos/logo1.png" alt="" width="180px">
        </a>
        <ul>
            <!-- Icone usuário -->
            <li class="nav-item"><a href="#" class="nav-link" role="button"><i class="bi bi-person-circle"></i></a>
            </li>
        </ul>
    </nav>
</header>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h2 class="text-center mb-4" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">
                    Editar Produto
                </h2>

                <form action="actionCrud.php" method="POST" class="was-validated" enctype="multipart/form-data">
                    <input type="hidden" name="acao" value="editarProduto">
                    <input type="hidden" name="idProduto" value="<?php echo $idProduto; ?>">
                    
                    <div class="text-center mb-3">
                        <p class="small text-muted mb-1">Imagem Atual:</p>
                        <img src="<?php echo $produto['imgProduto']; ?>" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>

                    <div class="form-floating mb-3">
                        <input type="file" class="form-control" id="imgProduto" name="imgProduto">
                        <label for="imgProduto">Substituir Foto (Opcional)</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="tituloProduto" name="tituloProduto" 
                               value="<?php echo htmlspecialchars($produto['tituloProduto']); ?>" required>
                        <label for="tituloProduto">Título do Produto</label>
                    </div>

                    <?php
                        // 1. Pega o ID do produto pela URL
                        $idProduto = intval($_GET['idProduto']);

                        // 2. Busca os dados atuais do produto
                        $queryProduto = "SELECT * FROM Produtos WHERE idProduto = $idProduto";
                        $resultadoProduto = mysqli_query($conn, $queryProduto);
                        $produto = mysqli_fetch_assoc($resultadoProduto);

                        // 3. Busca TODAS as categorias para listar no SELECT
                        $queryCategorias = "SELECT * FROM Categorias ORDER BY tituloCategoria ASC";
                        $resultadoCategorias = mysqli_query($conn, $queryCategorias);
                        ?>

                        <div class="form-group mb-3">
                            <label for="idCategoria" class="form-label"><strong>Categoria do Produto:</strong></label>
                            <select class="form-control" name="idCategoria" id="idCategoria" required>
                                <option value="">Selecione uma categoria</option>
                                
                                <?php 
                                // Loop para listar todas as categorias do banco
                                while ($cat = mysqli_fetch_assoc($resultadoCategorias)) {
                                    if ($cat['idCategoria'] == $produto['idCategoria']) {
                                        $selecionado = "selected";
                                    } else {
                                        $selecionado = "";
                                    }
                                    echo "<option value='{$cat['idCategoria']}' $selecionado>{$cat['tituloCategoria']}</option>";
                                }
                                ?>
                                
                            </select>
                        </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="descricaoProduto" name="descricaoProduto" 
                               value="<?php echo htmlspecialchars($produto['descricaoProduto']); ?>" required>
                        <label for="descricaoProduto">Descrição do Produto</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="precoProduto" name="precoProduto" 
                               value="<?php echo htmlspecialchars($produto['precoProduto']); ?>" required>
                        <label for="precoProduto">Valor do Produto</label>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="produtosAdmin.php" class="btn btn-danger w-50 fw-bold">Cancelar</a>
                        <button type="submit" class="btn btn-success w-50 fw-bold">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include "footer.php"; ?>