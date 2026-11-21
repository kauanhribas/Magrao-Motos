<?php
    // O mesmo formulário atende ao cadastro e à edição no modal da movimentação.
    $form_mode = $form_mode ?? 'create';
    $is_editing = $form_mode === 'update';
    $form_action = $form_action ?? [];
    $form_id = $form_id ?? 'actions_form';
?>

<?php if (!$is_editing) { ?>
<div class="modal fade" id="<?php echo $form_id; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar movimentação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
<?php } ?>
                <form action="<?php echo root_url; ?>pg_actions/actions_action.php" method="POST" class="was-validated">
                    <input type="hidden" name="action_mode" value="<?php echo $form_mode; ?>">

                    <?php if ($is_editing) { ?>
                        <input type="hidden" name="action_id" value="<?php echo (int) $form_action['action_id']; ?>">
                    <?php } ?>

                    <div style="margin: 8px; margin-bottom: 24px">
                        <div class="form-floating mt-3 mb-3">
                            <input type="text" class="form-control" id="<?php echo $form_id; ?>_title"
                                name="action_title" placeholder="Título" maxlength="100" required
                                value="<?php echo htmlspecialchars($form_action['action_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <label for="<?php echo $form_id; ?>_title">Título</label>
                        </div>

                        <div class="form-floating mt-3 mb-3">
                            <textarea class="form-control" id="<?php echo $form_id; ?>_description"
                                name="action_description" placeholder="Descrição" maxlength="500"
                                style="height: 100px"><?php echo htmlspecialchars($form_action['action_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <label for="<?php echo $form_id; ?>_description">Descrição</label>
                        </div>

                        <div class="form-floating mt-3 mb-3">
                            <select class="form-select" id="<?php echo $form_id; ?>_type" name="action_type" required>
                                <option value="" <?php echo empty($form_action['action_type']) ? 'selected' : ''; ?> disabled>Selecione</option>
                                <option value="in" <?php echo ($form_action['action_type'] ?? '') === 'in' ? 'selected' : ''; ?>>Entrada</option>
                                <option value="out" <?php echo ($form_action['action_type'] ?? '') === 'out' ? 'selected' : ''; ?>>Saída</option>
                            </select>
                            <label for="<?php echo $form_id; ?>_type">Tipo de movimentação</label>
                        </div>

                        <div class="form-floating mt-3 mb-3">
                            <select class="form-select" id="<?php echo $form_id; ?>_method" name="action_method" required>
                                <option value="" <?php echo empty($form_action['action_method']) ? 'selected' : ''; ?> disabled>Selecione</option>
                                <option value="dinheiro" <?php echo ($form_action['action_method'] ?? '') === 'dinheiro' ? 'selected' : ''; ?>>Dinheiro</option>
                                <option value="pix" <?php echo ($form_action['action_method'] ?? '') === 'pix' ? 'selected' : ''; ?>>Pix</option>
                                <option value="cartao" <?php echo ($form_action['action_method'] ?? '') === 'cartao' ? 'selected' : ''; ?>>Cartão</option>
                                <option value="outra" <?php echo ($form_action['action_method'] ?? '') === 'outra' ? 'selected' : ''; ?>>Outra</option>
                            </select>
                            <label for="<?php echo $form_id; ?>_method">Método de pagamento</label>
                        </div>

                        <div class="form-floating mt-3 mb-3">
                            <input type="number" class="form-control" id="<?php echo $form_id; ?>_value"
                                name="action_value" placeholder="Valor" min="0.01" step="0.01" required
                                value="<?php echo htmlspecialchars($form_action['action_value'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <label for="<?php echo $form_id; ?>_value">Valor (R$)</label>
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
