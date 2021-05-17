<html>
    <head>
        <title>Ipeças - Faça seu pedido</title>
        <!--JS-->
        <script src="../js/sistema.js" type="text/javascript"></script>
    </head>
    <body>
        <?php
        try {
            if (!(include 'navbar.php') || 
                !(include 'carousel-index.php') || 
                !(include 'cards-index.php') || 
                !(include 'sistema.php')) {
                throw new Exception('Erro no include de páginas do Index');
            }
        } catch (Exception $ex) {
            header('location: error404.php');
            trigger_error($ex->getMessage());
        }
        ?>
    </body>
</html>



