<?php
require_once root_path . "config/conectionDB.php";

$select_actions = "SELECT * FROM actions ORDER BY action_datetime DESC";
$result_actions = mysqli_query($conn, $select_actions);
?>

<?php
require_once root_path . "config/conectionDB.php";

$select_services = "SELECT * FROM services ORDER BY service_date DESC";
$result_services = mysqli_query($conn, $select_services);
?>

<div style="padding: 4px; background-color: white">
    <section class="section1" style="border-radius: 10px">

        <div class="s2-titulo">
            <h1>
                <span class="linha linha-esquerda"></span>
                <span class="texto-titulo">SERVIÇOS</span>
                <span class="linha linha-direita"></span>
            </h1>
        </div>

        <div class="container-fluid" style="padding: 8px">
            <div class="d-flex flex-column quadros">

                <?php
                // Percorre todos os serviços encontrados
                while ($service = mysqli_fetch_assoc($result_services)) {

                    // Formata a data
                    $service_date = date(
                        'd/m/Y',
                        strtotime($service["service_date"])
                    );

                    // Define o identificador do modal
                    $modal_id = "service" . $service["service_id"];
                ?>

                    <div class="row"
                        style="display: flex; align-items: stretch;"
                        data-bs-toggle="modal"
                        data-bs-target="#<?php echo $modal_id; ?>">

                        <span class="linha2"></span>

                        <div class="conteudo-acao">

                            <h1>
                                <?php echo htmlspecialchars($service["service_title"]); ?>
                            </h1>

                            <?php if (!empty($service["service_description"])) { ?>
                                <p>
                                    <?php echo htmlspecialchars($service["service_description"]); ?>
                                </p>
                            <?php } ?>

                            <small>
                                <?php echo $service_date; ?>
                            </small>

                        </div>
                    </div>

                    <!-- Modal de edição -->
                    <?php require root_path . "pg_services/services_edit.php"; ?>

                <?php
                }
                ?>

            </div>
        </div>

        <div class="container-fluid fixed-bottom"
            style="padding-bottom: calc(10vh + 16px)">

            <div class="d-flex btn-registrar">

                <button type="button"
                    class="btn"
                    data-bs-toggle="modal"
                    data-bs-target="#services_form">

                    Registrar Serviços

                </button>

            </div>
        </div>

        <?php
        $form_mode = 'create';
        $form_service = [];
        $form_id = 'services_form';

        include root_path . 'pg_services/services_form.php';
        ?>

    </section>
</div>