<?php
include_once '/var/www/html/novoEmpreendimento/classes/Evaluation.php';

if(!isset($_SESSION)){
    session_start();
}

//Produto
$idProduto = isset($_POST['id_produto']) ? filter_input(INPUT_POST, 'id_produto', FILTER_SANITIZE_STRING) : '';
$tituloProduto = isset($_POST['titulo_vendedor']) ? mb_strtoupper(filter_input(INPUT_POST, 'titulo_vendedor', FILTER_SANITIZE_STRING)) : '';
$mensagemProduto = isset($_POST['descricao_avaliacao']) ? mb_strtoupper(filter_input(INPUT_POST, 'descricao_avaliacao', FILTER_SANITIZE_STRING)) : '';
$estrelasProduto = isset($_POST['estrelas_avaliacao']) ? filter_input(INPUT_POST, 'estrelas_avaliacao', FILTER_SANITIZE_NUMBER_INT) : '';

//Vendedor
$idNegociacao = isset($_POST['id_negociacao']) ? filter_input(INPUT_POST, 'id_negociacao', FILTER_SANITIZE_STRING) : '';
$tituloVendedor = isset($_POST['titulo_vendedor']) ? mb_strtoupper(filter_input(INPUT_POST, 'titulo_vendedor', FILTER_SANITIZE_STRING)) : '';
$atendimentoVendedor = isset($_POST['atendimento']) ? filter_input(INPUT_POST, 'atendimento', FILTER_SANITIZE_NUMBER_INT) : '';
$tempoEntregaVendedor = isset($_POST['tempo_entrega']) ? filter_input(INPUT_POST, 'tempo_entrega', FILTER_SANITIZE_NUMBER_INT) : '';
$observacaoVendedor = isset($_POST['observacao_vendedor']) ? mb_strtoupper(filter_input(INPUT_POST, 'observacao_vendedor', FILTER_SANITIZE_STRING)) : '';


$classeEvaluation = new Evaluation;

try {
    $retornoProduto = $classeEvaluation->adicionarAvaliacaoProduto($idProduto, $tituloProduto, $mensagemProduto, $estrelasProduto);
    if($retornoProduto === false){
        throw new Exception($classeEvaluation->mensagem);
    }

    $retornoVendedor = $classeEvaluation->adicionarAvaliacaoVendedor($idNegociacao, $tituloVendedor, $atendimentoVendedor, $tempoEntregaVendedor, $observacaoVendedor);
    if($retornoVendedor === false){
        throw new Exception($classeEvaluation->mensagem);
    }

    $retornoStatus = json_encode(array(
        'status' => 'SUCESSO',
        'mensagem' => 'Avaliação cadastrada com sucesso'
    ));
    echo $retornoStatus;
} catch (Exception $ex) {
    $retorno = array(
        'status' => 'ERRO',
        'mensagem' => $ex->getMessage()
    );
    echo $retorno;
}