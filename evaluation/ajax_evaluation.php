<?php
include_once '/var/www/html/novoEmpreendimento/classes/Evaluation.php';

if(!isset($_SESSION)){
    session_start();
}

$tipo = isset($_POST['tipo']) ? mb_strtoupper(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING)) : '';
$idProduto = isset($_POST['id_produto']) ? filter_input(INPUT_POST, 'id_produto', FILTER_SANITIZE_STRING) : '';
$idParceiro = isset($_POST['idParceiro']) ? filter_input(INPUT_POST, 'idParceiro', FILTER_SANITIZE_STRING) : '';
$titulo = isset($_POST['titulo']) ? mb_strtoupper(filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING)) : '';
$mensagem = isset($_POST['descricao_avaliacao']) ? mb_strtoupper(filter_input(INPUT_POST, 'descricao_avaliacao', FILTER_SANITIZE_STRING)) : '';
$estrelas = isset($_POST['estrelas']) ? filter_input(INPUT_POST, 'estrelas', FILTER_SANITIZE_NUMBER_INT) : '';

$classeEvaluation = new Evaluation;

try {
    switch (true) {
        case $tipo == 'PRODUTO':
            $retorno = $classeEvaluation->adicionarAvaliacaoProduto($idProduto, $titulo, $mensagem, $estrelas);
            if($retorno === false){
                throw new Exception($classeEvaluation->mensagem);
            }

            $retornoStatus = json_encode(array(
                'status' => 'SUCESSO',
                'mensagem' => 'Avaliação cadastrada com sucesso'
            ));
            echo $retornoStatus;
            break;
        default:
            throw new Exception('Método indefinido. Por favor refaça o procedimento');
            break;
    }
} catch (Exception $ex) {
    $retorno = array(
        'status' => 'ERRO',
        'mensagem' => $ex->getMessage()
    );
    echo $retorno;
}