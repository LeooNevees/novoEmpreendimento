<?php
include_once '/var/www/html/novoEmpreendimento/classes/Xmongo.php';

/**
 * Classe para realizar a conexão com o banco de dados
 *
 * @author leoneves
 */
class ProductsRepository{
    public $encontrados = 0;
    public $mensagem;

    function __construct() {
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

    /**
     * $dados Array
     * $limit String 
     * return Object || Boolean 
     */
    public function getProductLimit($dados = '', $limit = ''){
        try {
            $requisicao = array(
                'tabela' => 'produtos',
                'acao' => 'pesquisar',
            );

            if(!empty($dados) && count($dados) > 0){
                $requisicao['dados'] = $dados;
            }
            if(!empty($limit)){
                $requisicao['limit'] = $limit;
            }
    
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
