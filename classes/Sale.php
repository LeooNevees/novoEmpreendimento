<?php
include __DIR__.'/repository/ProductsRepository.php';
include __DIR__.'/Login.php';

/**
 * Classe para efetivar a compra dos produtos
 *
 * @author leoneves
 */
class Sale{
    public $mensagem;
    public $erro;

    public function gerarCompraProduto($idProduto, $quantidade){
        try {
            if(empty($idProduto) || empty($quantidade)){
                throw new Exception('Parâmetros inválidos na função Gerar Compra Produto');
            }

            if(!$retornoSession = $this->validarSession()){
                $this->erro = 'LOGIN';
                throw new Exception($this->mensagem);                
            }

            if(!$retornoAnaliseProduto = $this->analiseProduto($idProduto, $quantidade)){
                $this->erro = 'PRODUTO';
                throw new Exception($this->mensagem);
            }
            

            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function validarSession(){
        try {
            $classeSession = new Login;
            $retornoSession = $classeSession->validarExistenciaSession();
            if($classeSession->getMensagem() == 'NAO EXISTE'){
                throw new Exception('Necessário fazer Login para realizar a compra');
            }
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function analiseProduto($idProduto, $quantidade){
        try {
            if(empty($idProduto) || empty($quantidade)){
                throw new Exception('Parâmetros inválidos na função Analise Produto');
            }
            $classeProduto = new ProductsRepository;
            $retornoProduto = $classeProduto->getProduct($idProduto);
            if($retornoProduto === false){
                throw new Exception('Erro ao tentar buscar o produto. Por favor refaça o procedimento');
            }
            if($classeProduto->encontrados == 0){
                throw new Exception('Nenhum produto encontrado com o Id '.$idProduto);
            }

            if($classeProduto->quantidade_estoque < $quantidade){
                throw new Exception("Quantidade solicitada indisponível no estoque");
            }
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }    
}
