<?php
include_once '/var/www/html/novoEmpreendimento/classes/Product.php';
if(!isset($_SESSION)){
    session_start();
}

try {
    $dados = array(
        'nomeProduto' => isset($_POST['nome_produto']) ? mb_strtoupper(filter_input(INPUT_POST, 'nome_produto', FILTER_SANITIZE_STRING)) : '',
        'quantidadeProduto' => isset($_POST['quantidade_produto']) ? filter_input(INPUT_POST, 'quantidade_produto', FILTER_SANITIZE_NUMBER_INT) : '', 
        'descricaoProduto' => isset($_POST['descricao_produto']) ? mb_strtoupper(filter_input(INPUT_POST, 'descricao_produto', FILTER_SANITIZE_STRING)) : '', 
        'corProduto' => isset($_POST['cor_produto']) ? mb_strtoupper(filter_input(INPUT_POST, 'cor_produto', FILTER_SANITIZE_STRING)) : '',
        'tipo' => isset($_POST['tipo']) ? mb_strtoupper(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING)) : '',
        'grupo' => isset($_POST['grupo']) ? mb_strtoupper(filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_STRING)) : '', 
        'valor' => isset($_POST['valor']) ? filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_STRING) : '', 
        'promocao' => isset($_POST['promocao']) ? mb_strtoupper(filter_input(INPUT_POST, 'promocao', FILTER_SANITIZE_STRING)) : '', 
        'porcentagemPromocao' => isset($_POST['porcentagem_desconto']) ? mb_strtoupper(filter_input(INPUT_POST, 'porcentagem_desconto', FILTER_SANITIZE_NUMBER_FLOAT)) : ''
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
