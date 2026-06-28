<?php
    include "conexaoBD.php"; 
    session_start(); 

    $emailCliente = mysqli_real_escape_string($conn, $_POST['emailCliente']); 
    $senhaCliente = mysqli_real_escape_string($conn, $_POST['senhaCliente']);

    $buscarLogin = "SELECT * FROM Clientes WHERE emailCliente = '{$emailCliente}' AND senhaCliente = md5('{$senhaCliente}') ";
    $efetuarLogin = mysqli_query($conn, $buscarLogin);

    if ($registro = mysqli_fetch_assoc($efetuarLogin)){

        $_SESSION['idCliente']    = $registro['idCliente'];
        $_SESSION['emailCliente'] = $registro['emailCliente'];
        $_SESSION['logado']       = true;

        $nomeCompleto = $registro['nomeCliente'];
        $partesNome = explode(" ", $nomeCompleto);
        $_SESSION['nomeCliente'] = $partesNome[0]; 

        $_SESSION['fotoCliente']  = $registro['fotoCliente'];

        // --- REDIRECIONAMENTO INTELIGENTE ---
        if (isset($_SESSION['url_retorno'])) {
            $url = $_SESSION['url_retorno'];
            unset($_SESSION['url_retorno']); 
            header("Location: " . $url);
        } else {
            header("Location: index.php");
        }
        exit();
    }
    else{
        header("Location: loginCliente.php?erroLogin=dadosInvalidos");
        exit();
    }
?>
