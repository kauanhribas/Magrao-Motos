<?php
require_once root_path . 'config/conectionDB.php';

/*
|--------------------------------------------------------------------------
| Funções auxiliares
|--------------------------------------------------------------------------
*/

function reports_empty_summary()
{
    return [
        'in' => 0.0,
        'out' => 0.0,
        'balance' => 0.0
    ];
}

function reports_add_value(&$summary, $action_type, $action_value)
{
    $value = (float) $action_value;

    if ($action_type === 'in') {
        $summary['in'] += $value;
        $summary['balance'] += $value;
    } else {
        $summary['out'] += $value;
        $summary['balance'] -= $value;
    }
}

function reports_money($value)
{
    return 'R$ ' . number_format((float) $value, 2, ',', '.');
}

function reports_valid_date($date)
{
    $date_object = DateTime::createFromFormat('Y-m-d', $date);

    return $date_object
        && $date_object->format('Y-m-d') === $date;
}

function reports_render_metric($label, $summary)
{
?>
    <div class="report-metric">
        <div class="report-metric-main">
            <span><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></span>

            <strong>
                <?php echo reports_money($summary['balance']); ?>
            </strong>
        </div>

        <div class="report-metric-details">
            Entradas: <?php echo reports_money($summary['in']); ?>
            ·
            Saídas: <?php echo reports_money($summary['out']); ?>
        </div>
    </div>
<?php
}

/*
|--------------------------------------------------------------------------
| Filtros recebidos pela URL
|--------------------------------------------------------------------------
*/

$available_metrics = [
    'total',
    'average',
    'pending',
    'closed',
    'dinheiro',
    'pix',
    'cartao',
    'outra'
];

$selected_metrics = $_GET['metrics'] ?? [];

if (!is_array($selected_metrics)) {
    $selected_metrics = [];
}

$selected_metrics = array_intersect(
    $selected_metrics,
    $available_metrics
);

// Sem checkbox enviada: mostra todas as informações.
$show_all_metrics = empty($selected_metrics);

$show_metric = function ($metric) use ($selected_metrics, $show_all_metrics) {
    return $show_all_metrics || in_array($metric, $selected_metrics, true);
};

$date_start = $_GET['date_start'] ?? '';
$date_end = $_GET['date_end'] ?? '';

