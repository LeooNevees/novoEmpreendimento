<?php
include __DIR__.'/repository/ProductsRepository.php';

/**
 * Classe para efetivar a compra dos produtos
 *
 * @author leoneves
 */
class Sale{
    public $mensagem;

    public function gerarCompraProduto($idProduto){
        try {
            if(empty($idProduto)){
                throw new Exception('Parâmetros inválidos');
            }
            $classeProduto = new ProductsRepository;
            $retornoProduto = $classeProduto->getProduct($idProduto);
            if($retornoProduto === false){
                throw new Exception('Erro ao tentar buscar o produto. Por favor refaça o procedimento');
            }
            if($classeProduto->encontrados == 0){
                throw new Exception('Nenhum produto encontrado com o Id '.$idProduto);
            }
            
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }    
}
