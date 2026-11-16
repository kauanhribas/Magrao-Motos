<div class="modal fade" id="actions_form" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Registrar movimentação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                    <form action="pg_actions/actions_action.php" method="POST" class="was-validated">

                        <!-- Título -->
                        <div class="form-floating mt-3 mb-3">
                            <input type="text" class="form-control" id="action_title"
                                name="action_title" placeholder="Título" maxlength="100" required>
                            <label for="action_title">Título</label>
                        </div>

                        <!-- Descrição -->
                        <div class="form-floating mt-3 mb-3">
                            <textarea class="form-control" id="action_description"
                                name="action_description" placeholder="Descrição"
                                maxlength="500" style="height: 100px"></textarea>
                            <label for="action_description">Descrição</label>
                        </div>

                        <!-- Tipo -->
                        <div class="form-floating mt-3 mb-3">
                            <select class="form-select" id="action_type" name="action_type" required>
                                <option value="" selected disabled>Selecione</option>
                                <option value="in">Entrada</option>
                                <option value="out">Saída</option>
                            </select>
                            <label for="action_type">Tipo de movimentação</label>
                        </div>

                        <!-- Método -->
                        <div class="form-floating mt-3 mb-3">
                            <select class="form-select" id="action_method" name="action_method" required>
                                <option value="" selected disabled>Selecione</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="pix">Pix</option>
                                <option value="cartao">Cartão</option>
                                <option value="outra">Outra</option>
                            </select>
                            <label for="action_method">Método de pagamento</label>
                        </div>

                        <!-- Valor -->
                        <div class="form-floating mt-3 mb-3">
                            <input type="number" class="form-control" id="action_value"
                                name="action_value" placeholder="Valor"
                                min="0.01" step="0.01" required>
                            <label for="action_value">Valor (R$)</label>
                        </div>

                        <button type="submit" class="btn btn-success">Registrar</button>
                    </form>
            </div>

        </div>
    </div>
</div>