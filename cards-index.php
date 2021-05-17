<?php
include '/var/www/html/novoEmpreendimento/classes/Card.php';
include '/var/www/html/novoEmpreendimento/classes/CardProducts.php';
include '/var/www/html/novoEmpreendimento/classes/CardSalesman.php';
include '/var/www/html/novoEmpreendimento/classes/CardBuyer.php';

try {
    $conteudo = [];

    //Top Produtos
    $cardProdutos = new CardProducts('Produtos mais procurados');
    $dadosProdutos = ['status' => 'ATIVO'];
    $retornoProdutos = $cardProdutos->gerarEstrutura($dadosProdutos, 6);
    if($retornoProdutos === false){
        throw new Exception($cardProdutos->getMensagem());
    }
    $conteudo[] = $cardProdutos->getMensagem();
    
    //Top Vendas
    $cardParceiroVenda = new CardSalesman('Vendedores mais Ativos');
    $dadosParceiroVenda = ['situacao' => 'A'];
    $retornoVendedores = $cardParceiroVenda->gerarEstrutura($dadosParceiroVenda, 3);
    if($retornoVendedores === false){
        throw new Exception($cardParceiroVenda->getMensagem());
    }
    $conteudo[] = $cardParceiroVenda->getMensagem();

    //Top Compras
    $cardParceiroCompra = new CardBuyer('Compradores mais Ativos');
    $dadosParceiroCompra = ['situacao' => 'A'];
    $retornoCompradores = $cardParceiroCompra->gerarEstrutura($dadosParceiroCompra, 3);
    if($retornoCompradores === false){
        throw new Exception($cardParceiroCompra->getMensagem());
    }
    $conteudo[] = $cardParceiroCompra->getMensagem();
    
    echo implode("\n", $conteudo);

} catch (Exception $ex) {
    $mensagem = $ex->getMessage();
    echo $mensagem;
}
?>

<script src="/novoEmpreendimento/js/cards-index.js" type="text/javascript"></script>
