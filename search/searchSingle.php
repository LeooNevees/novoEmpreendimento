<?php
$produto = isset($_GET['product']) ? filter_input(INPUT_GET, 'product', FILTER_SANITIZE_STRING) : '';
$parceiroNegocio = isset($_GET['business']) ? filter_input(INPUT_GET, 'business', FILTER_SANITIZE_STRING) : '';

include_once '/var/www/html/novoEmpreendimento/sistema.php';
include_once '/var/www/html/novoEmpreendimento/classes/SingleProduct.php';
include_once '/var/www/html/novoEmpreendimento/classes/SingleBusinessPartner.php';
?>

<html>

<head>
    <meta charset="utf-8">
    <title>Ipeças - Faça seu pedido</title>
    <!-- JS -->
    <script src="/novoEmpreendimento/js/searchSingle.js" type="text/javascript"></script>
    <script src="/novoEmpreendimento/js/sistema.js" type="text/javascript"></script>

    <!-- CSS -->
    <link href="/novoEmpreendimento/css/product.css" rel="stylesheet">
</head>

    <body class="cor-body">
        <?php
            try {
                if (!(include __DIR__ . '/../navbar.php')) {
                    echo 'Erro ao carregar o Navbar';
                }
        
                switch (true) {
                    case !empty($produto):
                        $classeSingleProdutc = new SingleProduct;
                        $retorno = $classeSingleProdutc->gerarEstrutura($produto);
                        if($retorno === false){
                            throw new Exception($classeSingleProdutc->mensagem);
                        }
                        echo $retorno;
                        break;
        
                    case !empty($parceiroNegocio):
                        $classeSingleBusiness = new SingleBusinessPartner;
                        $retorno = $classeSingleBusiness->gerarEstrutura($parceiroNegocio);
                        if($retorno === false){
                            throw new Exception($classeSingleBusiness->mensagem);
                        }
                        echo $retorno;
                        break;
                    
                    default:
                        echo 'Parâmetro não reconhecido. Por favor refaça o procedimento';
                        break;
                } 
            } catch (Exception $ex) {
                trigger_error($ex->getMessage());
                echo 'Erro: '.$ex->getMessage().'. Por favor refaça o procedimento';
                return false;
            }       
        ?>
    </body>
</html>