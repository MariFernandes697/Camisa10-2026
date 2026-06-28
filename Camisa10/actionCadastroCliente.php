<!-- Inclui o header.php -->
<?php include "header.php" ?>

    <?php
    
        //Verifica o método de requisição do servidor
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //Define o bloco de variáveis para armazenar as informações recebidas do formulário
            $fotoCliente = $nomeCliente = $cpfCliente = $telefoneCliente = $emailCliente = $senhaCliente = $confirmarSenhaCliente = "";

            //Variável booleana para controle de erros de preenchimento
            $erroPreenchimento = false;

            //Validação do campo nomeCliente
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["nomeCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>NOME</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Filtra e Armazena o valor na variável
                $nomeCliente = filtrar_entrada($_POST["nomeCliente"]);

                //Utiliza a função preg_match() para verificar se há apenas letras no nome
                if(!preg_match('/^[\p{L} ]+$/u', $nomeCliente)){
                    echo "<div class='alert alert-warning text-center'>O campo <strong>NOME</strong>
                    deve conter APENAS LETRAS!</div>";
                    $erroPreenchimento = true;
                }
            }

            //Validação do campo cpfCliente
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["cpfCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>CPF</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Filtra e Armazena o valor na variável
                $cpfCliente = filtrar_entrada($_POST["cpfCliente"]);

                //Utiliza a função preg_match() para verificar se há apenas letras no nome
                if (!preg_match('/^[0-9]+$/', $cpfCliente)) {
                    echo "<div class='alert alert-warning text-center'>O campo <strong>TELEFONE</strong>
                    deve conter APENAS NÚMEROS!</div>";
                    $erroPreenchimento = true;
                }
            }

            //Validação do campo telefoneCliente
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["telefoneCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>TELEFONE</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Filtra e Armazena o valor na variável
                $telefoneCliente = filtrar_entrada($_POST["telefoneCliente"]);

                //Utiliza a função preg_match() para verificar se há apenas letras no nome
                if (!preg_match('/^[0-9]+$/', $telefoneCliente)) {
                    echo "<div class='alert alert-warning text-center'>O campo <strong>TELEFONE</strong>
                    deve conter APENAS NÚMEROS!</div>";
                    $erroPreenchimento = true;
                }
            }

        
            //Validação do campo emailCliente
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["emailCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>EMAIL</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Filtra e Armazena o valor na variável
                $emailCliente = filtrar_entrada($_POST["emailCliente"]);
            }

            //Validação do campo senhaCliente
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["senhaCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>SENHA</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Filtra e Armazena o valor na variável || Aplica a função md5 para criptografar a senha
                $senhaCliente = md5(filtrar_entrada($_POST["senhaCliente"]));
            }

            //Validação do campo confirmarSenhaCliente
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["confirmarSenhaCliente"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>CONFIRMAR SENHA</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Filtra e Armazena o valor na variável || Aplica a função md5 para criptografar a senha
                $confirmarSenhaCliente = md5(filtrar_entrada($_POST["confirmarSenhaCliente"]));

                //Compara se as senhas são diferentes
                if($senhaCliente != $confirmarSenhaCliente){
                    echo "<div class='alert alert-warning text-center'>As <strong>SENHAS</strong> informadas são diferentes!</div>";
                    $erroPreenchimento = true;
                }
            }

            //Início da validação da fotoCliente
            $diretorio    = "assets/clientes/"; //Define para qual diretório as imagens serão movidas
            $fotoCliente  = $diretorio . basename($_FILES['fotoCliente']['name']); //Montar o nome a ser salvo no BD
            $tipoDaImagem = strtolower(pathinfo($fotoCliente, PATHINFO_EXTENSION)); //Pega o tipo do arquivo em letras minúsculas
            $erroUpload   = false; //Variável para controle de erros do upload da foto

            //Verifica se o tamanho do arquivo é diferente de ZERO
            if($_FILES["fotoCliente"]["size"] != 0){

                //Verifica se o tamanho do arquivo é maior do que 5 MegaBytes(MB) - Medida em bytes
                if($_FILES["fotoCliente"]["size"] > 5000000){
                    echo "<div class='alert alert-warning text-center'>O campo <strong>FOTO</strong> deve ter tamanho máximo de 5MB!</div>";
                    $erroUpload = true;
                }

                //Verifica se a foto está nos formatos JPG, JPEG, PNG ou WEBP
                if($tipoDaImagem != "jpg" && $tipoDaImagem != "jpeg" && $tipoDaImagem != "png" && $tipoDaImagem != "webp"){
                    echo "<div class='alert alert-warning text-center'>A <strong>FOTO</strong> deve estar no formatos JPG, JPEG, PNG ou WEBP!</div>";
                    $erroUpload = true;
                }

                //Verifica se a imagem foi movida para o diretório (assets/img), utilizando a função move_uploaded_file()
                if(!move_uploaded_file($_FILES["fotoCliente"]["tmp_name"], $fotoCliente)){
                    echo "<div class='alert alert-warning text-center'>Erro ao tentar mover a <strong>FOTO</strong> para o diretório $diretorio!</div>";
                    $erroUpload = true;
                }

            }
            else{
                echo "<div class='alert alert-warning text-center'>O campo <strong>FOTO</strong> é obrigatório!</div>";
                $erroUpload = true;
            }
            //Se NÃO houver erro de preenchimento e NÃO houver erro no upload da foto
            if(!$erroPreenchimento && !$erroUpload){
                
                //Criar uma variável para armazenar a QUERY que realiza a inserção de dados do Usuário na tabela Clientes
                $inserirCliente = "INSERT INTO Clientes (fotoCliente, nomeCliente, cpfCliente, telefoneCliente, emailCliente, senhaCliente)
                                    VALUES ('$fotoCliente', '$nomeCliente', '$cpfCliente', '$telefoneCliente', '$emailCliente', '$senhaCliente')";

                //Inclui o arquivo de conexão com o Banco de Dados
                include "conexaoBD.php";

                //Se conseguir executar a QUERY para inserção, exibe alerta de sucesso e a tabela com os dados informados
                //A funçao mysqli_query executa operações no Banco de Dados
                if(mysqli_query($conn, $inserirCliente)){

                    echo "<div class='container'>";
                        echo "<div class='alert alert-success text-center'><strong>USUÁRIO</strong> cadastrado com sucesso!</div>";
                        echo "
                            <div class='container mt-3'>
                                <div class='container mt-3 mb-3 text-center'>
                                    <img src='$fotoCliente' style='width:150px' title='Foto de $nomeCliente' class='img-thumbnail'>
                                </div>
                                <table class='table'>
                                    <tr>
                                        <th>NOME</th>
                                        <td>$nomeCliente</td>
                                    </tr>
                                    <tr>
                                        <th>CPF</th>
                                        <td>$cpfCliente</td>
                                    </tr>
                                    <tr>
                                        <th>TELEFONE</th>
                                        <td>$telefoneCliente</td>
                                    </tr>
                                    <tr>
                                        <th>EMAIL</th>
                                        <td>$emailCliente</td>
                                    </tr>
                                    <tr>
                                        <th>SENHA</th>
                                        <td>$senhaCliente</td>
                                    </tr>
                                    <tr>
                                        <th>CONFIRMAÇÃO DE SENHA</th>
                                        <td>$confirmarSenhaCliente</td>
                                    </tr>
                                </table>
                            </div>
                        ";
                    echo "</div>";
                }
                else{
                    echo "<div class='alert alert-danger text-center'>
                    Erro ao tentar inserir dados do<strong>USUÁRIO</strong> no banco de dados $database!</div>";
                }
            }

        }
        else{
            //Usa a função "header()" para redirecionar o usuário para o formCliente.php
            header("location:cadastroCliente.php");
        }

        //Função para filtrar entrada de dados
        function filtrar_entrada($dado){
            $dado = trim($dado); //Remove espaços desnecessários
            $dado = stripslashes($dado); //Remove barras invertidas
            $dado = htmlspecialchars($dado); //Converte caracteres especiais em entidades HTML

            //Retorna o dado após filtrado
            return($dado);
        }
    
    ?>

<!-- Inclui o footer.php -->
<?php include "footer.php" ?>