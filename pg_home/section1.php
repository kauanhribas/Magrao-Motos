
<?php
require_once root_path . "config/conectionDB.php";

$select_services = "SELECT * FROM services
                    ORDER BY
                    CASE
                        WHEN service_status = 'Pendente' THEN 0
                        ELSE 1
                    END,
                    service_date DESC";

$result_services = mysqli_query($conn, $select_services);
?>

<div style="padding-left: 4px; padding-right: 4px; background-color: white">
    <section class="section1">

        <div class="s2-titulo">
            <h1>
                <span class="linha linha-esquerda"></span>
                <span class="texto-titulo">INFORMAÇÕES</span>
                <span class="linha linha-direita"></span>
            </h1>
        </div>

        <div class="container-fluid" style="margin-top: 8px">
            <div class="d-flex flex-column quadros">

                <?php if ($result_services && mysqli_num_rows($result_services) > 0) { ?>

                    <?php while ($service = mysqli_fetch_assoc($result_services)) {

                        $service_id = (int) $service['service_id'];

                        $service_date = date(
                            'd/m/Y',
                            strtotime($service['service_date'])
                        );

                        $service_status = $service['service_status'] ?? 'Pendente';

                        $status_class = $service_status === 'Encerrado'
                            ? 'bg-success'
                            : 'bg-warning text-dark';

                        $modal_id = 'home_service_' . $service_id;
                    ?>

                        <div class="row"
                            data-bs-toggle="modal"
                            data-bs-target="#<?php echo $modal_id; ?>">

                            <h1>
                                <?php echo htmlspecialchars($service['service_title'], ENT_QUOTES, 'UTF-8'); ?>
                            </h1>

                            <div style="margin: 0 8px 8px">
                                <span class="badge <?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($service_status, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>

                        </div>

                        <!-- Modal do serviço -->
                        <div class="modal fade"
                            id="<?php echo $modal_id; ?>"
                            tabindex="-1">

                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <?php echo htmlspecialchars($service['service_title'], ENT_QUOTES, 'UTF-8'); ?>
                                        </h5>

                                        <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <p>
                                            <strong>Descrição:</strong>
                                            <?php echo htmlspecialchars($service['service_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                        </p>

                                        <p>
                                            <strong>Data:</strong>
                                            <?php echo $service_date; ?>
                                        </p>

                                        <p>
                                            <strong>Status:</strong>

                                            <span class="badge <?php echo $status_class; ?>">
                                                <?php echo htmlspecialchars($service_status, ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </p>

                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <div class="alert alert-info">
                        Nenhum serviço cadastrado.
                    </div>

                <?php } ?>

            </div>
        </div>

    </section>
</div>
