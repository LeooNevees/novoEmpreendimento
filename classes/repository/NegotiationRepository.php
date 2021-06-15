<?php
include_once '/var/www/html/novoEmpreendimento/classes/Xmongo.php';

/**
 * Classe para realizar a conexão com o banco de dados
 *
 * @author leoneves
 */
class NegotiationRepository{
    public $encontrados = 0;
    public $mensagem;

    function __construct() {
        $this->conexao = new Xmongo();
    }

    /**
     * $dados Array
     * return Object || Boolean 
     */
    public function getNegotiation(){
        try {
            if(empty($dados)){
                throw new Exception('Parâmetros inválidos para a função getNegotiation');
            }

            $requisicao = array(
                'tabela' => 'negociacao',
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
     * return Object || Boolean 
     */
    public function insertNegotiation($dados){
        try {
            if(empty($dados)){
                throw new Exception('Parâmetros inválidos para a função insertNegotiation');
            }

            $requisicao = array(
                'tabela' => 'negociacao',
                'acao' => 'cadastrar',
                'dados' => $dados
            );
    
            $retorno = $this->conexao->requisitar($requisicao);
            if ($retorno === false) {
                throw new Exception($this->conexao->getMensagem());
            }

            return $this->conexao->getMensagem();
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    } 
}
