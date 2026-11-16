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
                                        <a href='actions.php' class='btn'>
                                            Movim.
                                        </a>
                                    ";
                                }

                                else {
                                    echo "
                                        <a href='" . BASE_URL . "#' class='btn'>
                                            Serv.
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
                                        <a href='" . BASE_URL . "index.php' class='btn'>
                                            Home
                                        </a>
                                    ";
                                }

                                else {
                                    echo "
                                        <a href='actions.php' class='btn'>
                                            Movim.
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
                                        <a href='actions.php' class='btn'>
                                            Movim.
                                        </a>
                                    ";
                                }

                                else {
                                    echo "
                                        <a href='" . BASE_URL . "reports.php' class='btn'>
                                            Relat.
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