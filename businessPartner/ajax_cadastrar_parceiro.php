<?php
require '/var/www/html/novoEmpreendimento/classes/BusinessPartner.php';

try {
    $array = array(
        'nomeCompleto' => mb_strtoupper(trim(filter_input(INPUT_POST, 'nomeCompleto', FILTER_SANITIZE_STRING))),
        'nomeFantasia' => mb_strtoupper(trim(filter_input(INPUT_POST, 'nomeFantasia', FILTER_SANITIZE_STRING))),
        'email' => mb_strtoupper(trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING))),
        'senha' => trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING)),
        'confSenha' => trim(filter_input(INPUT_POST, 'confSenha', FILTER_SANITIZE_STRING)),
        'sexo' => mb_strtoupper(trim(filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_STRING))),
        'cpfcnpj' => mb_strtoupper(trim(filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING))),
        'cep' => mb_strtoupper(trim(filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_STRING))),
        'telefone' => mb_strtoupper(trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING))),
        'rua' => mb_strtoupper(trim(filter_input(INPUT_POST, 'rua', FILTER_SANITIZE_STRING))),
        'numero' => mb_strtoupper(trim(filter_input(INPUT_POST, 'numEndereco', FILTER_SANITIZE_STRING))),
        'bairro' => mb_strtoupper(trim(filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_STRING))),
        'cidade' => mb_strtoupper(trim(filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_STRING))),
        'uf' => mb_strtoupper(trim(filter_input(INPUT_POST, 'uf', FILTER_SANITIZE_STRING))));
    
    $parceiroNegocio = new BusinessPartner();
    $retorno = $parceiroNegocio->cadastrarParceiro($array);
    
    if($retorno === false){
        throw new Exception($parceiroNegocio->getMensagem());
    }
    
    $mensagem = array(
        'status' => 'SUCESSO',
        'mensagem' => utf8_encode($parceiroNegocio->getMensagem())
    );
    $retornoMensagem = json_encode($mensagem);
    echo $retornoMensagem;
    
} catch (Exception $ex) {
    $mensagem = array(
        'status' => 'ERRO',
        'mensagem' => $ex->getMessage()
    );
    $retornoMensagem = json_encode($mensagem);
    echo $retornoMensagem;
}


