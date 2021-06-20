<?php
include __DIR__ . '/../sistema.php';
include_once '/var/www/html/novoEmpreendimento/classes/CardProducts.php';
include_once '/var/www/html/novoEmpreendimento/classes/CardSalesman.php';

$buscar = isset($_GET['search']) ? mb_strtoupper(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING)) : '';
?>

<html>

    <head>
        <title>Ipeças - Faça seu pedido</title>
        <script src="/novoEmpreendimento/js/searchProductBusiness.js" type="text/javascript"></script>
    </head>

    <body>
        <?php
        try {
            if (
                !(include __DIR__ . '/../navbar.php')
                || !(include __DIR__ . '/../carousel-index.php')
            ) {
                throw new Exception('Erro ao carregar o NavBar');
            }

            if(empty($buscar)){
                throw new Exception('Nenhum parâmetro fornecido. Por favor refaça o procedimento');
            }
            $divRetorno = [];

            //BUSCA PRODUTOS
            $classeProducts = new CardProducts('Produtos Encontrados');
            $dadosProdutos = array(
                'nome' => new \MongoDB\BSON\Regex(utf8_encode($buscar))
            );
            $retornoProdutos = $classeProducts->gerarEstrutura($dadosProdutos, 6);
            if($classeProducts->getEncontrados() > 0){
                $divRetorno[] = $classeProducts->getMensagem();
            }

            // BUSCA PARCEIRO DE NEGÓCIO
            $dadosBusiness = array(
                'nome_completo' => new \MongoDB\BSON\Regex(utf8_encode($buscar))
            );
            $classeBusinessPartner = new CardSalesman('Vendedores Encontrados');
            $retornoBusiness = $classeBusinessPartner->gerarEstrutura($dadosBusiness, 6);
            if($classeBusinessPartner->getEncontrados() > 0){
                $divRetorno[] = $classeBusinessPartner->getMensagem();
            }

            if(!count($divRetorno)){
                $divRetorno[] = array('mensagem' => 'Não encontrado Produtos ou Vendedores com esse nome');
            }

            echo implode('',$divRetorno);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
        ?>
    </body>

</html>