<?php
$produto = isset($_GET) ? filter_input(INPUT_GET, 'product', FILTER_SANITIZE_STRING) : '';
include __DIR__ . '/../sistema.php';
include __DIR__.'/../classes/SingleProduct.php';
?>

<html>

<head>
    <meta charset="utf-8">
    <title>Ipeças - Faça seu pedido</title>
    <!-- JS -->
    <script src="/novoEmpreendimento/js/searchSale.js" type="text/javascript"></script>

    <!-- CSS -->
    <link href="/novoEmpreendimento/css/product.css" rel="stylesheet">
</head>

    <body class="cor-body">
        <?php
        if (!(include __DIR__ . '/../navbar.php')) {
            echo 'Erro ao carregar o Navbar';
        }

        $classeSingleProdutc = new SingleProduct;
        $retorno = $classeSingleProdutc->gerarEstrutura($produto);
        echo $retorno;
        ?>
    </body>
</html>