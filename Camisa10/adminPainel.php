
<?php include "header.php";?>
<style>
.categorias {
    width: 100%; 
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    box-sizing: border-box;
}

.cards-categorias {
    display: flex;
    flex-wrap: nowrap; 
    justify-content: center; 
    gap: 20px;
    width: 100%;
    max-width: 1200px;
}

.product-card {
    width: 210px;
    height: 120px;
    flex: 0 0 auto;  
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ece6e6d4; 
    border-radius: 15px;
    box-shadow: 0 10px 10px rgba(0, 0, 0, 0.15); 
    
    transition: transform 220ms ease, box-shadow 220ms;
    cursor: pointer;
    text-decoration: none; /* Remove sublinhado do link */
}

.card-figure {
    margin: 0;
    padding: 15px;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-figure a {
    text-decoration: none;
    width: 100%;
}

.card-title {
    margin: 0;
    text-align: center;
    font-weight: 700; /* Negrito marcante igual ao print */
    font-size: 1.05rem;
    color: #000000; /* Texto totalmente preto */
    font-family: sans-serif;
}

/* Efeito sutil ao passar o mouse */
.product-card:hover,
.product-card:focus {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}
</style>
<header>
    <nav>
        <a href="index.php">
            <!-- Logo -->
            <img src="assets/logos/logo1.png" alt="" width="180px">
        </a>
        <ul>
            <!-- Icone usuário -->
            <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<li class="nav-item dropdown">

    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
       href="#"
       id="adminDropdown"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false">

        <i class="bi bi-person-circle" style="font-size: 1.3rem;"></i>

        <?php if(isset($_SESSION['admin_logado'])): ?>
            <span>Administrador</span>
        <?php endif; ?>

    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">

        <li>
            <a class="dropdown-item text-danger" href="logout.php?origem=admin">
                <i class="bi bi-box-arrow-right"></i>Sair</a>
        </li>
    </ul>
</li>
</ul>
</nav>
</header>
<?php include "conexaoBD.php";
// 2. Busca todas as mensagens enviadas, ordenando pelas mais recentes
$buscarMensagens = "SELECT idContato, nomeContato, emailContato, mensagemContato, dataEnvio FROM formContato ORDER BY idContato DESC";

// Ativa relatórios de erro para ajudar você a testar
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $resultado = mysqli_query($conn, $buscarMensagens);
} catch (mysqli_sql_exception $e) {
    die("<div class='alert alert-danger text-center m-5'>Erro no banco: " . $e->getMessage() . "</div>");
}
?>

<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center mt-5 mb-1" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">Dúvidas Recebidas</h2>
        <span class="badge bg-success fs-6 mt-5 mb-1"><?php echo mysqli_num_rows($resultado); ?> mensagens</span>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 20%;">Nome</th>
                    <th style="width: 20%;">E-mail</th>
                    <th style="width: 40%;">Mensagem / Assunto</th>
                    <th style="width: 15%;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // 3. Se houver mensagens, o while renderiza cada linha na tabela
                if (mysqli_num_rows($resultado) > 0): 
                    while ($contato = mysqli_fetch_assoc($resultado)): 
                ?>
                    <tr>
                        <td class="text-center"><strong><?= $contato['idContato'] ?></strong></td>
                        <td><?= htmlspecialchars($contato['nomeContato']) ?></td>
                        <td><a href="mailto:<?= $contato['emailContato'] ?>"><?= htmlspecialchars($contato['emailContato']) ?></a></td>
                        <td><?= nl2br(htmlspecialchars($contato['mensagemContato'])) ?></td>
                        <td class="text-center">
                            <a href="mailto:<?= $contato['emailContato'] ?>?subject=Contato Camisa 10" class="btn btn-sm btn-danger" title="Responder por E-mail">
                                <i class="bi bi-reply-fill"></i> Responder
                            </a>
                        </td>
                    </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Nenhuma mensagem recebida até o momento.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Painel de pedidos -->
<?php
include "conexaoBD.php";
// Query para o Admin ver os pedidos ordenados pelos mais recentes
$queryAdminPedidos = "SELECT p.idPedido, p.dataPedido, p.formaPagamento, p.totalPedido, p.statusPedido,
                             c.nomeCliente, e.cidade, e.estado
                      FROM pedidos p
                      INNER JOIN clientes c ON p.idCliente = c.idCliente
                      INNER JOIN enderecosClientes e ON p.idEndereco = e.idEndereco
                      ORDER BY p.dataPedido DESC";

$resultadoAdmin = mysqli_query($conn, $queryAdminPedidos);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center mb-1" style="font-size: 1.5em; font-weight: 700; color: var(--cor-fonte);">Pedidos Realizados</h2>
        <span class="badge bg-success fs-6 mb-1"><?php echo mysqli_num_rows($resultadoAdmin); ?> pedido(s)</span>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Cidade/UF</th>
                    <th>Pagamento</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultadoAdmin) > 0): ?>
                    <?php while($pedido = mysqli_fetch_assoc($resultadoAdmin)): ?>
                    <tr>
                        <td class="text-center"><strong><?php echo $pedido['idPedido']; ?></strong></td>
                        <td><?php echo htmlspecialchars($pedido['nomeCliente']); ?></td>
                        <td class="text-center"><?php echo date('d/m/Y H:i', strtotime($pedido['dataPedido'])); ?></td>
                        <td><?php echo htmlspecialchars($pedido['cidade']) . "/" . $pedido['estado']; ?></td>
                        <td class="text-center"><?php echo $pedido['formaPagamento']; ?></td>
                        <td class="text-end fw-bold">R$ <?php echo number_format($pedido['totalPedido'], 2, ',', '.'); ?></td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark"><?php echo $pedido['statusPedido']; ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Nenhum pedido realizado até o momento.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="categorias">
    <div class="cards-categorias">
        
        <article class="product-card" onclick="location.href='produtosAdmin.php'">
            <figure class="card-figure">
                <a href="produtosAdmin.php">
                    <figcaption class="card-title">Gerenciar Produtos</figcaption>
                </a>
            </figure>
        </article>

        <article class="product-card" onclick="location.href='categoriasAdmin.php'">
            <figure class="card-figure">
                <a href="categoriasAdmin.php">
                    <figcaption class="card-title">Gerenciar Categorias</figcaption>
                </a>
            </figure>
        </article>

        <article class="product-card" onclick="location.href='promocoesAdmin.php'">
            <figure class="card-figure">
                <a href="promocoesAdmin.php">
                    <figcaption class="card-title">Gerenciar Promoções</figcaption>
                </a>
            </figure>
        </article>

        <article class="product-card" onclick="location.href='#'">
            <figure class="card-figure">
                <a href="#">
                    <figcaption class="card-title">Gerenciar Vendas</figcaption>
                </a>
            </figure>
        </article>

        <article class="product-card" onclick="location.href='#'">
            <figure class="card-figure">
                <a href="#">
                    <figcaption class="card-title">Geral</figcaption>
                </a>
            </figure>
        </article>
    
    </div>
</div>

<?php include 'footer.php';?>
