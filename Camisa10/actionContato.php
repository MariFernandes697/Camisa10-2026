<?php include "header.php"; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $email = $mensagem = "";
    $erro = false;

    // Validações básicas
    if (empty($_POST["nomeContato"]) || empty($_POST["emailContato"]) || empty($_POST["mensagemContato"])) {
        echo "<script>alert('Todos os campos são obrigatórios!); window.history.back();</script>";
        $erro = true;
    } else {
        $nomeContato = filtrar_entrada($_POST["nomeContato"]);
        $emailContato = filtrar_entrada($_POST["emailContato"]);
        $mensagemContato = filtrar_entrada($_POST["mensagemContato"]);
    }

    if (!$erro) {
        include "conexaoBD.php";

        // Prepara a inserção
        $inserirContato = "INSERT INTO formContato (nomeContato, emailContato, mensagemContato) VALUES ('$nomeContato', '$emailContato', '$mensagemContato')";

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $nomeContato = mysqli_real_escape_string($conn, $nomeContato);
            $emailContato = mysqli_real_escape_string($conn, $emailContato);
            $mensagemContato = mysqli_real_escape_string($conn, $mensagemContato);

            $inserirContato = "INSERT INTO formContato (nomeContato, emailContato, mensagemContato) VALUES ('$nomeContato', '$emailContato', '$mensagemContato')";

            if (mysqli_query($conn, $inserirContato)) {
                echo "
                <script>
                    alert('Sucesso! Sua mensagem foi enviada. Obrigado pelo contato!');
                    window.location.href = 'index.php#contato';
                </script>
                ";
            }
        } catch (mysqli_sql_exception $e) {

            $erroBanco = addslashes($e->getMessage());
            echo "
                <script>
                    alert('Erro no banco: $erroBanco');
                    window.history.back(); 
                </script>
                ";
        }
    }

} else {
    header("location:index.php");
}

function filtrar_entrada($dado)
{
    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado);
    return $dado;
}
?>

<?php include "footer.php"; ?>
