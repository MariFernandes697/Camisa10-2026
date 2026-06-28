<?php
include "conexaoBD.php";

// Função para filtrar entrada de dados
function filtrar_entrada($dado)
{
    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado);
    return $dado;
}

// Função para validação do upload de imagens - Otimização com Alerts em JS
function validacao_upload_imagem($campoArquivo, $diretorioDestino)
{
    if (!isset($_FILES[$campoArquivo]) || $_FILES[$campoArquivo]["size"] == 0) {
        echo "<script>alert('O campo imagem é obrigatório!'); window.history.back();</script>";
        exit();
    }

    $arquivo = $_FILES[$campoArquivo];
    $caminhoFinal = $diretorioDestino . basename($arquivo['name']);
    $tipoDaImagem = strtolower(pathinfo($caminhoFinal, PATHINFO_EXTENSION));

    // Validação de Tamanho (Máximo 5MB)
    if ($arquivo["size"] > 5000000) {
        echo "<script>alert('A imagem deve ter tamanho máximo de 5MB!'); window.history.back();</script>";
        exit();
    }

    // Validação de Formato
    $formatosPermitidos = ["jpg", "jpeg", "png", "webp"];
    if (!in_array($tipoDaImagem, $formatosPermitidos)) {
        echo "<script>alert('A imagem deve estar nos formatos JPG, JPEG, PNG ou WEBP!'); window.history.back();</script>";
        exit();
    }

    // Move o arquivo temporário para a pasta definitiva
    if (!move_uploaded_file($arquivo["tmp_name"], $caminhoFinal)) {
        echo "<script>alert('Erro ao tentar mover a imagem para o diretório de destino!'); window.history.back();</script>";
        exit();
    }

    return $caminhoFinal;
}

// Verifica se a ação veio por POST ou GET
$acao = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acao"])) {
    $acao = $_POST["acao"];
} elseif (isset($_GET["acao"])) {
    $acao = $_GET["acao"];
}

