<?php include "header.php" ?>
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

<!-- Seção para conteúdo da página -->
<main class="container py-5">
    <div class="row g-4">

        <section class="col-lg-4">
            <div class="card shadow-sm p-4">
                <h2 class="text-center mb-4" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">
                    Cadastrar Produto
                </h2>

                <form action="actionCrud.php" method="POST" class="was-validated" enctype="multipart/form-data">

                    <div class="form-floating mt-3 mb-3">
                        <input type="file" class="form-control" id="imgProduto" placeholder="Imagem do Produto"
                            name="imgProduto">
                        <label for="imgProduto">Imagem</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-floating mt-3 mb-3">
                        <input type="text" class="form-control" id="tituloProduto" placeholder="Titulo do Produto"
                            name="tituloProduto">
                        <label for="tituloProduto">Titulo do Produto</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-floating mt-3 mb-3">
                        <select class="form-select" id="idCategoria" name="idCategoria" required>
                            <option value="" selected disabled>Selecione uma categoria</option>

                            <?php
                            // Lógica para puxar as categorias já cadastradas no banco de dados
                            include "conexaoBD.php";
                            $buscarCategorias = "SELECT idCategoria, tituloCategoria FROM categorias ORDER BY idCategoria";
                            $resultado = mysqli_query($conn, $buscarCategorias);

                            while ($categoria = mysqli_fetch_assoc($resultado)):
                                ?>
                                <option value="<?php echo htmlspecialchars($categoria['idCategoria']) ?>">
                                    <?php echo htmlspecialchars($categoria['tituloCategoria']) ?>
                                </option>
                            <?php endwhile; ?>

                        </select>
                        <label for="idCategoria">Categoria do Produto</label>
                    </div>

                    <div class="form-floating mt-3 mb-3">
                        <input type="text" class="form-control" id="descricaoProduto" placeholder="Descricao do Produto"
                            name="descricaoProduto">
                        <label for="descricaoProduto">Descrição do Produto</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-floating mt-3 mb-3">
                        <input type="text" class="form-control" id="precoProduto" placeholder="Valor Produto"
                            name="precoProduto">
                        <label for="precoProduto">Valor do Produto</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <button name="acao" value="cadastrarProduto" type="submit" class="btn btn-success">Cadastrar
                        Produto</button>
                </form>
            </div>
        </section>
        <section class="col-lg-8">
            <div class="card shadow-sm p-4">
                <h3 class="mb-4" style="color: var(--cor-fonte); font-weight: 700; font-size: 1.4em;">
                    Produtos Cadastrados
                </h3>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Imagem</th>
                                <th>Produto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 1. Inclui o banco de dados
                            include "conexaoBD.php";

                            // 2. Cria a query para buscar as Produtos
                            $buscarProdutos = "SELECT idProduto, tituloProduto, imgProduto FROM Produtos ORDER BY idProduto";
                            $resultado = mysqli_query($conn, $buscarProdutos);

                            // 3. Verifica se existe pelo menos 1 Produto no banco
                            if (mysqli_num_rows($resultado) > 0):
                                // 4. Loop de renderização
                                while ($linha = mysqli_fetch_assoc($resultado)):
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $linha['idProduto']; ?></strong></td>

                                        <td>
                                            <img src="<?php echo $linha['imgProduto']; ?>" alt="Imagem da Produto"
                                                class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>

                                        <td class="text-start"><?php echo htmlspecialchars($linha['tituloProduto']); ?></td>

                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="editarProduto.php?idProduto=<?php echo $linha['idProduto']; ?>"
                                                    class="btn btn-warning btn-sm text-dark" title="Editar Produto">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>

                                                <a href="actionCrud.php?acao=excluirProduto&idProduto=<?php echo $linha['idProduto']; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Tem certeza que deseja excluir esta Produto?');"
                                                    title="Excluir Produto">
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
                                    <td colspan="4" class="text-muted py-4">Nenhum produto cadastrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <a href="adminPainel.php"><button class="btn btn-link w-100 fw-bold">Voltar para Dashboard</button></a>
    </div>
</main>
<?php include "footer.php" ?>