$days = filter_input(
    INPUT_GET,
    'days',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$filter_message = 'Exibindo todas as movimentações.';

/*
|--------------------------------------------------------------------------
| Consulta de movimentações conforme o período escolhido
|--------------------------------------------------------------------------
*/

$base_sql = "
    SELECT
        a.action_type,
        a.action_method,
        a.action_value,
        a.services_service_id,
        s.service_status
    FROM actions AS a
    LEFT JOIN services AS s
        ON s.service_id = a.services_service_id
";

$result_actions = false;

// Últimos dias têm prioridade sobre as datas.
if ($days !== false && $days !== null) {
    $initial_date = new DateTime('today');
    $initial_date->modify('-' . ($days - 1) . ' days');

    $initial_datetime = $initial_date->format('Y-m-d 00:00:00');

    $statement = mysqli_prepare(
        $conn,
        $base_sql . "
            WHERE a.action_datetime >= ?
            ORDER BY a.action_datetime DESC
        "
    );

    if ($statement) {
        mysqli_stmt_bind_param($statement, 's', $initial_datetime);
        mysqli_stmt_execute($statement);

        $result_actions = mysqli_stmt_get_result($statement);

        $filter_message = 'Exibindo os últimos ' . $days . ' dia(s).';
    }

// Data inicial e final.
} elseif (
    $date_start !== ''
    && $date_end !== ''
    && reports_valid_date($date_start)
    && reports_valid_date($date_end)
) {
    $start_datetime = $date_start . ' 00:00:00';
    $end_datetime = $date_end . ' 23:59:59';

    $statement = mysqli_prepare(
        $conn,
        $base_sql . "
            WHERE a.action_datetime BETWEEN ? AND ?
            ORDER BY a.action_datetime DESC
        "
    );

    if ($statement) {
        mysqli_stmt_bind_param(
            $statement,
            'ss',
            $start_datetime,
            $end_datetime
        );

        mysqli_stmt_execute($statement);

        $result_actions = mysqli_stmt_get_result($statement);

        $filter_message = 'Período: '
            . date('d/m/Y', strtotime($date_start))
            . ' até '
            . date('d/m/Y', strtotime($date_end))
            . '.';
    }

// Apenas data inicial.
} elseif ($date_start !== '' && reports_valid_date($date_start)) {
    $start_datetime = $date_start . ' 00:00:00';

    $statement = mysqli_prepare(
        $conn,
        $base_sql . "
            WHERE a.action_datetime >= ?
            ORDER BY a.action_datetime DESC
        "
    );

    if ($statement) {
        mysqli_stmt_bind_param($statement, 's', $start_datetime);
        mysqli_stmt_execute($statement);

        $result_actions = mysqli_stmt_get_result($statement);

        $filter_message = 'A partir de '
            . date('d/m/Y', strtotime($date_start))
            . '.';
    }

// Apenas data final.
} elseif ($date_end !== '' && reports_valid_date($date_end)) {
    $end_datetime = $date_end . ' 23:59:59';

    $statement = mysqli_prepare(
        $conn,
        $base_sql . "
            WHERE a.action_datetime <= ?
            ORDER BY a.action_datetime DESC
        "
    );

    if ($statement) {
        mysqli_stmt_bind_param($statement, 's', $end_datetime);
        mysqli_stmt_execute($statement);

        $result_actions = mysqli_stmt_get_result($statement);

        $filter_message = 'Até '
            . date('d/m/Y', strtotime($date_end))
            . '.';
    }

// Sem filtro de data.
} else {
    $result_actions = mysqli_query(
        $conn,
        $base_sql . ' ORDER BY a.action_datetime DESC'
    );
}

/*
|--------------------------------------------------------------------------
| Estruturas dos dados do relatório
|--------------------------------------------------------------------------
*/

$payment_methods = [
    'dinheiro' => 'Dinheiro',
    'pix' => 'Pix',
    'cartao' => 'Cartão',
    'outra' => 'Outra'
];

// Totais gerais: incluem todas as movimentações.
$all_total = reports_empty_summary();
$all_payments = [];

// Totais de movimentações atreladas a um serviço.
$services_total = reports_empty_summary();
$services_pending = reports_empty_summary();
$services_closed = reports_empty_summary();
$services_payments = [];

// Totais de movimentações sem serviço vinculado.
$no_service_total = reports_empty_summary();
$no_service_payments = [];

// Saldo individual de cada serviço, usado na média.
$service_balances = [];

foreach ($payment_methods as $method => $label) {
    $all_payments[$method] = reports_empty_summary();
    $services_payments[$method] = reports_empty_summary();
    $no_service_payments[$method] = reports_empty_summary();
}

/*
|--------------------------------------------------------------------------
| Processamento das movimentações
|--------------------------------------------------------------------------
*/

if ($result_actions) {
    while ($action = mysqli_fetch_assoc($result_actions)) {
        $action_type = $action['action_type'];
        $action_value = $action['action_value'];
        $action_method = $action['action_method'];
        $service_id = $action['services_service_id'];

        // Soma nos totais gerais.
        reports_add_value(
            $all_total,
            $action_type,
            $action_value
        );

        if (isset($all_payments[$action_method])) {
            reports_add_value(
                $all_payments[$action_method],
                $action_type,
                $action_value
            );
        }

        // Movimentações vinculadas a um serviço.
        if ($service_id !== null) {
            reports_add_value(
                $services_total,
                $action_type,
                $action_value
            );

            $service_status = $action['service_status'] === 'Encerrado'
                ? 'Encerrado'
                : 'Pendente';

            if ($service_status === 'Encerrado') {
                reports_add_value(
                    $services_closed,
                    $action_type,
                    $action_value
                );
            } else {
                reports_add_value(
                    $services_pending,
                    $action_type,
                    $action_value
                );
            }

            // Cria o resumo do serviço caso ainda não exista.
            if (!isset($service_balances[$service_id])) {
                $service_balances[$service_id] = reports_empty_summary();
            }

            reports_add_value(
                $service_balances[$service_id],
                $action_type,
                $action_value
            );

            if (isset($services_payments[$action_method])) {
                reports_add_value(
                    $services_payments[$action_method],
                    $action_type,
                    $action_value
                );
            }

        // Movimentações sem serviço.
        } else {
            reports_add_value(
                $no_service_total,
                $action_type,
                $action_value
            );

            if (isset($no_service_payments[$action_method])) {
                reports_add_value(
                    $no_service_payments[$action_method],
                    $action_type,
                    $action_value
                );
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Média por serviço
|--------------------------------------------------------------------------
*/

$services_average = reports_empty_summary();
$services_count = count($service_balances);

if ($services_count > 0) {
    foreach ($service_balances as $service_balance) {
        $services_average['in'] += $service_balance['in'];
        $services_average['out'] += $service_balance['out'];
        $services_average['balance'] += $service_balance['balance'];
    }

    $services_average['in'] /= $services_count;
    $services_average['out'] /= $services_count;
    $services_average['balance'] /= $services_count;
}
?>

<style>
    .reports-row {
        background: transparent !important;
        padding: 0 !important;
        margin: 0 0 14px 0 !important;
    }

    .report-card {
        height: 100%;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }

    .report-card-title {
        color: #a90000;
        font-size: 20px;
        font-weight: bold;
        margin: 0 0 12px 0;
        text-align: center;
    }

    .report-metric {
        border-top: 1px solid #e2e2e2;
        padding: 10px 0;
    }

    .report-metric:first-of-type {
        border-top: 0;
        padding-top: 0;
    }

    .report-metric-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 16px;
    }

    .report-metric-main strong {
        color: #111;
        white-space: nowrap;
    }

    .report-metric-details {
        color: #666;
        font-size: 13px;
        margin-top: 3px;
    }

    .report-empty {
        color: #777;
        margin: 0;
        text-align: center;
    }
</style>

<div style="padding: 4px; background-color: white">

    <section class="section1">

        <div class="s2-titulo">
            <h1>
                <span class="linha linha-esquerda"></span>
                <span class="texto-titulo">RELATÓRIOS</span>
                <span class="linha linha-direita"></span>
            </h1>
        </div>

        <div class="container-fluid" style="margin-top: 8px">

            <p class="text-white text-center">
                <?php echo htmlspecialchars($filter_message, ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <div class="d-flex flex-column quadros">

                <!-- Primeira row: todas as movimentações -->
                <div class="row reports-row">
                    <div class="col-12">
                        <div class="report-card">

                            <h2 class="report-card-title">
                                Visão geral das movimentações
                            </h2>

                            <?php
                            $has_general_information =
                                $show_metric('total')
                                || $show_metric('dinheiro')
                                || $show_metric('pix')
                                || $show_metric('cartao')
                                || $show_metric('outra');
                            ?>

                            <?php if ($has_general_information) { ?>

                                <?php if ($show_metric('total')) { ?>
                                    <?php reports_render_metric(
                                        'Saldo total',
                                        $all_total
                                    ); ?>
                                <?php } ?>

                                <?php foreach ($payment_methods as $method => $label) { ?>
                                    <?php if ($show_metric($method)) { ?>
                                        <?php reports_render_metric(
                                            $label,
                                            $all_payments[$method]
                                        ); ?>
                                    <?php } ?>
                                <?php } ?>

                            <?php } else { ?>

                                <p class="report-empty">
                                    Nenhuma informação deste quadro foi selecionada.
                                </p>

                            <?php } ?>

                        </div>
                    </div>
                </div>

                <!-- Segunda row: movimentações vinculadas a serviços -->
                <div class="row reports-row">

                    <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                        <div class="report-card">

                            <h2 class="report-card-title">
                                Movimentações com serviços
                            </h2>

                            <?php
                            $has_service_information =
                                $show_metric('total')
                                || $show_metric('average')
                                || $show_metric('pending')
                                || $show_metric('closed');
                            ?>

                            <?php if ($has_service_information) { ?>

                                <?php if ($show_metric('total')) { ?>
                                    <?php reports_render_metric(
                                        'Saldo total dos serviços',
                                        $services_total
                                    ); ?>
                                <?php } ?>

                                <?php if ($show_metric('average')) { ?>
                                    <?php reports_render_metric(
                                        'Média de saldo por serviço',
                                        $services_average
                                    ); ?>
                                <?php } ?>

                                <?php if ($show_metric('pending')) { ?>
                                    <?php reports_render_metric(
                                        'Saldo dos serviços pendentes',
                                        $services_pending
                                    ); ?>
                                <?php } ?>

                                <?php if ($show_metric('closed')) { ?>
                                    <?php reports_render_metric(
                                        'Saldo dos serviços encerrados',
                                        $services_closed
                                    ); ?>
                                <?php } ?>

                            <?php } else { ?>

                                <p class="report-empty">
                                    Nenhuma informação deste quadro foi selecionada.
                                </p>

                            <?php } ?>

                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="report-card">

                            <h2 class="report-card-title">
                                Formas de pagamento dos serviços
                            </h2>

                            <?php
                            $has_service_payment_information =
                                $show_metric('dinheiro')
                                || $show_metric('pix')
                                || $show_metric('cartao')
                                || $show_metric('outra');
                            ?>

                            <?php if ($has_service_payment_information) { ?>

                                <?php foreach ($payment_methods as $method => $label) { ?>
                                    <?php if ($show_metric($method)) { ?>
                                        <?php reports_render_metric(
                                            $label,
                                            $services_payments[$method]
                                        ); ?>
                                    <?php } ?>
                                <?php } ?>

                            <?php } else { ?>

                                <p class="report-empty">
                                    Nenhuma forma de pagamento foi selecionada.
                                </p>

                            <?php } ?>

                        </div>
                    </div>

                </div>

                <!-- Terceira row: movimentações sem serviço -->
                <div class="row reports-row">
                    <div class="col-12">
                        <div class="report-card">

                            <h2 class="report-card-title">
                                Movimentações sem serviço vinculado
                            </h2>

                            <?php
                            $has_no_service_information =
                                $show_metric('total')
                                || $show_metric('dinheiro')
                                || $show_metric('pix')
                                || $show_metric('cartao')
                                || $show_metric('outra');
                            ?>

                            <?php if ($has_no_service_information) { ?>

                                <?php if ($show_metric('total')) { ?>
                                    <?php reports_render_metric(
                                        'Saldo total sem serviço',
                                        $no_service_total
                                    ); ?>
                                <?php } ?>

                                <?php foreach ($payment_methods as $method => $label) { ?>
                                    <?php if ($show_metric($method)) { ?>
                                        <?php reports_render_metric(
                                            $label,
                                            $no_service_payments[$method]
                                        ); ?>
                                    <?php } ?>
                                <?php } ?>

                            <?php } else { ?>

                                <p class="report-empty">
                                    Nenhuma informação deste quadro foi selecionada.
                                </p>

                            <?php } ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Botão de filtro: fica acima da navbar inferior e à direita -->
        <div class="container-fluid fixed-bottom"
            style="padding-bottom: calc(10vh + 16px)">

            <div class="d-flex justify-content-end">
                <button type="button"
                    class="btn btn-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalFiltro">

                    Filtrar

                </button>
            </div>
        </div>

    </section>
</div>

<!-- Modal: inicialmente oculto; abre somente pelo botão Filtrar -->
<div class="modal fade" id="modalFiltro" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="GET" action="<?php echo root_url; ?>reports.php">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Filtrar relatórios
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <h6>Informações exibidas</h6>

                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            name="metrics[]"
                            value="total"
                            id="filter_total"
                            <?php echo $show_metric('total') ? 'checked' : ''; ?>>

                        <label class="form-check-label" for="filter_total">
                            Saldo total
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            name="metrics[]"
                            value="average"
                            id="filter_average"
                            <?php echo $show_metric('average') ? 'checked' : ''; ?>>

                        <label class="form-check-label" for="filter_average">
                            Média de saldo por serviço
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            name="metrics[]"
                            value="pending"
                            id="filter_pending"
                            <?php echo $show_metric('pending') ? 'checked' : ''; ?>>

                        <label class="form-check-label" for="filter_pending">
                            Saldo dos serviços pendentes
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            name="metrics[]"
                            value="closed"
                            id="filter_closed"
                            <?php echo $show_metric('closed') ? 'checked' : ''; ?>>

                        <label class="form-check-label" for="filter_closed">
                            Saldo dos serviços encerrados
                        </label>
                    </div>

                    <hr>

                    <h6>Formas de pagamento</h6>

                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            name="metrics[]"
                            value="dinheiro"
                            id="filter_dinheiro"
                            <?php echo $show_metric('dinheiro') ? 'checked' : ''; ?>>

                        <label class="form-check-label" for="filter_dinheiro">
                            Dinheiro
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            name="metrics[]"
                            value="pix"
                            id="filter_pix"
                            <?php echo $show_metric('pix') ? 'checked' : ''; ?>>

                        <label class="form-check-label" for="filter_pix">
                            Pix
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            name="metrics[]"
                            value="cartao"
                            id="filter_cartao"
                            <?php echo $show_metric('cartao') ? 'checked' : ''; ?>>

                        <label class="form-check-label" for="filter_cartao">
                            Cartão
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            name="metrics[]"
                            value="outra"
                            id="filter_outra"
                            <?php echo $show_metric('outra') ? 'checked' : ''; ?>>

                        <label class="form-check-label" for="filter_outra">
                            Outra forma
                        </label>
                    </div>

                    <hr>

                    <h6>Período</h6>

                    <div class="mb-3">
                        <label class="form-label" for="date_start">
                            Data inicial
                        </label>

                        <input class="form-control"
                            type="date"
                            name="date_start"
                            id="date_start"
                            value="<?php echo htmlspecialchars($date_start, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="date_end">
                            Data final
                        </label>

                        <input class="form-control"
                            type="date"
                            name="date_end"
                            id="date_end"
                            value="<?php echo htmlspecialchars($date_end, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div>
                        <label class="form-label" for="days">
                            Ou últimos dias
                        </label>

                        <input class="form-control"
                            type="number"
                            name="days"
                            id="days"
                            min="1"
                            placeholder="Ex.: 7"
                            value="<?php echo ($days !== false && $days !== null) ? (int) $days : ''; ?>">

                        <div class="form-text">
                            Se preenchido, “Últimos dias” tem prioridade sobre as datas.
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <a href="<?php echo root_url; ?>reports.php"
                        class="btn btn-secondary">

                        Limpar filtros

                    </a>

                    <button type="submit" class="btn btn-primary">
                        Aplicar filtros
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>