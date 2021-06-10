<?php
include_once '/var/www/html/novoEmpreendimento/vendor/autoload.php';
/**
 * Description of xmongo
 * Realiza a conexao com o banco de dados Mongo
 * @author leoneves
 */
class Xmongo {

    private $conexao;     // Handle da conexao
    private $auto_inc;      //Ultimo codigo incremental atribuido ao valor inserido
    private $encontrados;    // Numero de registros encontrados na pesquisa
    private $afetados;    // Numero de registros afetados nos procedimentos
    private $mensagem;      // Ultima mensagem de erro para exibicao

    public function __construct() {
        $this->afetados = 0;
    }

    private function conectar($tabela) {
        try {
            $client = new MongoDB\Client('mongodb://localhost:27017');  //IP
            $db = $client->empreendimento;
            $collection = $db->$tabela;
            
            if ($collection === false) {
                throw new Exception('Erro ao estabelecer conexão com o banco de dados');
                return false;
            }
            return $collection;
            
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    public function requisitar($requisicao) {
        try {
            $this->setEncontrados(0);
            $tabela = trim($requisicao['tabela']);
            $acao = trim(mb_strtoupper($requisicao['acao']));
            $filter = isset($requisicao['dados']) ? $requisicao['dados'] : '';
            $limite = isset($requisicao['limit']) ? $requisicao['limit'] : 3;

            if (empty($tabela) || trim($tabela) == '') {
                throw new Exception('Necessário informar a Tabela');
            }

            if (empty($acao) || trim($acao) == '') {
                throw new Exception('Necessário informar a Ação');
            }

            $collection = $this->conectar($tabela);
            
            if ($collection === false) {
                throw new Exception('Erro ao estabelecer conexão com o banco');
            }
            
            switch ($acao) {
                case 'PESQUISAR':
                    if (empty($filter)) {
                        $cursor = $collection->find();
                    } else {
                        $cursor = $collection->find($filter, ['limit' => $limite]);
                    }

                    foreach ($cursor as $registros) {
                        $this->setEncontrados($this->getEncontrados() + 1);
                        $retorno[] = $registros;
                    }

                    if (isset($retorno)) {
                        $mensagem = json_encode($retorno);
                        $this->setMensagem($mensagem);
                    }
                    return true;
                    break;

                case 'CADASTRAR':
                    if (empty($filter)) {
                        throw new Exception('Não possui parâmetros para cadastrar');
                    }

                    $cursor = $collection->insertOne($filter);

                    $resultado = $cursor->getInsertedCount();

                    if (empty($resultado) || $resultado < 1) {
                        throw new Exception('Não foi possível cadastrar o Parceiro. Por favor refaça o procedimento');
                    }

                    $this->setAfetados(+1);
                    return true;
                    break;
                default:
                    throw new Exception('Erro inesperado');
                    break;
            }
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function getConexao() {
        return $this->conexao;
    }

    public function getAuto_inc() {
        return $this->auto_inc;
    }

    public function getEncontrados() {
        return $this->encontrados;
    }

    public function getAfetados() {
        return $this->afetados;
    }

    public function getMensagem() {
        return $this->mensagem;
    }

    private function setConexao($conexao) {
        $this->conexao = $conexao;
    }

    private function setAuto_inc($auto_inc) {
        $this->auto_inc = $auto_inc;
    }

    private function setEncontrados($encontrados) {
        $this->encontrados = $encontrados;
    }

    private function setAfetados($afetados) {
        $this->afetados = $afetados;
    }

    private function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

}
