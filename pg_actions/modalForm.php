<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Registrar movimentação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="" method="POST">

                    <label class="form-label">Descrição</label>
                    <input type="text" name="descricao" class="form-control">

                    <label class="form-label mt-3">Valor</label>
                    <input type="number" name="valor" step="0.01" class="form-control">

                    <label class="form-label mt-3">Tipo</label>
                    <select name="tipo" class="form-select">
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>

                    <button type="submit" class="btn btn-primary mt-3">
                        Cadastrar
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>