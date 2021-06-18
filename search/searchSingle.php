<?php
$produto = isset($_GET) ? filter_input(INPUT_GET, 'product', FILTER_SANITIZE_STRING) : '';
$business = isset($_GET) ? filter_input(INPUT_GET, 'business', FILTER_SANITIZE_STRING) : '';
include __DIR__ . '/../sistema.php';
include __DIR__.'/../classes/SingleProduct.php';
include __DIR__.'/../classes/SingleBusiness.php';
?>

<html>

<head>
    <meta charset="utf-8">
    <title>Ipeças - Faça seu pedido</title>
    <!-- JS -->
    <script src="/novoEmpreendimento/js/sistema.js" type="text/javascript"></script>

    <!-- CSS -->
    <link href="/novoEmpreendimento/css/product.css" rel="stylesheet">
</head>

    <body class="cor-body">
        <?php
        if (!(include __DIR__ . '/../navbar.php')) {
            echo 'Erro ao carregar o Navbar';
        }

        if(isset($produto) && !empty($produto)){
            $classeSingleProdutc = new SingleProduct;
            $retornoProduto = $classeSingleProdutc->gerarEstrutura($produto);
            echo $retornoProduto;
        }

        if(isset($business) && !empty($business)){
            $classeSingleBusiness = new SingleBusiness;
            $retornoParceiro = $classeSingleBusiness->gerarEstrutura($produto);
            echo $retornoParceiro;
        }
        
        ?>
    </body>
</html>