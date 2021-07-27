<?php
include_once '/var/www/html/novoEmpreendimento/classes/Xmongo.php';

/**
 * Classe para realizar a conexão com o banco de dados
 *
 * @author leoneves
 */
class GroupsRepository{
    public $encontrados = 0;
    public $mensagem;

    function __construct() {
        $this->conexao = new Xmongo();
    }

    public function getGroups($dados, $limite = 10){
        try {
            if(!count($dados) || empty($limite)){
                throw new Exception('Parâmetros inválidos para a função getGroupsLimit');
            }
            $requisicao = array(
                'tabela' => 'grupos',
                'acao' => 'pesquisar',
                'dados' => $dados,
                'limit' => $limite
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
