<?php
include_once '/var/www/html/novoEmpreendimento/classes/repository/ProductsRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/BusinessPartnerRepository.php';

try {
    $buscar = isset($_POST['buscar']) ? mb_strtoupper(filter_input(INPUT_POST, 'buscar', FILTER_SANITIZE_STRING)) : '';
    $dadosProdutos = array(
        'nome' => new \MongoDB\BSON\Regex(utf8_encode($buscar))
    );
    $classeProducts = new ProductsRepository;
    $retornoProdutos = $classeProducts->getProductLimit($dadosProdutos, 3);
    if ($retornoProdutos === false) {
        throw new Exception($classeProducts->mensagem);
    }
    $auxRet = json_decode($retornoProdutos);
    $data = [];
    if(!empty($auxRet) && count($auxRet)){
        foreach ($auxRet as $key => $value) {
            $auxIdProd = (array)$value->_id;
            foreach ($auxIdProd as $valores) {
                $idProduto = $valores;   
            }

            $data[] = array(
                'id' => 'product='.$idProduto, 
                'text' => ucfirst(mb_strtolower($value->nome))
            );
        }
    }

    $dadosBusiness = array(
        'nome_completo' => new \MongoDB\BSON\Regex(utf8_encode($buscar))
    );
    $classeBusinessPartner = new BusinessPartnerRepository;
    $retornoBusiness = $classeBusinessPartner->getBusinessPartnerLimit($dadosBusiness, 3);
    if($retornoBusiness === false){
        throw new Exception($classeBusinessPartner->mensagem);
    }
    $auxBusiness = json_decode($retornoBusiness);
    if(!empty($auxBusiness) && count($auxBusiness)){
        foreach ($auxBusiness as $key => $newValue) {
            $auxIdBus = $newValue->_id;
            foreach ($auxIdBus as $newValores) {
                $idBusiness = $newValores;
            }

            $data[] = array(
                'id' => 'business='.$idBusiness,
                'text' => ucfirst(mb_strtolower($newValue->nome_completo))
            );
        }
    }

    if(!count($data)){
        $data[] = array(
            'id' => 0,
            'text' => 'Nenhum resultado encontrado'
        );
    }
    
    $retornoMensagem = json_encode($data);
    echo $retornoMensagem;
} catch (Exception $ex) {
    $mensagem = array(
        'status' => 'ERRO',
        'retorno' => utf8_encode($ex->getMessage())
    );
    $retornoMensagem = json_encode($mensagem);
    echo $retornoMensagem;
}
