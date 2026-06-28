

<?php
// Inclui a conexão com o banco de dados que todas as ações vão usar
include "conexaoBD.php";

// Função para filtrar entrada de dados (reaproveitada para todo o sistema)
function filtrar_entrada($dado){
    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado);
    return $dado;
}

// Função para validação do upload de imagens - Otimização do código do professor
function validacao_upload_imagem($campoArquivo, $diretorioDestino){
    if (!isset($_FILES[$campoArquivo]) || $_FILES[$campoArquivo]["size"] == 0) {
        echo "<div class='alert alert-warning text-center'>O campo <strong>imagem</strong> é obrigatório!</div>";
        return false;
    }

    $arquivo = $_FILES[$campoArquivo];
    $caminhoFinal = $diretorioDestino . basename($arquivo['name']);
    $tipoDaImagem = strtolower(pathinfo($caminhoFinal, PATHINFO_EXTENSION));

    // Validação de Tamanho (Máximo 5MB)
    if ($arquivo["size"] > 5000000) {
        echo "<div class='alert alert-warning text-center'>A imagem deve ter tamanho máximo de 5MB!</div>";
        return false;
    }

    // Validação de Formato
    $formatosPermitidos = ["jpg", "jpeg", "png", "webp"];
    if (!in_array($tipoDaImagem, $formatosPermitidos)) {
        echo "<div class='alert alert-warning text-center'>A imagem deve estar nos formatos JPG, JPEG, PNG ou WEBP!</div>";
        return false;
    }

    // Move o arquivo temporário para a pasta definitiva
    if (!move_uploaded_file($arquivo["tmp_name"], $caminhoFinal)) {
        echo "<div class='alert alert-warning text-center'>Erro ao tentar mover a imagem para o diretório $diretorioDestino!</div>";
        return false;
    }

    return $caminhoFinal;
}

// Verifica se a ação veio por POST (Formulários) ou GET (Links)
$acao = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acao"])) {
    $acao = $_POST["acao"];
} elseif (isset($_GET["acao"])) {
    $acao = $_GET["acao"];
}

//echo "<strong>Ação recebida:</strong> '" . $acao . "'<br>";
//echo "<strong>ID recebido:</strong> '" . ($_GET['id'] ?? 'NENHUM') . "'<br>";
//die("teste");

