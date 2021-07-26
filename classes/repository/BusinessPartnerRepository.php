<?php
include_once '/var/www/html/novoEmpreendimento/classes/Xmongo.php';

/**
 * Classe para realizar a conexão com o banco de dados
 *
 * @author leoneves
 */
class BusinessPartnerRepository{
    public $encontrados = 0;
    public $mensagem;
    public $idInserido;
    public $afetados;

    function __construct() {
        $this->conexao = new Xmongo();
    }

    /**
     * $dados = Array (ex: '_id' => new MongoDB\BSON\ObjectID($idProduto))
     */
    public function getBusinessPartner(array $dados){
        try {
            if(empty($dados) || !count($dados)){
                throw new Exception('Parâmetros inválidos');
            }
            $requisicao = array(
                'tabela' => 'parceiroNegocio',
                'acao' => 'pesquisar',
                'dados' => $dados
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
    public function getBusinessPartnerLimit($dados = '', $limit = ''){
        try {
            $requisicao = array(
                'tabela' => 'parceiroNegocio',
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
     * $dados Array
     * return Object || Boolean 
     */
    public function insert(array $dados){
        try {
            if(!count($dados)){
                throw new Exception('Parâmetros inválidos para a função Insert');
            }
            
            $requisicao = array(
                'tabela' => 'parceiroNegocio',
                'acao' => 'cadastrar',
                'dados' => $dados
            );
            
            $retornoCadastro = $this->conexao->requisitar($requisicao);
            if ($retornoCadastro === false) {
                throw new Exception($this->conexao->getMensagem());
            }

            if ($this->conexao->getAfetados() < 1) {
                throw new Exception('Problema ao cadastar o parceiro. Por favor refaça o procedimento');
            }

            $this->afetados = $this->conexao->getAfetados();
            $this->idInserido = $this->conexao->getIdInserido();
            return $this->conexao->getMensagem();
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
    public function update(string $idParceiro, array $params){
        try {
            if(empty($idParceiro) || !count($params)){
                throw new Exception('Parâmertos inválidos na função Update');
            }
            $requisicao = array(
                'tabela' => 'parceiroNegocio',
                'acao' => 'atualizar',
                '_id' => $idParceiro,
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
}
