<?php
include_once '/var/www/html/novoEmpreendimento/sistema.php';
include_once '/var/www/html/novoEmpreendimento/classes/CardProductsUser.php';
include_once '/var/www/html/novoEmpreendimento/classes/Login.php';

$classeLogin = new Login;
$retornoSession = $classeLogin->validarExistenciaSession();
if($classeLogin->getMensagem() != 'EXISTE'){
    header('location:/novoEmpreendimento/login.php');
    return false;
}
$idBusiness = isset($_GET['business']) ? filter_input(INPUT_GET, 'business', FILTER_SANITIZE_STRING) : '';
?>
<html>
    <head>
        <title>Ipeças - Faça seu pedido</title>
        <!--JS-->
        <script src="/novoEmpreendimento/js/myProducts.js" type="text/javascript"></script>
    </head>
    <body class="cor-bodyy">
        <?php
        try {
            if (!(include '/var/www/html/novoEmpreendimento/navbar.php')) {
                throw new Exception('Erro no include de páginas do myProducts');
            }

            $classeProducts = new CardProductsUser('Itens à venda');
            $retornoProd = $classeProducts->gerarEstrutura(array('id_vendedor' => $idBusiness), 6);
            if($retornoProd === false){
                throw new Exception('Erro ao gerar a Estrutura dos produtos');
            }
            echo $classeProducts->getMensagem();

        } catch (Exception $ex) {
            trigger_error($ex->getMessage());
            header('location: /novoEmpreendimento/error404.php');
        }
        ?>
    </body>
</html>