// Se uma ação válida foi identificada, passamos pela chave seletora
if (!empty($acao)) {

    switch ($acao) {
        // Categorias
        case "cadastrarCategoria":
            //Verifica o método de requisição do servidor
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                //Define o bloco de variáveis para armazenar as informações recebidas do formulário
                $imgCategoria = $tituloCategoria = "";
                //Variável booleana para controle de erros de preenchimento
                $erroPreenchimento = false;

                //Validação do campo tituloCategoria
                //Utiliza a função empty() para verificar se o campo está vazio
                if(empty($_POST["tituloCategoria"])){
                    echo "<div class='alert alert-warning text-center'>O campo <strong>TÍTULO DO ANÚNCIO</strong> é obrigatório!</div>";
                    $erroPreenchimento = true;
                }
                else{
                    //Filtra e Armazena o valor na variável
                    $tituloCategoria = filtrar_entrada($_POST["tituloCategoria"]);
                }
                //Chamada da validação da imgCategoria
                $imgCategoria = validacao_upload_imagem("imgCategoria", "assets/categorias/");

                //Se NÃO houver erro de preenchimento e NÃO houver erro no upload da img
                if(!$erroPreenchimento && $imgCategoria){
                    
                    //Criar uma variável para armazenar a QUERY que realiza a inserção de dados do Usuário na tabela Usuarios
                    $inserirCategoria = "INSERT INTO Categorias (tituloCategoria, imgCategoria) VALUES ('$tituloCategoria', '$imgCategoria')";
                    //Inclui o arquivo de conexão com o Banco de Dados
                    include "conexaoBD.php";

                    //Se conseguir executar a QUERY para inserção, exibe alerta de sucesso e a tabela com os dados informados
                    //A funçao mysqli_query executa operações no Banco de Dados
                    if(mysqli_query($conn, $inserirCategoria)){

                        header("location:categoriasAdmin.php");
                    }
                    else{
                        echo "<div class='alert alert-danger text-center'>
                        Erro ao tentar inserir dados do<strong>ANÚNCIO</strong> no banco de dados $database!</div>" . mysqli_error($conn);
                    }
                }

            }
            else{
                header("location:categoriasAdmin.php");
            }
            break;
        
        case "editarCategoria":
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $idCategoria = intval($_POST["idCategoria"]);
                $tituloCategoria = filtrar_entrada($_POST["tituloCategoria"]);
                $erroPreenchimento = false;

                if(empty($tituloCategoria)){
                    echo "<div class='alert alert-warning text-center'>O campo título é obrigatório!</div>";
                    $erroPreenchimento = true;
                }

                // Lógica da Imagem na Edição:
                // Se o usuário enviou um arquivo novo, fazemos o upload
                if ($_FILES['imgCategoria']['size'] > 0) {
                    $imgCategoria = validacao_upload_imagem("imgCategoria", "assets/categorias/");
                } else {
                    // Se não enviou, buscamos no banco qual era a imagem antiga para não perdê-la
                    $buscaFotoAntiga = "SELECT imgCategoria FROM Categorias WHERE idCategoria = $idCategoria";
                    $resultadoImg = mysqli_query($conn, $buscaFotoAntiga);
                    $imgA = mysqli_fetch_assoc($resultadoImg);
                    $imgCategoria = $imgA['imgCategoria']; // Mantém a foto atual
                }

                // Se o texto está ok e temos uma imagem definida (nova ou antiga)
                if(!$erroPreenchimento && $imgCategoria){
                    
                    // Query de UPDATE do SQL
                    $editar = "UPDATE Categorias SET tituloCategoria = '$tituloCategoria', imgCategoria = '$imgCategoria' WHERE idCategoria = $idCategoria";
                    
                    if(mysqli_query($conn, $editar)){
                        header("location:categoriasAdmin.php");
                    } else {
                        echo "<div class='alert alert-danger text-center'>Erro ao atualizar no banco de dados.</div>";
                    }
                }
            }
            break;
        
        case "excluirCategoria":
            // Como o ID veio pelo link, pegamos via GET
            if (isset($_GET['idCategoria'])) {
                $idCategoria = intval($_GET['idCategoria']);

                // Query para deletar a categoria específica
                $deletarCategoria = "DELETE FROM Categorias WHERE idCategoria = $idCategoria";

                if (mysqli_query($conn, $deletarCategoria)) {
                    // Redireciona de volta para a página de listagem atualizando a tela
                    header("location: categoriasAdmin.php?sucesso=excluido");
                    exit;
                } else {
                    echo "<div class='alert alert-danger text-center'>Erro ao tentar excluir a categoria. Certifique-se de que não existem promoções vinculados a ela!</div>";
                }
            }
            break;


        // Produtos
        case "cadastrarProduto":
            //Verifica o método de requisição do servidor
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //Define o bloco de variáveis para armazenar as informações recebidas do formulário
            $imgProduto = $tituloProduto = $idCategoria = $descricaoProduto = $precoProduto = "";
            //Variável booleana para controle de erros de preenchimento
            $erroPreenchimento = false;

            //Validação do campo tituloProduto
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["tituloProduto"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>TÍTULO DO PRODUTO</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Filtra e Armazena o valor na variável
                $tituloProduto = filtrar_entrada($_POST["tituloProduto"]);
            }

            // NOVO: Validação do campo idCategoria (Supondo que o select do HTML envie 'idCategoria')
            if(empty($_POST["idCategoria"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>CATEGORIA</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                $idCategoria = filtrar_entrada($_POST["idCategoria"]);
            }

            //Validação do campo descricaoProduto
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["descricaoProduto"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>DESCRIÇÃO DO PRODUTO</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Filtra e Armazena o valor na variável
                $descricaoProduto = filtrar_entrada($_POST["descricaoProduto"]);
            }

            //Validação do campo precoProduto
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["precoProduto"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>VALOR DO PRODUTO</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Filtra e Armazena o valor na variável
                $precoProduto = filtrar_entrada($_POST["precoProduto"]);
            }
            // Chamada da função de validação da imgProduto
            $imgProduto = validacao_upload_imagem("imgProduto", "assets/produtos/");



            //Se NÃO houver erro de preenchimento e NÃO houver erro no upload da img
            if(!$erroPreenchimento && $imgProduto){
                
                //Criar uma variável para armazenar a QUERY que realiza a inserção de dados do Usuário na tabela Usuarios
                $inserirProduto = "INSERT INTO Produtos (tituloProduto, imgProduto, idCategoria, descricaoProduto, precoProduto) VALUES ('$tituloProduto', '$imgProduto', '$idCategoria', '$descricaoProduto', '$precoProduto')";


                //Inclui o arquivo de conexão com o Banco de Dados
                include "conexaoBD.php";

                //Se conseguir executar a QUERY para inserção, exibe alerta de sucesso e a tabela com os dados informados
                //A funçao mysqli_query executa operações no Banco de Dados
                
                if(mysqli_query($conn, $inserirProduto)){
                    echo "<div class='container'>";
                        echo "<div class='alert alert-success text-center'><strong>Produto</strong> cadastrado com sucesso!</div>";
                    echo "</div>";
                }
                else{
                    echo "<div class='alert alert-danger text-center'>
                    Erro ao tentar inserir dados do<strong>ANÚNCIO</strong> no banco de dados $database!</div>" . mysqli_error($conn);
                }
            }

        }
        else{
            
            header("location:produtoAdmin.php");
        }
            break;

        case "editarProduto":
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $idProduto = intval($_POST["idProduto"]);
                $tituloProduto = filtrar_entrada($_POST["tituloProduto"]);
                $idCategoriaProduto = filtrar_entrada($_POST["idCategoria"]);
                $descricaoProduto = filtrar_entrada($_POST["descricaoProduto"]);
                $precoProduto = filtrar_entrada($_POST["precoProduto"]);
                $erroPreenchimento = false;

                if(empty($tituloProduto)){
                    echo "<div class='alert alert-warning text-center'>O campo título é obrigatório!</div>";
                    $erroPreenchimento = true;
                }

                // Lógica da Imagem na Edição:
                // Se o usuário enviou um arquivo novo, fazemos o upload
                if ($_FILES['imgProduto']['size'] > 0) {
                    $imgProduto = validacao_upload_imagem("imgProduto", "assets/produtos/");
                } else {
                    // Se não enviou, buscamos no banco qual era a imagem antiga para não perdê-la
                    $buscaFotoAntiga = "SELECT imgProduto FROM Produtos WHERE idProduto = $idProduto";
                    $resultadoImg = mysqli_query($conn, $buscaFotoAntiga);
                    $imgA = mysqli_fetch_assoc($resultadoImg);
                    $imgProduto = $imgA['imgProduto']; // Mantém a foto atual
                }

                // Se o texto está ok e temos uma imagem definida (nova ou antiga)
                if(!$erroPreenchimento && $imgProduto){
                    
                    // Query de UPDATE do SQL
                    $editar = "UPDATE Produtos SET tituloProduto = '$tituloProduto', imgProduto = '$imgProduto', idCategoria = '$idCategoriaProduto', descricaoProduto = '$descricaoProduto', precoProduto = '$precoProduto' WHERE idProduto = $idProduto";
                    
                    if(mysqli_query($conn, $editar)){
                        header("location:produtosAdmin.php");
                    } else {
                        echo "<div class='alert alert-danger text-center'>Erro ao atualizar no banco de dados.</div>";
                    }
                }
            }
            break;
        
        case "excluirProduto":
            // Como o ID veio pelo link, pegamos via GET
            if (isset($_GET['idProduto'])) {
                $idProduto = intval($_GET['idProduto']);

                // Query para deletar a Produto específica
                $deletarProduto = "DELETE FROM Produtos WHERE idProduto = $idProduto";

                if (mysqli_query($conn, $deletarProduto)) {
                    // Redireciona de volta para a página de listagem atualizando a tela
                    header("location: produtosAdmin.php?sucesso=excluido");
                    exit;
                } else {
                    echo "<div class='alert alert-danger text-center'>Erro ao tentar excluir a Produto. Certifique-se de que não existem produtos vinculados a ela!</div>";
                }
            }
            break;


        // Promoções
        case "cadastrarPromocao":
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $imgPromocao = $tituloPromocao = $dataInicio = $dataFim = "";
                $erroPreenchimento = false;

                if(empty($_POST["tituloPromocao"])){
                    echo "<div class='alert alert-warning text-center'>O campo <strong>TÍTULO</strong> é obrigatório!</div>";
                    $erroPreenchimento = true;
                } else {
                    $tituloPromocao = filtrar_entrada($_POST["tituloPromocao"]);
                }

                $dataInicio = $_POST['dataInicio'];
                $dataFim    = $_POST['dataFim'];

                if (strtotime($dataFim) < strtotime($dataInicio)) {
                    echo "<div class='alert alert-danger text-center'>A data de término não pode ser menor que a data de início!</div>";
                    $erroPreenchimento = true;
                }

                // Faz o upload usando o nome padronizado
                $imgPromocao = validacao_upload_imagem("imgPromocao", "assets/promocoes/");

                if(!$erroPreenchimento && $imgPromocao){
                    include "conexaoBD.php";

                    // Query usando nomes padronizados das colunas
                    $inserirPromocao = "INSERT INTO Promocoes (tituloPromocao, imgPromocao, dataInicio, dataFim) VALUES ('$tituloPromocao', '$imgPromocao', '$dataInicio', '$dataFim')";
                
                    if(mysqli_query($conn, $inserirPromocao)){
                        $idPromocao = mysqli_insert_id($conn);

                        if(!empty($_POST['produtos']) && is_array($_POST['produtos'])){
                            foreach($_POST['produtos'] as $idProduto){
                                $idProduto = intval($idProduto);
                                $inserirProdutosPromocao = "INSERT INTO produtosPromocao (idPromocao, idProduto) VALUES ($idPromocao, $idProduto)";
                                mysqli_query($conn, $inserirProdutosPromocao);
                            }
                        }
                        header("location: promocoesAdmin.php?sucesso=cadastrado");
                        exit;
                    } else {
                        echo "<div class='alert alert-danger text-center'>Erro ao tentar inserir dados no banco de dados!</div>" . mysqli_error($conn);
                    }
                }
            } else {
                header("location:promocoesAdmin.php");
            }
            break;

        case "editarPromocao":
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                include "conexaoBD.php";
                $idPromocao = intval($_POST["idPromocao"]);
                $tituloPromocao = filtrar_entrada($_POST["tituloPromocao"]);
                $dataInicio = $_POST['dataInicio'];
                $dataFim = $_POST['dataFim'];
                $erroPreenchimento = false;

                // Validação da imagem
                if (isset($_FILES['imgPromocao']) && $_FILES['imgPromocao']['size'] > 0) {
                    $imgPromocao = validacao_upload_imagem("imgPromocao", "assets/promocoes/");
                } else {
                    $buscaFotoAntiga = "SELECT imgPromocao FROM Promocoes WHERE idPromocao = $idPromocao";
                    $resultadoImg = mysqli_query($conn, $buscaFotoAntiga);
                    $imgA = mysqli_fetch_assoc($resultadoImg);
                    $imgPromocao = $imgA['imgPromocao'];
                }

                if(!$erroPreenchimento && $imgPromocao){
                    $editarPromo = "UPDATE Promocoes SET tituloPromocao = '$tituloPromocao', imgPromocao = '$imgPromocao', dataInicio = '$dataInicio', dataFim = '$dataFim' WHERE idPromocao = $idPromocao";
                    
                    if(mysqli_query($conn, $editarPromo)){
                        // Deleta vínculos antigos
                        $deletarVinculosAntigos = "DELETE FROM produtosPromocao WHERE idPromocao = $idPromocao";
                        mysqli_query($conn, $deletarVinculosAntigos);

                        // Insere novos selecionados
                        if(!empty($_POST['produtos']) && is_array($_POST['produtos'])){
                            foreach($_POST['produtos'] as $idProduto){
                                $idProduto = intval($idProduto);
                                $inserirProdutosPromocao = "INSERT INTO produtosPromocao (idPromocao, idProduto) VALUES ($idPromocao, $idProduto)";
                                mysqli_query($conn, $inserirProdutosPromocao);
                            }
                        }

                        header("location: promocoesAdmin.php?sucesso=atualizado");
                        exit;
                    } else {
                        echo "<div class='alert alert-danger text-center'>Erro ao editar a promoção.</div>";
                    }
                }
            }
            break;
                
                case "excluirPromocao":
                    // Como o ID veio pelo link, pegamos via GET
                    if (isset($_GET['idPromocao'])) {
                        $idPromocao = intval($_GET['idPromocao']);

                        // Query para deletar a Promocao específica
                        $deletarPromocao = "DELETE FROM Promocoes WHERE idPromocao = $idPromocao";

                        if (mysqli_query($conn, $deletarPromocao)) {
                            // Redireciona de volta para a página de listagem atualizando a tela
                            header("location: promocoesAdmin.php?sucesso=excluido");
                            exit;
                        } else {
                            echo "<div class='alert alert-danger text-center'>Erro ao tentar excluir a Promoção. Certifique-se de que não existem promocaos vinculados a ela!</div>";
                        }
                    }
                    break;

                default:
                    echo "Ação inválida ou não encontrada.";
                    break;
            }

        } else {
            // Se tentarem acessar o controlador direto sem passar ação, redireciona
            header("location: adminPainel.php");
            exit;
        }
?>