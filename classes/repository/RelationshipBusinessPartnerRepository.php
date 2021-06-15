<?php
include_once '/var/www/html/novoEmpreendimento/classes/Xmongo.php';

/**
 * Classe para realizar a conexão com o banco de dados
 *
 * @author leoneves
 */
class RelationshipBusinessPartnerRepository{
    public $encontrados = 0;
    public $mensagem;

    function __construct() {
        $this->conexao = new Xmongo();
    }

    /**
     * $dados Array
     * $limit String 
     * return Object || Boolean 
     */
    public function getRelationBusiness($dados){
        try {
            if(empty($dados)){
                throw new Exception('Parâmetros inválidos para a função getRelationBusiness');
            }

            $requisicao = array(
                'tabela' => 'relacaoParceiroNegocio',
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
     * $id String
     * $dados Array
     * return Object || Boolean 
     */
    public function updateRelationshipBusiness($id, $dados){
        try {
            if(empty($id) || !count($dados)){
                throw new Exception('Parâmetros inválidos na função Update Products');
            }
            $requisicao = array(
                'tabela' => 'relacaoParceiroNegocio',
                'acao' => 'atualizar',
                '_id' => array(
                    'id_parceiro' => $id
                ),
                'dados' => $dados
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
