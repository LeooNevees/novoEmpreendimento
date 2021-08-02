<?php
include_once '/var/www/html/novoEmpreendimento/classes/Dashboard.php';

$tipo = isset($_POST['tipo']) ? filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING) : '';

try {
    $classeDashboard = new Dashboard;
    $retorno = $classeDashboard->gerarDashboard();
    if($retorno === false){
        throw new Exception($classeDashboard->mensagem);
    }    
    
    if(!count($retorno)){
        throw new Exception('Retorno inválido. Por favor refaça procedimento. '.$retorno);
    }

    echo json_encode($retorno);
} catch (Exception $ex) {
    $retorno = array(
        'status' => 'ERRO',
        'mensagem' => $ex->getMessage()
    );
    echo json_encode($retorno);
}