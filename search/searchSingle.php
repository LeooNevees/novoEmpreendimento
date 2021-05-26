<?php
$produto = isset($_GET) ? filter_input(INPUT_GET, 'product', FILTER_SANITIZE_STRING) : '';
include __DIR__ . '/../sistema.php';
include __DIR__.'/../classes/Sale.php';
?>

<html>

<head>
    <meta charset="utf-8">
    <title>Ipeças - Faça seu pedido</title>

    <!-- CSS -->
    <link href="/novoEmpreendimento/css/product.css" rel="stylesheet">
</head>

    <body class="cor-body">
        <?php
        if (!(include __DIR__ . '/../navbar.php')) {
            echo 'Erro ao carregar o Navbar';
        }

        $classeSale = new Sale;
        $retorno = $classeSale->gerarEstrutura($produto);
        echo $retorno;
        ?>
    </body>
</html>