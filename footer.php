        <footer> 
            <?php
                $pagina = basename($_SERVER['PHP_SELF']);
            ?>

            <section class="container-fluid fixed-bottom nav-section">
                <div class="row h-100">

                    <div class="col-4">
                        <div>
                            <?php
                                if ($pagina == "services.php") {
                                    echo "
                                        <a href='" . root_url . "actions.php' class='btn'>
                                            AÇÕES
                                        </a>
                                    ";
                                }

                                else {
                                    echo "
                                        <a href='" . root_url . "services.php' class='btn'>
                                            SERVIÇOS
                                        </a>
                                    ";
                                }
                            ?>
                        </div>
                    </div>

                    <div class="col-4">
                        <div>
                            <?php
                                if ($pagina != "index.php") {
                                    echo "
                                        <a href='" . root_url . "index.php' class='btn'>
                                            HOME
                                        </a>
                                    ";
                                }

                                else {
                                    echo "
                                        <a href='" . root_url . "actions.php' class='btn'>
                                            AÇÕES
                                        </a>
                                    ";
                                }
                            ?>
                        </div>
                    </div>

                    <div class="col-4">
                        <div>
                            <?php
                                if ($pagina == "reports.php") {
                                    echo "
                                        <a href='" . root_url . "actions.php' class='btn'>
                                            AÇÕES
                                        </a>
                                    ";
                                }

                                else {
                                    echo "
                                        <a href='" . root_url . "reports.php' class='btn'>
                                            RELATÓRIOS
                                        </a>
                                    ";
                                }
                            ?>
                        </div>
                    </div>

                </div>
            </section>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>