<?php
$idProduto = isset($_POST['idProduto']) ? FILTER_INPUT(INPUT_POST, 'idProduto', FILTER_SANITIZE_STRING) : '';

include __DIR__.'/../classes/Sale.php';
include __DIR__.'/../classes/Login.php';

try {
    if(empty($idProduto)){
        throw new Exception('Id do produto inexistente. Por favor refaça o procedimento');
        
    }
    $classeSession = new Login;
    $retornoSession = $classeSession->validarExistenciaSession();
    if($classeSession->getMensagem() == 'NAO EXISTE'){
        throw new Exception('Necessário fazer Login para realizar a compra');
    }
    
    $return = array(
        'erro' => 0,
        'mensagem' => utf8_encode('Tudo certo')
    );
    echo json_encode($return);
} catch (Exception $ex) {
    $return = array(
        'erro' => 1,
        'mensagem' => $ex->getMessage()
    );
    echo json_encode($return);
}


