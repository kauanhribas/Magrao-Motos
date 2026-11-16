<section class="section1">
    <div class="s1-titulo">
        <h1>MOVIMENTAÇÕES</h1>
    </div>

    <div class="container-fluid">
        <div class="d-flex flex-column quadros">

            <div class="row" data-bs-toggle="modal" data-bs-target="#action1">
                <h1>Entrada 1</h1>
            </div>

            <div class="row" data-bs-toggle="modal" data-bs-target="#action1">
                <h1>Saida 1</h1>
            </div>

            <div class="row" data-bs-toggle="modal" data-bs-target="#action1">
                <h1>Entrada 2</h1>
            </div>

            <div class="row" data-bs-toggle="modal" data-bs-target="#action1">
                <h1>Saida 2</h1>
            </div>

            <div class="row" data-bs-toggle="modal" data-bs-target="#action1">
                <h1>Entrada 3</h1>
            </div>

            <div class="row" data-bs-toggle="modal" data-bs-target="#action1">
                <h1>Saida 3</h1>
            </div>

            <div class="row" data-bs-toggle="modal" data-bs-target="#action1">
                <h1>Entrada 4</h1>
            </div>

            <div class="row" data-bs-toggle="modal" data-bs-target="#action1">
                <h1>Saida 4</h1>
            </div>
        </div>
    </div>

    <!-- Modal de movimentações 1 -->
    <div class="modal fade" id="action1" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Movimentação X</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Informações da movimentação.
                </div>

            </div>
        </div>
    </div>

    <div class="container-fluid fixed-bottom" style="padding-bottom: calc(10vh + 16px)">
        <div class="d-flex" style="background-color: gray; border-radius: 7px; margin-left: 30px; margin-right: 30px; justify-content: center">
            
            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#actions_form" style="color: white">
                Registrar movimentação
            </button>

        </div>
    </div>

    <?php include "actions_form.php" ?>

</section>