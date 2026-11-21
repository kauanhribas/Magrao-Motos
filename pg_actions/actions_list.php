<?php
require_once root_path . "config/conectionDB.php";

$select_actions = "SELECT * FROM actions ORDER BY action_datetime DESC";
$result_actions = mysqli_query($conn, $select_actions);
?>

<div style="padding: 8px">
<section class="section1" style="border-radius: 10px">
    <div class="s1-titulo">
        <h1>MOVIMENTAÇÕES</h1>
    </div>

    <div class="container-fluid" style="padding: 8px">
        <div class="d-flex flex-column quadros">

            <?php
            // Percorre todas as movimentações encontradas
            while ($action = mysqli_fetch_assoc($result_actions)) {

                // Define o nome do tipo de movimentação
                $action_type_name = ($action["action_type"] == "in") ? "Entrada" : "Saída";

                // Formata a data
                $action_date = date(
                    'd/m/Y H:i',
                    strtotime($action["action_datetime"])
                );

                // Define o identificador do modal
                $modal_id = "action" . $action["action_id"];
            ?>

                <div class="row"
                    data-bs-toggle="modal"
                    data-bs-target="#<?php echo $modal_id; ?>">

                    <h1>
                        <?php echo htmlspecialchars($action["action_title"]); ?>
                    </h1>

                    <?php if (!empty($action["action_description"])) { ?>
                        <p> <?php echo htmlspecialchars($action["action_description"]); ?> </p>
                    <?php } ?>
                </div>

                <!-- Modal da edição -->
                <?php require root_path . "pg_actions/actions_edit.php"?>

            <?php
            }
            ?>

        </div>
    </div>

    <div class="container-fluid fixed-bottom" style="padding-bottom: calc(10vh + 16px)">
        <div class="d-flex" style="background-color: gray; border-radius: 7px; margin-left: 30px; margin-right: 30px; justify-content: center">
            
            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#actions_form" style="color: white">
                Registrar movimentação
            </button>

        </div>
    </div>

    <?php
        $form_mode = 'create';
        $form_action = [];
        $form_id = 'actions_form';
        include root_path . 'pg_actions/actions_form.php';
    ?>
</section>
</div>
