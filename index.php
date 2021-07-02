<html>
    <head>
        <title>Ipeças - Faça seu pedido</title>
        <!--JS-->
        <script src="/novoEmpreendimento/js/sistema.js" type="text/javascript"></script>
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
            trigger_error($ex->getMessage());
            header('location: error404.php');
        }
        ?>
    </body>
</html>



