<?php
include_once '/var/www/html/novoEmpreendimento/classes/Product.php';
if(!isset($_SESSION)){
    session_start();
}

try {
    $dados = array(
        'idProduto' => isset($_POST['idProduto']) ? filter_input(INPUT_POST, 'idProduto', FILTER_SANITIZE_STRING) : ''
    );

    $classeProduct = new Product;
    $retornoCadastro = $classeProduct->cadastrarProdutos($dados);
    if($retornoCadastro === false){
        throw new Exception($classeProduct->mensagem);
    }
    $arrayRetorno = array(
        'status' => 'SUCESSO',
        'mensagem' => 'Produto cadastro com sucesso',
        'business' => $_SESSION['id']
    );
    echo json_encode($arrayRetorno);
} catch (Exception $ex) {
    $arrayRetorno =  array(
        'status' => 'ERRO',
        'mensagem' => $ex->getMessage()
    );
    echo json_encode($arrayRetorno);
}