<?php

    include "conexaoBD.php"; //Inclui o arquivo de conexão com o BD para consultar usuários
    session_start(); //Função para iniciar uma sessão

    $emailCliente = mysqli_real_escape_string($conn, $_POST['emailCliente']); //Filtra a entrada de dados
    $senhaCliente = mysqli_real_escape_string($conn, $_POST['senhaCliente']);

    //Query para buscar dados de Login
    $buscarLogin = "SELECT *
                    FROM Clientes
                    WHERE emailCliente = '{$emailCliente}'
                    AND senhaCliente = md5('{$senhaCliente}') ";

    //Executa a Query
    $efetuarLogin = mysqli_query($conn, $buscarLogin);

    //Verifica se encontrou um usuário
    if ($registro = mysqli_fetch_assoc($efetuarLogin)){

        //Cria variáveis de sessão
        $_SESSION['idCliente']    = $registro['idCliente'];
        $_SESSION['emailCliente'] = $registro['emailCliente'];
        $_SESSION['logado']       = true;

        // Pegar apenas o primeiro nome para não quebrar o layout da barra
        $nomeCompleto = $registro['nomeCliente'];
        $partesNome = explode(" ", $nomeCompleto);
        $_SESSION['nomeCliente'] = $partesNome[0]; 

        // Salva o caminho da foto (ajuste o nome da coluna se for diferente no seu banco)
        $_SESSION['fotoCliente']  = $registro['fotoCliente'];

        //Redireciona o usuário para a página inicial
        header("Location: index.php");
        exit();
    }
    else{
        header("Location: loginCliente.php?erroLogin=dadosInvalidos");
        exit();
    }

?>