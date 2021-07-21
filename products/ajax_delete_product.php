<?php
include_once '/var/www/html/novoEmpreendimento/classes/Product.php';
if(!isset($_SESSION)){
    session_start();
}

try {
    $idProduto = isset($_POST['idProduto']) ? filter_input(INPUT_POST, 'idProduto', FILTER_SANITIZE_STRING) : '';
    $classeProduct = new Product;
    $retornoInativacao = $classeProduct->inativarProduto($idProduto);
    if($retornoInativacao === false){
        throw new Exception($classeProduct->mensagem);
    }
    $arrayRetorno = array(
        'status' => 'SUCESSO',
        'mensagem' => 'Produto inativado com sucesso',
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
