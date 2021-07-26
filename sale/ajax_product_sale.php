<?php
$idProduto = isset($_POST['idProduto']) ? FILTER_INPUT(INPUT_POST, 'idProduto', FILTER_SANITIZE_STRING) : '';
$quantidade = isset($_POST['quantidade']) ? FILTER_INPUT(INPUT_POST, 'quantidade', FILTER_SANITIZE_STRING) : '';

include __DIR__.'/../classes/Sale.php';

try {
    if(empty($idProduto)){
        throw new Exception('Id do produto inexistente. Por favor refaça o procedimento');
    }
    if(empty($quantidade) || !is_numeric($quantidade) || $quantidade < 1){
        throw new Exception('Quantidade fornecida inválida. Por favor refaça o procedimento');
    }

    $classeSale = new Sale;
    if(!$retornoSale = $classeSale->gerarCompraProduto($idProduto, $quantidade)){
        $erro = $classeSale->erro;
        throw new Exception($classeSale->mensagem);        
    }
    
    $return = array(
        'status' => 0,
        'mensagem' => utf8_encode('Compra Realizada com sucesso. Acompanhe o status da entrega na sua plataforma'),
        'idInserido' => $retornoSale
    );
    echo json_encode($return);
} catch (Exception $ex) {
    $return = array(
        'status' => 1,
        'erro' => isset($erro) ? $erro : '',
        'mensagem' => $ex->getMessage()
    );
    echo json_encode($return);
}


