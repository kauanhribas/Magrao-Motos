<div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <?php echo htmlspecialchars($action["action_title"]); ?>
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div style="margin: 8px; margin-bottom: 24px">
                    <p>
                        <strong>Descrição:</strong>
                        <?php echo htmlspecialchars($action["action_description"]); ?>
                    </p>

                    <p>
                        <strong>Tipo:</strong>
                        <?php echo $action_type_name; ?>
                    </p>

                    <p>
                        <strong>Método de pagamento:</strong>
                        <?php echo htmlspecialchars($action["action_method"]); ?>
                    </p>

                    <p>
                        <strong>Valor:</strong>
                        R$ <?php echo number_format($action["action_value"], 2, ",", "."); ?>
                    </p>

                    <p>
                        <strong>Data e horário:</strong>
                        <?php echo $action_date; ?>
                    </p>
                </div>

                <div style="display: flex; justify-content: center; margin: 8px">
                    <button class="btn btn-success" style="margin-right: 12px" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#edit_action_<?php echo (int) $action['action_id']; ?>"
                        aria-expanded="false"
                        aria-controls="edit_action_<?php echo (int) $action['action_id']; ?>">
                        Editar
                    </button>

                    <form action="<?php echo root_url; ?>pg_actions/actions_action.php"
                        method="POST"
                        onsubmit="return confirm('Deseja realmente excluir esta movimentação?');">

                        <input type="hidden" name="action_mode" value="delete">
                        <input type="hidden" name="action_id"
                            value="<?php echo (int) $action['action_id']; ?>">

                        <button class="btn btn-danger" type="submit">
                            Excluir
                        </button>
                    </form>
                </div>

                <div class="collapse mt-3" id="edit_action_<?php echo (int) $action['action_id']; ?>">
                    <hr style="margin-top: 24px; margin-bottom: 24px">
                    <div style="margin: 8px; display: flex; justify-content: center">
                        
                        <h3>EDITAR MOVIMENTAÇÃO</h3>
                    </div>
                    <?php
                        $form_mode = 'update';
                        $form_action = $action;
                        $form_id = 'edit_action_' . $action['action_id'];
                        include root_path . 'pg_actions/actions_form.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>