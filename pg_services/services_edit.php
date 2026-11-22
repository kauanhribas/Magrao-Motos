<div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <?php echo htmlspecialchars($service["service_title"], ENT_QUOTES, 'UTF-8'); ?>
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div style="margin: 8px; margin-bottom: 24px">
                    <p>
                        <strong>Descrição:</strong>
                        <?php echo htmlspecialchars($service["service_description"] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>
                        <strong>Data:</strong>
                        <?php echo $service_date; ?>
                    </p>
                </div>

                <div style="display: flex; justify-content: center; margin: 8px">

                    <button class="btn btn-success"
                        style="margin-right: 12px"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#edit_service_<?php echo (int) $service['service_id']; ?>"
                        aria-expanded="false"
                        aria-controls="edit_service_<?php echo (int) $service['service_id']; ?>">

                        Editar

                    </button>

                    <form action="<?php echo root_url; ?>pg_services/services_action.php"
                        method="POST"
                        onsubmit="return confirm('Deseja realmente excluir este serviço?');">

                        <input type="hidden" name="service_mode" value="delete">

                        <input type="hidden" name="service_id"
                            value="<?php echo (int) $service['service_id']; ?>">

                        <button class="btn btn-danger" type="submit">
                            Excluir
                        </button>

                    </form>

                </div>

                <div class="collapse mt-3"
                    id="edit_service_<?php echo (int) $service['service_id']; ?>">

                    <hr style="margin-top: 24px; margin-bottom: 24px">

                    <div style="margin: 8px; display: flex; justify-content: center">
                        <h3>EDITAR SERVIÇO</h3>
                    </div>

                    <?php
                        $form_mode = 'update';
                        $form_service = $service;
                        $form_id = 'edit_service_' . $service['service_id'];

                        include root_path . 'pg_services/services_form.php';
                    ?>

                </div>

            </div>
        </div>
    </div>
</div>