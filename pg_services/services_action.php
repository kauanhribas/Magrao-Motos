
<?php
ob_start();
require_once dirname(__DIR__) . '/head.php';
?>

<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<div class='container mt-3'>
            <div class='alert alert-warning'>Requisição inválida.</div>
          </div>";

    require_once root_path . 'footer.php';
    exit;
}

$service_mode = $_POST['service_mode'] ?? 'create';
$is_update = $service_mode === 'update';
$errors = [];

$service_title = trim($_POST['service_title'] ?? '');
$service_description = trim($_POST['service_description'] ?? '');
$service_status = $_POST['service_status'] ?? 'Pendente';

$service_id = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);


// INÍCIO: tratamento da exclusão
if ($service_mode === 'delete') {

    if (!$service_id || $service_id <= 0) {
        echo "<div class='container mt-3'>
                <div class='alert alert-danger'>
                    Serviço inválido para exclusão.
                </div>
              </div>";

        require_once root_path . 'footer.php';
        exit;
    }

    require_once root_path . 'config/conectionDB.php';

    $statement = mysqli_prepare(
        $conn,
        'DELETE FROM services WHERE service_id = ?'
    );

    if ($statement) {

        mysqli_stmt_bind_param(
            $statement,
            'i',
            $service_id
        );

        if (mysqli_stmt_execute($statement)) {
            header('Location: ' . root_url . 'services.php');
            exit;
        }
    }

    echo "<div class='container mt-3'>
            <div class='alert alert-danger'>
                Erro ao excluir o serviço.
            </div>
          </div>";

    require_once root_path . 'footer.php';
    exit;
}
// FIM: tratamento da exclusão


// Validação do título
if ($service_title === '') {
    $errors[] = 'O campo TÍTULO é obrigatório.';
}

// Validação do status
if (!in_array($service_status, ['Pendente', 'Encerrado'], true)) {
    $errors[] = 'Status inválido.';
}

// Validação do ID para edição
if ($is_update && (!$service_id || $service_id <= 0)) {
    $errors[] = 'Serviço inválido para edição.';
}


if ($errors) {

    echo "<div class='container mt-3'>
            <div class='alert alert-warning'>";

    foreach ($errors as $error) {
        echo '<div>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
    }

    echo "</div></div>";

} else {

    require_once root_path . 'config/conectionDB.php';

    // INÍCIO: edição
    if ($is_update) {

        $statement = mysqli_prepare(
            $conn,
            'UPDATE services
             SET service_title = ?,
                 service_description = ?,
                 service_status = ?
             WHERE service_id = ?'
        );

        if ($statement) {

            mysqli_stmt_bind_param(
                $statement,
                'sssi',
                $service_title,
                $service_description,
                $service_status,
                $service_id
            );
        }

    } else {

        // INÍCIO: cadastro
        date_default_timezone_set('America/Sao_Paulo');

        $service_date = date('Y-m-d');

        $statement = mysqli_prepare(
            $conn,
            'INSERT INTO services
            (service_title, service_description, service_date, service_status)
            VALUES (?, ?, ?, ?)'
        );

        if ($statement) {

            mysqli_stmt_bind_param(
                $statement,
                'ssss',
                $service_title,
                $service_description,
                $service_date,
                $service_status
            );
        }

        // FIM: cadastro
    }

    // Executa a consulta
    if ($statement && mysqli_stmt_execute($statement)) {

        header('Location: ' . root_url . 'services.php');
        exit;

    } else {

        echo "<div class='container mt-3'>
                <div class='alert alert-danger text-center'>
                    Erro ao salvar o serviço.
                </div>
              </div>";
    }
}

?>

<?php require_once root_path . 'footer.php'; ?>