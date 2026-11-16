<!-- Inclui o header.php -->
<?php include __DIR__ . "/../head.php" ?>

    <?php
    
        //Verifica o método de requisição do servidor
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //Define o bloco de variáveis para armazenar as informações recebidas do formulário
            $action_title = $action_description = $action_type = $action_method = $action_value = $action_datetime = "";

            //Variável booleana para controle de erros de preenchimento
            $filling_error = false;

            //Validação do campo titulo
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["action_title"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>TÍTULO</strong> é obrigatório!</div>";
                $filling_error = true;
            }

            else{
                //Armazena o valor na variável
                $action_title = entry_filter($_POST["action_title"]);
            }

            //Validação do campo descrição
            if(!empty($_POST["action_description"])) {
                $action_description = entry_filter($_POST["action_description"]);
            };

            //Validação do campo tipo
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["action_type"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>TIPO DE MOVIMENTAÇÃO</strong> é obrigatório!</div>";
                $filling_error = true;
            }

            else{
                //Armazena o valor na variável
                $action_type = $_POST["action_type"];
            }

            //Validação do campo metodo
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["action_method"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>MÉTODO DE PAGAMENTO</strong> é obrigatório!</div>";
                $filling_error = true;
            }

            else{
                //Armazena o valor na variável
                $action_method = $_POST["action_method"];
            }

            //Validação do campo valor
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["action_value"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>VALOR</strong> é obrigatório!</div>";
                $filling_error = true;
            }

            else{
                //Armazena o valor na variável
                $action_value = entry_filter($_POST["action_value"]);
            }

            date_default_timezone_set('America/Sao_Paulo');

            $action_datetime = date('Y-m-d H:i:s');

            //Se NÃO houver erro de preenchimento e NÃO houver erro no upload da foto
            if(!$filling_error){

                //Criar uma variável para armazenar a QUERY que realiza a inserção de dados da Movimentação na tabela actions
                $insert_action = "INSERT INTO actions (action_title, action_description, action_type, action_method, action_value, action_datetime)
                                    VALUES ('$action_title', '$action_description', '$action_type', '$action_method', '$action_value', '$action_datetime')";

                //Inclui o arquivo de conexão com o Banco de Dados
                include __DIR__ . "/../config/conectionDB.php";

                //Se conseguir executar a QUERY para inserção, exibe alerta de sucesso e a tabela com os dados informados
                //A funçao mysqli_query executa operações no Banco de Dados
                if(mysqli_query($conn, $insert_action)){

                    echo "<div class='container'>";
                        echo "<div class='alert alert-success text-center'><strong>MOVIMENTAÇÃO</strong> registrada com sucesso!</div>";
                        
                        echo "
                            <div class='container mt-3'>
                                <table class='table'>
                                    <tr>
                                        <th>TÍTULO</th>
                                        <td>$action_title</td>
                                    </tr>
                        ";

                        if(!empty($_POST["action_description"])) {
                            echo "
                                    <tr>
                                        <th>DESCRIÇÃO</th>
                                        <td>$action_description</td>
                                    </tr>
                            ";
                        };
                        
                        echo "
                                    <tr>
                                        <th>TIPO</th>
                                        <td>$action_type</td>
                                    </tr>
                                    <tr>
                                        <th>METODO</th>
                                        <td>$action_method</td>
                                    </tr>
                                    <tr>
                                        <th>VALOR</th>
                                        <td>$action_value</td>
                                    </tr>
                                    <tr>
                                        <th>DATA E HORÁRIO</th>
                                        <td>" . date('d/m/Y H:i:s', strtotime($action_datetime)) . "</td>
                                    </tr>
                                </table>
                            </div>
                        ";
                    echo "</div>";
                }
                else{
                    echo "<div class='alert alert-danger text-center'>
                        Erro ao tentar inserir dados da <strong>MOVIMENTAÇÃO</strong> no banco de dados $database!
                        <br><strong>Erro:</strong> " . mysqli_error($conn) . "
                    </div>";
                }
            }

        }
        else{
            //Usa a função "header()" para redirecionar o usuário para o actions.php
            header("location:actions.php");
        }

        //Função para filtrar entrada de dados
        function entry_filter($data){
            $data = stripslashes($data); //Remove barras invertidas
            $data = htmlspecialchars($data); //Converte caracteres especiais em entidades HTML

            //Retorna o dado após filtrado
            return($data);
        }
    
    ?>

<!-- Inclui o footer.php -->
<?php include __DIR__ . "/../footer.php" ?>