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
    
}
