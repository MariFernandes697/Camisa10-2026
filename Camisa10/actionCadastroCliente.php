<?php

//Verifica o método de requisição do servidor
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Define o bloco de variáveis para armazenar as informações recebidas do formulário
    $fotoCliente = $nomeCliente = $cpfCliente = $telefoneCliente = $emailCliente = $senhaCliente = $confirmarSenhaCliente = "";

    //Variável booleana para controle de erros de preenchimento
    $erroPreenchimento = false;

    //Validação do campo nomeCliente
    if (empty($_POST["nomeCliente"])) {
        echo "<script>alert('O campo NOME é obrigatório!'); window.history.back();</script>";
        exit();
    } else {
        $nomeCliente = filtrar_entrada($_POST["nomeCliente"]);

        if (!preg_match('/^[\p{L} ]+$/u', $nomeCliente)) {
            echo "<script>alert('O campo NOME deve conter APENAS LETRAS!'); window.history.back();</script>";
            exit();
        }
    }

    //Validação do campo cpfCliente
    if (empty($_POST["cpfCliente"])) {
        echo "<script>alert('O campo CPF é obrigatório!'); window.history.back();</script>";
        exit();
    } else {
        $cpfCliente = filtrar_entrada($_POST["cpfCliente"]);

        if (!preg_match('/^[0-9]+$/', $cpfCliente)) {
            echo "<script>alert('O campo CPF deve conter APENAS NÚMEROS!'); window.history.back();</script>";
            exit();
        }
    }

    //Validação do campo telefoneCliente
    if (empty($_POST["telefoneCliente"])) {
        echo "<script>alert('O campo TELEFONE é obrigatório!'); window.history.back();</script>";
        exit();
    } else {
        $telefoneCliente = filtrar_entrada($_POST["telefoneCliente"]);

        if (!preg_match('/^[0-9]+$/', $telefoneCliente)) {
            echo "<script>alert('O campo TELEFONE deve conter APENAS NÚMEROS!'); window.history.back();</script>";
            exit();
        }
    }

    //Validação do campo emailCliente
    if (empty($_POST["emailCliente"])) {
        echo "<script>alert('O campo EMAIL é obrigatório!'); window.history.back();</script>";
        exit();
    } else {
        $emailCliente = filtrar_entrada($_POST["emailCliente"]);
    }

    //Validação do campo senhaCliente
    if (empty($_POST["senhaCliente"])) {
        echo "<script>alert('O campo SENHA é obrigatório!'); window.history.back();</script>";
        exit();
    } else {
        $senhaCliente = md5(filtrar_entrada($_POST["senhaCliente"]));
    }

    //Validação do campo confirmarSenhaCliente
    if (empty($_POST["confirmarSenhaCliente"])) {
        echo "<script>alert('O campo CONFIRMAR SENHA é obrigatório!'); window.history.back();</script>";
        exit();
    } else {
        $confirmarSenhaCliente = md5(filtrar_entrada($_POST["confirmarSenhaCliente"]));

        if ($senhaCliente != $confirmarSenhaCliente) {
            echo "<script>alert('As SENHAS informadas são diferentes!'); window.history.back();</script>";
            exit();
        }
    }

    //Início da validação da fotoCliente
    $diretorio = "assets/clientes/";
    $fotoCliente = $diretorio . basename($_FILES['fotoCliente']['name']);
    $tipoDaImagem = strtolower(pathinfo($fotoCliente, PATHINFO_EXTENSION));

    if ($_FILES["fotoCliente"]["size"] != 0) {

        if ($_FILES["fotoCliente"]["size"] > 5000000) {
            echo "<script>alert('O campo FOTO deve ter tamanho máximo de 5MB!'); window.history.back();</script>";
            exit();
        }

        if ($tipoDaImagem != "jpg" && $tipoDaImagem != "jpeg" && $tipoDaImagem != "png" && $tipoDaImagem != "webp") {
            echo "<script>alert('A FOTO deve estar nos formatos JPG, JPEG, PNG ou WEBP!'); window.history.back();</script>";
            exit();
        }

        if (!move_uploaded_file($_FILES["fotoCliente"]["tmp_name"], $fotoCliente)) {
            echo "<script>alert('Erro ao tentar mover a FOTO para o diretório!'); window.history.back();</script>";
            exit();
        }

    } else {
        echo "<script>alert('O campo FOTO é obrigatório!'); window.history.back();</script>";
        exit();
    }
    include "conexaoBD.php";
    $nomeEscapado = mysqli_real_escape_string($conn, $nomeCliente);
    $emailEscapado = mysqli_real_escape_string($conn, $emailCliente);

    $inserirCliente = "INSERT INTO Clientes (fotoCliente, nomeCliente, cpfCliente, telefoneCliente, emailCliente, senhaCliente)
                                VALUES ('$fotoCliente', '$nomeEscapado', '$cpfCliente', '$telefoneCliente', '$emailEscapado', '$senhaCliente')";

    if (mysqli_query($conn, $inserirCliente)) {

        // Após o cadastro o cliente já permanecerá logado no sistema
        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION['idCliente'] = mysqli_insert_id($conn);
        $_SESSION['emailCliente'] = $emailCliente;
        $_SESSION['logado'] = true;
        $_SESSION['fotoCliente'] = $fotoCliente;

        // Tratamento do primeiro nome
        $partesNome = explode(" ", $nomeCliente);
        $_SESSION['nomeCliente'] = $partesNome[0];

        // Redirecionamento do usuário para exatamente qual página que ele estava antes de fazer o cadastro 
        if (isset($_SESSION['url_retorno'])) {
            $url = $_SESSION['url_retorno'];
            unset($_SESSION['url_retorno']);
            header("Location: " . $url);
        } else {
            header("Location: index.php");
        }
        exit();

    } else {
        echo "<script>
                alert('Erro ao tentar realizar o cadastro! Tente Novamente'); 
                window.location.href='cadastroCliente.php';
            </script>";
        exit();
    }

} else {
    header("location:cadastroCliente.php");
    exit();
}

function filtrar_entrada($dado)
{
    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado);
    return ($dado);
}

?>

<?php include "footer.php" ?>
