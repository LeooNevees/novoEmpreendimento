<?php
include __DIR__.'/../Xmongo.php';

/**
 * Classe para realizar a conexão com o banco de dados
 *
 * @author leoneves
 */
class ProductsRepository{
    public $encontrados = 0;
    public $mensagem;

    private function __construct() {
        $this->conexao = new Xmongo();
    }

    public function getProduct($idProduto){
        try {
            if(empty($idProduto)){
                throw new Exception('Parâmetros inválidos');
            }
            $requisicao = array(
                'tabela' => 'produtos',
                'acao' => 'pesquisar',
                'dados' => array(
                    '_id' => new MongoDB\BSON\ObjectID($idProduto)
                )
            );
    
            $retorno = $this->conexao->requisitar($requisicao);
            if ($retorno === false) {
                throw new Exception($this->conexao->getMensagem());
            }
    
            $this->encontrados = $this->conexao->getEncontrados();

            return $this->conexao->getMensagem();
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }
    
}
