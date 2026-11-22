<?php
    // O mesmo formulário atende ao cadastro e à edição no modal.
    $form_mode = $form_mode ?? 'create';
    $is_editing = $form_mode === 'update';
    $form_service = $form_service ?? [];
    $form_id = $form_id ?? 'services_form';
?>

<?php if (!$is_editing) { ?>
<div class="modal fade" id="<?php echo $form_id; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Serviços</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
<?php } ?>

                <form action="<?php echo root_url; ?>pg_services/services_action.php"
                    method="POST" class="was-validated">

                    <input type="hidden" name="service_mode" value="<?php echo $form_mode; ?>">

                    <?php if ($is_editing) { ?>
                        <input type="hidden" name="service_id"
                            value="<?php echo (int) $form_service['service_id']; ?>">
                    <?php } ?>

                    <div style="margin: 8px; margin-bottom: 24px">

                        <div class="form-floating mt-3 mb-3">
                            <input type="text" class="form-control"
                                id="<?php echo $form_id; ?>_title"
                                name="service_title"
                                placeholder="Título"
                                maxlength="100"
                                required
                                value="<?php echo htmlspecialchars($form_service['service_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                            <label for="<?php echo $form_id; ?>_title">Título</label>
                        </div>

                        <div class="form-floating mt-3 mb-3">
                            <textarea class="form-control"
                                id="<?php echo $form_id; ?>_description"
                                name="service_description"
                                placeholder="Descrição"
                                maxlength="500"
                                style="height: 100px"><?php echo htmlspecialchars($form_service['service_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

                            <label for="<?php echo $form_id; ?>_description">Descrição</label>
                        </div>

                    </div>

                    <div style="display: flex; justify-content: center; margin: 8px">
                        <button type="submit" class="btn btn-success">
                            <?php echo $is_editing ? 'Salvar alterações' : 'Registrar'; ?>
                        </button>
                    </div>

                </form>

<?php if (!$is_editing) { ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>