<?php
    
        //Verifica o método de requisição do servidor
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //Define o bloco de variáveis para armazenar as informações recebidas do formulário
            $fotoCliente = $nomeCliente = $cpfCliente = $telefoneCliente = $emailCliente = $senhaCliente = $confirmarSenhaCliente = "";

            //Variável booleana para controle de erros de preenchimento
            $erroPreenchimento = false;

            //Validação do campo nomeCliente
            if(empty($_POST["nomeCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>NOME</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                $nomeCliente = filtrar_entrada($_POST["nomeCliente"]);

                if(!preg_match('/^[\p{L} ]+$/u', $nomeCliente)){
                    echo "<div class='alert alert-warning text-center'>O campo <strong>NOME</strong> deve conter APENAS LETRAS!</div>";
                    $erroPreenchimento = true;
                }
            }

            //Validação do campo cpfCliente
            if(empty($_POST["cpfCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>CPF</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                $cpfCliente = filtrar_entrada($_POST["cpfCliente"]);

                if (!preg_match('/^[0-9]+$/', $cpfCliente)) {
                    echo "<div class='alert alert-warning text-center'>O campo <strong>CPF</strong> deve conter APENAS NÚMEROS!</div>";
                    $erroPreenchimento = true;
                }
            }

            //Validação do campo telefoneCliente
            if(empty($_POST["telefoneCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>TELEFONE</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                $telefoneCliente = filtrar_entrada($_POST["telefoneCliente"]);

                if (!preg_match('/^[0-9]+$/', $telefoneCliente)) {
                    echo "<div class='alert alert-warning text-center'>O campo <strong>TELEFONE</strong> deve conter APENAS NÚMEROS!</div>";
                    $erroPreenchimento = true;
                }
            }

            //Validação do campo emailCliente
            if(empty($_POST["emailCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>EMAIL</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                $emailCliente = filtrar_entrada($_POST["emailCliente"]);
            }

            //Validação do campo senhaCliente
            if(empty($_POST["senhaCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>SENHA</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                $senhaCliente = md5(filtrar_entrada($_POST["senhaCliente"]));
            }

            //Validação do campo confirmarSenhaCliente
            if(empty($_POST["confirmarSenhaCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>CONFIRMAR SENHA</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                $confirmarSenhaCliente = md5(filtrar_entrada($_POST["confirmarSenhaCliente"]));

                if($senhaCliente != $confirmarSenhaCliente){
                    echo "<div class='alert alert-warning text-center'>As <strong>SENHAS</strong> informadas são diferentes!</div>";
                    $erroPreenchimento = true;
                }
            }

            //Início da validação da fotoCliente
            $diretorio    = "assets/clientes/"; 
            $fotoCliente  = $diretorio . basename($_FILES['fotoCliente']['name']); 
            $tipoDaImagem = strtolower(pathinfo($fotoCliente, PATHINFO_EXTENSION)); 
            $erroUpload   = false; 

            if($_FILES["fotoCliente"]["size"] != 0){

                if($_FILES["fotoCliente"]["size"] > 5000000){
                    echo "<div class='alert alert-warning text-center'>O campo <strong>FOTO</strong> deve ter tamanho máximo de 5MB!</div>";
                    $erroUpload = true;
                }

                if($tipoDaImagem != "jpg" && $tipoDaImagem != "jpeg" && $tipoDaImagem != "png" && $tipoDaImagem != "webp"){
                    echo "<div class='alert alert-warning text-center'>A <strong>FOTO</strong> deve estar no formatos JPG, JPEG, PNG ou WEBP!</div>";
                    $erroUpload = true;
                }

                if(!move_uploaded_file($_FILES["fotoCliente"]["tmp_name"], $fotoCliente)){
                    echo "<div class='alert alert-warning text-center'>Erro ao tentar mover a <strong>FOTO</strong> para o diretório $diretorio!</div>";
                    $erroUpload = true;
                }

            }
            else{
                echo "<div class='alert alert-warning text-center'>O campo <strong>FOTO</strong> é obrigatório!</div>";
                $erroUpload = true;
            }

            //Se NÃO houver erro
            if(!$erroPreenchimento && !$erroUpload){
                
                // Escape para segurança antes do banco
                include "conexaoBD.php";
                $nomeEscapado = mysqli_real_escape_string($conn, $nomeCliente);
                $emailEscapado = mysqli_real_escape_string($conn, $emailCliente);

                $inserirCliente = "INSERT INTO Clientes (fotoCliente, nomeCliente, cpfCliente, telefoneCliente, emailCliente, senhaCliente)
                                    VALUES ('$fotoCliente', '$nomeEscapado', '$cpfCliente', '$telefoneCliente', '$emailEscapado', '$senhaCliente')";

                if(mysqli_query($conn, $inserirCliente)){
                    
                    // --- EFETUAR AUTO-LOGIN LOGO APÓS O CADASTRO ---
                    if(!isset($_SESSION)) { session_start(); }
                    
                    $_SESSION['idCliente']    = mysqli_insert_id($conn); // Pega o id gerado no banco
                    $_SESSION['emailCliente'] = $emailCliente;
                    $_SESSION['logado']       = true;
                    $_SESSION['fotoCliente']  = $fotoCliente;

                    // Tratamento do primeiro nome
                    $partesNome = explode(" ", $nomeCliente);
                    $_SESSION['nomeCliente'] = $partesNome[0];

                    // --- REDIRECIONAMENTO INTELIGENTE ---
                    if (isset($_SESSION['url_retorno'])) {
                        $url = $_SESSION['url_retorno'];
                        unset($_SESSION['url_retorno']); // Limpa a variável da sessão
                        header("Location: " . $url);
                    } else {
                        header("Location: index.php");
                    }
                    exit();

                }
                else{
                    echo "<div class='alert alert-danger text-center'>
                    Erro ao tentar inserir dados do <strong>USUÁRIO</strong> no banco de dados!</div>";
                }
            }

        }
        else{
            header("location:cadastroCliente.php");
            exit();
        }

        function filtrar_entrada($dado){
            $dado = trim($dado); 
            $dado = stripslashes($dado); 
            $dado = htmlspecialchars($dado); 
            return($dado);
        }
    
    ?>

<?php include "footer.php" ?>
