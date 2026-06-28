<?php include "header.php" ?>
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
        <a href="adminPainel.php">
            <img src="assets/logos/logo1.png" alt="" width="180px">
        </a>
        <ul>
            <li class="nav-item"><a href="#" class="nav-link" role="button"><i class="bi bi-person-circle"></i></a></li>
        </ul>
    </nav>
</header>

<main class="container py-5">
    <div class="row g-4 align-items-start"> <section class="col-lg-4">
            <div class="card shadow-sm p-4">
                <h2 class="text-center mb-4" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">
                    Cadastrar Promoção
                </h2>

                <form action="actionCrud.php" method="POST" class="was-validated" enctype="multipart/form-data">
                    <input type="hidden" name="acao" value="cadastrarPromocao">

                    <div class="form-floating mt-3 mb-3">
                        <input type="file" class="form-control" id="imgPromocao" name="imgPromocao" required>
                        <label for="imgPromocao">Imagem Promocional</label>
                    </div>

                    <div class="form-floating mt-3 mb-3">
                        <input type="text" class="form-control" id="tituloPromocao" placeholder="Título do Anúncio" name="tituloPromocao" required>
                        <label for="tituloPromocao">Título do Banner</label>
                    </div>

                    <div class="card p-3 mt-3 mb-3">
                        <label class="form-label fw-bold mb-2">Selecione os Produtos da Promoção:</label>
                        <div style="max-height: 200px; overflow-y: auto; padding-right: 5px;">
                            <?php 
                            include "conexaoBD.php";
                            $buscarProdutos = "SELECT idProduto, tituloProduto FROM produtos ORDER BY tituloProduto ASC";
                            $resultado = mysqli_query($conn, $buscarProdutos);

                            if (mysqli_num_rows($resultado) > 0):
                                while($produto = mysqli_fetch_assoc($resultado)): 
                            ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="produtos[]" value="<?php echo htmlspecialchars($produto['idProduto']); ?>" id="prod_<?php echo $produto['idProduto']; ?>">
                                    <label class="form-check-label" for="prod_<?php echo $produto['idProduto']; ?>">
                                        <?php echo htmlspecialchars($produto['tituloProduto']); ?>
                                    </label>
                                </div>
                            <?php 
                                endwhile; 
                            else:
                                echo "<p class='text-muted small mb-0'>Nenhum produto cadastrado no momento.</p>";
                            endif; 
                            ?>
                        </div>
                    </div>

                    <div class="form-floating mt-3 mb-3">
                        <input type="date" class="form-control" id="dataInicio" name="dataInicio" required>
                        <label for="dataInicio">Data de Início</label>
                    </div>

                    <div class="form-floating mt-3 mb-3">
                        <input type="date" class="form-control" id="dataFim" name="dataFim" required>
                        <label for="dataFim">Data de Encerramento</label>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">Criar Banner Promocional</button>
                </form>
            </div>
        </section>

        <section class="col-lg-8">
            <div class="card shadow-sm p-4">
                <h3 class="mb-4" style="color: var(--cor-fonte); font-weight: 700; font-size: 1.4em;">Promoções</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Banner</th>
                                <th>Promoção</th>
                                <th>Ações</th>
                            </tr>
                        </table>
                        <table class="table table-striped table-hover align-middle text-center mb-0">
                            <tbody>
                                <?php 
                                include "conexaoBD.php";
                                $buscarPromocoes = "SELECT idPromocao, tituloPromocao, imgPromocao FROM Promocoes ORDER BY idPromocao";
                                $resultado = mysqli_query($conn, $buscarPromocoes);

                                if(mysqli_num_rows($resultado) > 0):
                                    while($linha = mysqli_fetch_assoc($resultado)): 
                                ?>
                                    <tr>
                                        <td><strong><?php echo $linha['idPromocao']; ?></strong></td>
                                        <td>
                                            <img src="<?php echo $linha['imgPromocao']; ?>" alt="Imagem da Promocao" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td class="text-start"><?php echo htmlspecialchars($linha['tituloPromocao']); ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="editarPromocao.php?idPromocao=<?php echo $linha['idPromocao']; ?>" class="btn btn-warning btn-sm text-dark" title="Editar Promocao">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="actionCrud.php?acao=excluirPromocao&idPromocao=<?php echo $linha['idPromocao']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta Promoção?');" title="Excluir Promocao">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                    endwhile; 
                                else: 
                                ?>
                                    <tr>
                                        <td colspan="4" class="text-muted py-4">Nenhuma promoção cadastrada.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            
            <div class="col-12 mt-3">
                <a href="adminPainel.php" class="btn btn-link w-100 fw-bold shadow-sm">Voltar para Dashboard</a>
            </div>

    </div>
</main>
<?php include "footer.php" ?>