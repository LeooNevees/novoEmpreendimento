<?php
include_once '/var/www/html/novoEmpreendimento/classes/Product.php';
if(!isset($_SESSION)){
    session_start();
}

try {
    $dados = array(
        'nomeProduto' => isset($_POST['nomeProduto']) ? mb_strtoupper(filter_input(INPUT_POST, 'nomeProduto', FILTER_SANITIZE_STRING)) : '',
        'quantidadeProduto' => isset($_POST['quantidadeProduto']) ? filter_input(INPUT_POST, 'quantidadeProduto', FILTER_SANITIZE_NUMBER_INT) : '', 
        'descricaoProduto' => isset($_POST['descricaoProduto']) ? mb_strtoupper(filter_input(INPUT_POST, 'descricaoProduto', FILTER_SANITIZE_STRING)) : '', 
        'corProduto' => isset($_POST['corProduto']) ? mb_strtoupper(filter_input(INPUT_POST, 'corProduto', FILTER_SANITIZE_STRING)) : '',
        'tipo' => isset($_POST['tipo']) ? mb_strtoupper(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING)) : '',
        'grupo' => isset($_POST['grupo']) ? mb_strtoupper(filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_STRING)) : '', 
        'valor' => isset($_POST['valor']) ? filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_NUMBER_FLOAT) : '', 
        'promocao' => isset($_POST['promocao']) ? mb_strtoupper(filter_input(INPUT_POST, 'promocao', FILTER_SANITIZE_STRING)) : '', 
        'porcentagemPromocao' => isset($_POST['porcentagemPromocao']) ? mb_strtoupper(filter_input(INPUT_POST, 'porcentagemPromocao', FILTER_SANITIZE_NUMBER_FLOAT)) : '',
        'idBusiness' => isset($_POST['idBusiness']) ? filter_input(INPUT_POST, 'idBusiness', FILTER_SANITIZE_STRING) : ''
    );

    $classeProduct = new Product;
    $retornoCadastro = $classeProduct->cadastrarProdutos($dados);
    if($retornoCadastro === false){
        throw new Exception($classeProduct->mensagem);
    }
    return array(
        'erro' => false,
        'mensagem' => 'Produto cadastro com sucesso',
        'business' => $_SESSION['id']
    );
} catch (Exception $ex) {
    return array(
        'erro' => true,
        'mensagem' => $ex->getMessage()
    );
}
