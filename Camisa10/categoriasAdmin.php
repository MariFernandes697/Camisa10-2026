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
<!-- Cadastro de Categorias -->
<main class="container py-5">
    <div class="row g-4">
        
        <section class="col-lg-4">
            <div class="card shadow-sm p-4">
                <h2 class="text-center mb-4" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">
                    Criar Categoria
                </h2>

                <form action="actionCrud.php" method="POST" class="was-validated" enctype="multipart/form-data">
                    <input type="hidden" name="acao" value="cadastrarCategoria">
                    
                    <div class="form-floating mb-3">
                        <input type="file" class="form-control" id="imgCategoria" placeholder="img" name="imgCategoria" required>
                        <label for="imgCategoria">Foto Categoria</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="tituloCategoria" placeholder="Título do Anúncio" name="tituloCategoria" required>
                        <label for="tituloCategoria">Título da Categoria</label>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 fw-bold">Criar Categoria</button>
                </form>
            </div>
        </section>

        <section class="col-lg-8">
            <div class="card shadow-sm p-4">
                <h3 class="mb-4" style="color: var(--cor-fonte); font-weight: 700; font-size: 1.4em;">
                    Categorias Cadastradas
                </h3>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Imagem</th>
                                <th>Categoria</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // 1. Inclui o banco de dados
                            include "conexaoBD.php";
                            
                            // 2. Cria a query para buscar as categorias
                            $buscarCategorias = "SELECT idCategoria, tituloCategoria, imgCategoria FROM Categorias ORDER BY idCategoria";
                            $resultado = mysqli_query($conn, $buscarCategorias);

                            // 3. Verifica se existe pelo menos 1 categoria no banco
                            if(mysqli_num_rows($resultado) > 0):
                                // 4. Loop de renderização
                                while($linha = mysqli_fetch_assoc($resultado)): 
                            ?>
                                <tr>
                                    <td><strong><?php echo $linha['idCategoria']; ?></strong></td>
                                    
                                    <td>
                                        <img src="<?php echo $linha['imgCategoria']; ?>" alt="Imagem da Categoria" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    
                                    <td class="text-start"><?php echo htmlspecialchars($linha['tituloCategoria']); ?></td>
                                    
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="editarCategoria.php?id=<?php echo $linha['idCategoria']; ?>" 
                                               class="btn btn-warning btn-sm text-dark" 
                                               title="Editar Categoria">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <a href="actionCrud.php?acao=excluirCategoria&idCategoria=<?php echo $linha['idCategoria']; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Tem certeza que deseja excluir esta categoria?');"
                                               title="Excluir Categoria">
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
                                    <td colspan="4" class="text-muted py-4">Nenhuma categoria cadastrada ainda.</td>
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