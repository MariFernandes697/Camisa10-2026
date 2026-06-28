<?php 
include "header.php";
include "conexaoBD.php";

// Captura o ID da categoria que será editada
if (isset($_GET['idCategoria'])) {
    $idCategoria = intval($_GET['idCategoria']);
    
    // Busca os dados atuais dela no banco
    $buscar = "SELECT * FROM Categorias WHERE idCategoria = $idCategoria";
    $resultado = mysqli_query($conn, $buscar);
    $categoria = mysqli_fetch_assoc($resultado);
} else {
    header("location: categoriasAdmin.php");
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
                    Editar Categoria
                </h2>

                <form action="actionCrud.php" method="POST" class="was-validated" enctype="multipart/form-data">
                    <input type="hidden" name="acao" value="editarCategoria">
                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria; ?>">
                    
                    <div class="text-center mb-3">
                        <p class="small text-muted mb-1">Imagem Atual:</p>
                        <img src="<?php echo $categoria['imgCategoria']; ?>" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>

                    <div class="form-floating mb-3">
                        <input type="file" class="form-control" id="imgCategoria" name="imgCategoria">
                        <label for="imgCategoria">Substituir Foto (Opcional)</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="tituloCategoria" name="tituloCategoria" 
                               value="<?php echo htmlspecialchars($categoria['tituloCategoria']); ?>" required>
                        <label for="tituloCategoria">Título da Categoria</label>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="categoriasAdmin.php" class="btn btn-danger w-50 fw-bold">Cancelar</a>
                        <button type="submit" class="btn btn-success w-50 fw-bold">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include "footer.php"; ?>