// Se uma ação válida foi identificada
if (!empty($acao)) {

    switch ($acao) {
        // Categorias
        case "cadastrarCategoria":
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $imgCategoria = $tituloCategoria = "";

                //Validação do campo tituloCategoria
                if (empty($_POST["tituloCategoria"])) {
                    echo "<script>alert('O campo TÍTULO DO ANÚNCIO é obrigatório!'); window.history.back();</script>";
                    exit();
                } else {
                    $tituloCategoria = filtrar_entrada($_POST["tituloCategoria"]);
                }

                //Chamada da validação da imgCategoria (já interrompe se der erro)
                $imgCategoria = validacao_upload_imagem("imgCategoria", "assets/categorias/");

                $inserirCategoria = "INSERT INTO Categorias (tituloCategoria, imgCategoria) VALUES ('$tituloCategoria', '$imgCategoria')";
                include "conexaoBD.php";

                if (mysqli_query($conn, $inserirCategoria)) {
                    header("location:categoriasAdmin.php");
                    exit();
                } else {
                    echo "<script>alert('Erro ao tentar inserir dados do ANÚNCIO no banco de dados!'); window.history.back();</script>";
                    exit();
                }

            } else {
                header("location:categoriasAdmin.php");
                exit();
            }
            break;

        case "editarCategoria":
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $idCategoria = intval($_POST["idCategoria"]);
                $tituloCategoria = filtrar_entrada($_POST["tituloCategoria"]);

                if (empty($tituloCategoria)) {
                    echo "<script>alert('O campo título é obrigatório!'); window.history.back();</script>";
                    exit();
                }

                // Lógica da Imagem na Edição
                if ($_FILES['imgCategoria']['size'] > 0) {
                    $imgCategoria = validacao_upload_imagem("imgCategoria", "assets/categorias/");
                } else {
                    $buscaFotoAntiga = "SELECT imgCategoria FROM Categorias WHERE idCategoria = $idCategoria";
                    $resultadoImg = mysqli_query($conn, $buscaFotoAntiga);
                    $imgA = mysqli_fetch_assoc($resultadoImg);
                    $imgCategoria = $imgA['imgCategoria'];
                }

                $editar = "UPDATE Categorias SET tituloCategoria = '$tituloCategoria', imgCategoria = '$imgCategoria' WHERE idCategoria = $idCategoria";

                if (mysqli_query($conn, $editar)) {
                    header("location:categoriasAdmin.php");
                    exit();
                } else {
                    echo "<script>alert('Erro ao atualizar no banco de dados.'); window.history.back();</script>";
                    exit();
                }
            }
            break;

        case "excluirCategoria":
            if (isset($_GET['idCategoria'])) {
                $idCategoria = intval($_GET['idCategoria']);
                $deletarCategoria = "DELETE FROM Categorias WHERE idCategoria = $idCategoria";

                if (mysqli_query($conn, $deletarCategoria)) {
                    header("location: categoriasAdmin.php?sucesso=excluido");
                    exit;
                } else {
                    echo "<script>alert('Erro ao tentar excluir a categoria. Certifique-se de que não existem promoções vinculadas a ela!'); window.history.back();</script>";
                    exit();
                }
            }
            break;

        // Produtos
        case "cadastrarProduto":
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $imgProduto = $tituloProduto = $idCategoria = $descricaoProduto = $precoProduto = "";

                if (empty($_POST["tituloProduto"])) {
                    echo "<script>alert('O campo TÍTULO DO PRODUTO é obrigatório!'); window.history.back();</script>";
                    exit();
                } else {
                    $tituloProduto = filtrar_entrada($_POST["tituloProduto"]);
                }

                if (empty($_POST["idCategoria"])) {
                    echo "<script>alert('O campo CATEGORIA é obrigatório!'); window.history.back();</script>";
                    exit();
                } else {
                    $idCategoria = filtrar_entrada($_POST["idCategoria"]);
                }

                if (empty($_POST["descricaoProduto"])) {
                    echo "<script>alert('O campo DESCRIÇÃO DO PRODUTO é obrigatório!'); window.history.back();</script>";
                    exit();
                } else {
                    $descricaoProduto = filtrar_entrada($_POST["descricaoProduto"]);
                }

                if (empty($_POST["precoProduto"])) {
                    echo "<script>alert('O campo VALOR DO PRODUTO é obrigatório!'); window.history.back();</script>";
                    exit();
                } else {
                    $precoProduto = filtrar_entrada($_POST["precoProduto"]);
                }

                $imgProduto = validacao_upload_imagem("imgProduto", "assets/produtos/");

                $inserirProduto = "INSERT INTO Produtos (tituloProduto, imgProduto, idCategoria, descricaoProduto, precoProduto) VALUES ('$tituloProduto', '$imgProduto', '$idCategoria', '$descricaoProduto', '$precoProduto')";
                include "conexaoBD.php";

                if (mysqli_query($conn, $inserirProduto)) {
                    echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='produtosAdmin.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Erro ao tentar inserir dados do ANÚNCIO no banco de dados!'); window.history.back();</script>";
                    exit();
                }

            } else {
                header("location:produtosAdmin.php");
                exit();
            }
            break;

        case "editarProduto":
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $idProduto = intval($_POST["idProduto"]);
                $tituloProduto = filtrar_entrada($_POST["tituloProduto"]);
                $idCategoriaProduto = filtrar_entrada($_POST["idCategoria"]);
                $descricaoProduto = filtrar_entrada($_POST["descricaoProduto"]);
                $precoProduto = filtrar_entrada($_POST["precoProduto"]);

                if (empty($tituloProduto)) {
                    echo "<script>alert('O campo título é obrigatório!'); window.history.back();</script>";
                    exit();
                }

                if ($_FILES['imgProduto']['size'] > 0) {
                    $imgProduto = validacao_upload_imagem("imgProduto", "assets/produtos/");
                } else {
                    $buscaFotoAntiga = "SELECT imgProduto FROM Produtos WHERE idProduto = $idProduto";
                    $resultadoImg = mysqli_query($conn, $buscaFotoAntiga);
                    $imgA = mysqli_fetch_assoc($resultadoImg);
                    $imgProduto = $imgA['imgProduto'];
                }

                $editar = "UPDATE Produtos SET tituloProduto = '$tituloProduto', imgProduto = '$imgProduto', idCategoria = '$idCategoriaProduto', descricaoProduto = '$descricaoProduto', precoProduto = '$precoProduto' WHERE idProduto = $idProduto";

                if (mysqli_query($conn, $editar)) {
                    header("location:produtosAdmin.php");
                    exit();
                } else {
                    echo "<script>alert('Erro ao atualizar no banco de dados.'); window.history.back();</script>";
                    exit();
                }
            }
            break;

        case "excluirProduto":
            if (isset($_GET['idProduto'])) {
                $idProduto = intval($_GET['idProduto']);
                $deletarProduto = "DELETE FROM Produtos WHERE idProduto = $idProduto";

                if (mysqli_query($conn, $deletarProduto)) {
                    header("location: produtosAdmin.php?sucesso=excluido");
                    exit;
                } else {
                    echo "<script>alert('Erro ao tentar excluir o Produto. Certifique-se de que não existem registros vinculados a ele!'); window.history.back();</script>";
                    exit();
                }
            }
            break;

        // Promoções
        case "cadastrarPromocao":
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $imgPromocao = $tituloPromocao = $dataInicio = $dataFim = "";

                if (empty($_POST["tituloPromocao"])) {
                    echo "<script>alert('O campo TÍTULO é obrigatório!'); window.history.back();</script>";
                    exit();
                } else {
                    $tituloPromocao = filtrar_entrada($_POST["tituloPromocao"]);
                }

                $dataInicio = $_POST['dataInicio'];
                $dataFim = $_POST['dataFim'];

                if (strtotime($dataFim) < strtotime($dataInicio)) {
                    echo "<script>alert('A data de término não pode ser menor que a data de início!'); window.history.back();</script>";
                    exit();
                }

                $imgPromocao = validacao_upload_imagem("imgPromocao", "assets/promocoes/");

                include "conexaoBD.php";
                $inserirPromocao = "INSERT INTO Promocoes (tituloPromocao, imgPromocao, dataInicio, dataFim) VALUES ('$tituloPromocao', '$imgPromocao', '$dataInicio', '$dataFim')";

                if (mysqli_query($conn, $inserirPromocao)) {
                    $idPromocao = mysqli_insert_id($conn);

                    if (!empty($_POST['produtos']) && is_array($_POST['produtos'])) {
                        foreach ($_POST['produtos'] as $idProduto) {
                            $idProduto = intval($idProduto);
                            $inserirProdutosPromocao = "INSERT INTO produtosPromocao (idPromocao, idProduto) VALUES ($idPromocao, $idProduto)";
                            mysqli_query($conn, $inserirProdutosPromocao);
                        }
                    }
                    header("location: promocoesAdmin.php?sucesso=cadastrado");
                    exit;
                } else {
                    echo "<script>alert('Erro ao tentar inserir dados no banco de dados!'); window.history.back();</script>";
                    exit();
                }
            } else {
                header("location:promocoesAdmin.php");
                exit();
            }
            break;

        case "editarPromocao":
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include "conexaoBD.php";
                $idPromocao = intval($_POST["idPromocao"]);
                $tituloPromocao = filtrar_entrada($_POST["tituloPromocao"]);
                $dataInicio = $_POST['dataInicio'];
                $dataFim = $_POST['dataFim'];

                if (strtotime($dataFim) < strtotime($dataInicio)) {
                    echo "<script>alert('A data de término não pode ser menor que a data de início!'); window.history.back();</script>";
                    exit();
                }

                if (isset($_FILES['imgPromocao']) && $_FILES['imgPromocao']['size'] > 0) {
                    $imgPromocao = validacao_upload_imagem("imgPromocao", "assets/promocoes/");
                } else {
                    $buscaFotoAntiga = "SELECT imgPromocao FROM Promocoes WHERE idPromocao = $idPromocao";
                    $resultadoImg = mysqli_query($conn, $buscaFotoAntiga);
                    $imgA = mysqli_fetch_assoc($resultadoImg);
                    $imgPromocao = $imgA['imgPromocao'];
                }

                $editarPromo = "UPDATE Promocoes SET tituloPromocao = '$tituloPromocao', imgPromocao = '$imgPromocao', dataInicio = '$dataInicio', dataFim = '$dataFim' WHERE idPromocao = $idPromocao";

                if (mysqli_query($conn, $editarPromo)) {
                    $deletarVinculosAntigos = "DELETE FROM produtosPromocao WHERE idPromocao = $idPromocao";
                    mysqli_query($conn, $deletarVinculosAntigos);

                    if (!empty($_POST['produtos']) && is_array($_POST['produtos'])) {
                        foreach ($_POST['produtos'] as $idProduto) {
                            $idProduto = intval($idProduto);
                            $inserirProdutosPromocao = "INSERT INTO produtosPromocao (idPromocao, idProduto) VALUES ($idPromocao, $idProduto)";
                            mysqli_query($conn, $inserirProdutosPromocao);
                        }
                    }

                    header("location: promocoesAdmin.php?sucesso=atualizado");
                    exit;
                } else {
                    echo "<script>alert('Erro ao editar a promoção.'); window.history.back();</script>";
                    exit();
                }
            }
            break;

        case "excluirPromocao":
            if (isset($_GET['idPromocao'])) {
                $idPromocao = intval($_GET['idPromocao']);
                $deletarPromocao = "DELETE FROM Promocoes WHERE idPromocao = $idPromocao";

                if (mysqli_query($conn, $deletarPromocao)) {
                    header("location: promocoesAdmin.php?sucesso=excluido");
                    exit;
                } else {
                    echo "<script>alert('Erro ao tentar excluir a Promoção. Certifique-se de que não existem registros vinculados a ela!'); window.history.back();</script>";
                    exit();
                }
            }
            break;

        default:
            echo "<script>alert('Ação inválida ou não encontrada.'); window.history.back();</script>";
            exit();
    }

} else {
    header("location: adminPainel.php");
    exit;
}
?>