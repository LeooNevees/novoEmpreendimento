<?php
include_once '/var/www/html/novoEmpreendimento/classes/Xmongo.php';

/**
 * Classe para realizar a conexão com o banco de dados
 *
 * @author leoneves
 */
class ProductsRepository{
    public $encontrados = 0;
    public $afetados = 0;
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

    /**
     * $idProduto String
     * $quantidade String
     * return Object || Boolean 
     */
    public function updateProducts($idProduto, $quantidadeEstoque, $quantidadeVendida){
        try {
            if(empty($idProduto) || empty($quantidadeEstoque) || empty($quantidadeVendida)){
                throw new Exception('Parâmertos inválidos na função Update Products');
            }
            $requisicao = array(
                'tabela' => 'produtos',
                'acao' => 'atualizar',
                '_id' => $idProduto,
                'dados' => array(
                    'quantidade_estoque' => $quantidadeEstoque,
                    'quantidade_vendida' => $quantidadeVendida
                )
            );

            $retorno = $this->conexao->requisitar($requisicao);
            if ($retorno === false) {
                throw new Exception($this->conexao->getMensagem());
            }
            $this->afetados = $this->conexao->getAfetados();
            $this->mensagem = $this->conexao->getMensagem();
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    /**
     * $idProduto String
     * $params Array
     * return Object || Boolean 
     */
    public function update(string $idProduto, array $params){
        try {
            if(empty($idProduto) || !count($params)){
                throw new Exception('Parâmertos inválidos na função Update Products');
            }
            $requisicao = array(
                'tabela' => 'produtos',
                'acao' => 'atualizar',
                '_id' => $idProduto,
                'dados' => $params
            );

            $retorno = $this->conexao->requisitar($requisicao);
            if ($retorno === false) {
                throw new Exception($this->conexao->getMensagem());
            }
            $this->afetados = $this->conexao->getAfetados();
            $this->mensagem = $this->conexao->getMensagem();
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    /**
     * $dados Array
     * return Object || Boolean 
     */
    public function insertProduct($dados){
        try {
            if(empty($dados) || !count($dados)){
                throw new Exception('Parâmetros inválidos para a função insertProduct');
            }

            $requisicao = array(
                'tabela' => 'produtos',
                'acao' => 'cadastrar',
                'dados' => $dados
            );
    
            $retorno = $this->conexao->requisitar($requisicao);
            if ($retorno === false) {
                throw new Exception($this->conexao->getMensagem());
            }
            $this->afetados = $this->conexao->getAfetados();
            return $this->conexao->getMensagem();
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    } 
}
