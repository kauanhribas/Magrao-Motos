<?php
    ob_start();
    require_once dirname(__DIR__) . '/head.php';
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "<div class='container mt-3'><div class='alert alert-warning'>Requisição inválida.</div></div>";
        require_once root_path . 'footer.php';
        exit;
    }

    $action_mode = $_POST['action_mode'] ?? 'create';
    $is_update = $action_mode === 'update';
    $errors = [];

    $action_title = trim($_POST['action_title'] ?? '');
    $action_description = trim($_POST['action_description'] ?? '');
    $action_type = $_POST['action_type'] ?? '';
    $action_method = $_POST['action_method'] ?? '';
    $action_value = $_POST['action_value'] ?? '';
    // Recebe o serviço selecionado.
    // "none" significa que esta movimentação não será vinculada a um serviço.
    $services_service_id = ($_POST['services_service_id'] ?? 'none') === 'none'
        ? null
        : filter_input(INPUT_POST, 'services_service_id', FILTER_VALIDATE_INT);
    $action_id = filter_input(INPUT_POST, 'action_id', FILTER_VALIDATE_INT);

    // INÍCIO: tratamento da exclusão
    if ($action_mode === 'delete') {

        // Confere se a movimentação recebeu um ID válido.
        if (!$action_id || $action_id <= 0) {
            echo "<div class='container mt-3'>
                    <div class='alert alert-danger'>
                        Movimentação inválida para exclusão.
                    </div>
                </div>";

            require_once root_path . 'footer.php';
            exit;
        }

        // Abre a conexão com o banco.
        require_once root_path . 'config/conectionDB.php';

        // Prepara a exclusão somente da movimentação cujo ID foi enviado.
        $statement = mysqli_prepare(
            $conn,
            'DELETE FROM actions WHERE action_id = ?'
        );

        // O \"i\" significa que action_id é um número inteiro.
        if ($statement) {
            mysqli_stmt_bind_param($statement, 'i', $action_id);
        }

        // Se excluiu corretamente, volta à lista sem exibir uma tela de sucesso.
        if ($statement && mysqli_stmt_execute($statement)) {
            header('Location: ' . root_url . 'actions.php');
            exit;
        }

        // Caso o banco não consiga excluir, exibe o erro e encerra o processamento.
        echo "<div class='container mt-3'>
                <div class='alert alert-danger'>
                    Erro ao excluir a movimentação.
                </div>
            </div>";

        require_once root_path . 'footer.php';
        exit;
    }
    // FIM: tratamento da exclusão

    if ($action_title === '') {
        $errors[] = 'O campo TÍTULO é obrigatório.';
    }

    if (!in_array($action_type, ['in', 'out'], true)) {
        $errors[] = 'Selecione um TIPO DE MOVIMENTAÇÃO válido.';
    }

    if (!in_array($action_method, ['dinheiro', 'pix', 'cartao', 'outra'], true)) {
        $errors[] = 'Selecione um MÉTODO DE PAGAMENTO válido.';
    }

    if (!is_numeric($action_value) || (float) $action_value <= 0) {
        $errors[] = 'Informe um VALOR maior que zero.';
    }

    // Se não for "Nenhum serviço", o valor enviado precisa ser um ID válido.
    if (
        ($_POST['services_service_id'] ?? 'none') !== 'none'
        && (!$services_service_id || $services_service_id <= 0)
    ) {
        $errors[] = 'Selecione um serviço válido.';
    }

    if ($is_update && (!$action_id || $action_id <= 0)) {
        $errors[] = 'Movimentação inválida para edição.';
    }

    if ($errors) {
        echo "<div class='container mt-3'><div class='alert alert-warning'>";
        foreach ($errors as $error) {
            echo '<div>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
        }
        echo "</div></div>";
    } 
    else {
        require_once root_path . 'config/conectionDB.php';

        if ($is_update) {

            // Atualiza uma movimentação existente.
            $statement = mysqli_prepare(
                $conn,
                'UPDATE actions
                SET action_title = ?,
                    action_description = ?,
                    action_type = ?,
                    action_method = ?,
                    action_value = ?,
                    services_service_id = ?
                WHERE action_id = ?'
            );

            if ($statement) {
                mysqli_stmt_bind_param(
                    $statement,
                    'ssssdii',
                    $action_title,
                    $action_description,
                    $action_type,
                    $action_method,
                    $action_value,
                    $services_service_id,
                    $action_id
                );
            }

        } else {

            // Define data e horário somente ao criar uma movimentação.
            date_default_timezone_set('America/Sao_Paulo');
            $action_datetime = date('Y-m-d H:i:s');

            // Cria uma movimentação nova.
            $statement = mysqli_prepare(
                $conn,
                'INSERT INTO actions (
                    action_title,
                    action_description,
                    action_type,
                    action_method,
                    action_value,
                    action_datetime,
                    services_service_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?)'
            );

            if ($statement) {
                mysqli_stmt_bind_param(
                    $statement,
                    'ssssdsi',
                    $action_title,
                    $action_description,
                    $action_type,
                    $action_method,
                    $action_value,
                    $action_datetime,
                    $services_service_id
                );
            }
        }

        // Executa a consulta, seja ela INSERT ou UPDATE.
        if ($statement && mysqli_stmt_execute($statement)) {
            header('Location: ' . root_url . 'actions.php');
            exit;
        }

        // Exibe erro caso não seja possível salvar.
        echo "<div class='container mt-3'>
                <div class='alert alert-danger text-center'>
                    Erro ao salvar a movimentação.
                </div>
            </div>";

        } // Fecha o else iniciado em: if ($errors) { ... } else {
?>

<?php require_once root_path . 'footer.php'; ?>
