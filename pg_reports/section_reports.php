<section class="section1">
    <div class="s1-titulo">
        <h1>RELATÓRIOS</h1>
    </div>

    <div class="container-fluid">
        <div class="d-flex flex-column quadros">

            <div class="row">
                <h1>Saldo: xxxxxx</h1>
                <p>Total de entradas: xxxxxx
                <br>Total de saidas: xxxxxx</p>
            </div>

            <div class="row">
                <h1>Entradas: xxxxxx</h1>
                <p>Serviços: xxxxxx
                <br>Peças: xxxxxx</p>
            </div>

            <div class="row">
                <h1>Custos: xxxxxx</h1>
                <p>Aluguel: xxxxxx
                <br>Peças: xxxxxx</p>
            </div>
        </div>
    </div>

    <!-- Botão filtro -->
    <div class="container-fluid fixed-bottom" style="padding-bottom: calc(10vh + 16px)">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-secondary"
                data-bs-toggle="modal"
                data-bs-target="#modalFiltro">
            Filtrar
        </button>
    </div>
</div>
</section>

<!-- Modal do filtrar -->
<div class="modal fade" id="modalFiltro" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Filtrar movimentações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <h6>Tipo de movimentação</h6>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="entrada">
                    <label class="form-check-label" for="entrada">
                        Entradas
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="saida">
                    <label class="form-check-label" for="saida">
                        Saídas
                    </label>
                </div>

                <hr>

                <h6>Ordenação</h6>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ordem" id="recentes" checked>
                    <label class="form-check-label" for="recentes">
                        Mais recentes
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ordem" id="antigas">
                    <label class="form-check-label" for="antigas">
                        Mais antigas
                    </label>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Fechar
                </button>

                <button type="button" class="btn btn-primary">
                    Aplicar filtros
                </button>
            </div>

        </div>
    </div>
</div>