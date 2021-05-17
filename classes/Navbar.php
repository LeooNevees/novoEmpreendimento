<?php

include '/var/www/html/novoEmpreendimento/classes/Xmongo.php';

/**
 * Classe criada para controlar e manusear o Navbar disponibilizado durante o acesso ao sistema
 *
 * @author leoneves
 */
class Navbar {

    private $funcao;
    private $modulo;
    public $encontrados;
    public $mensagem;

    private function valida($array) {
        try {
            if (empty($array)) {
                throw new Exception('Id Função ou Id Módulo não fornecido');
            }

            foreach ($array as $key => $value) {
                if (!is_numeric($value) || $value == 0) {
                    throw new Exception('Id ' . $key . ' inválido. Por favor contate um administrador');
                }
                $set = 'set' . ucfirst($key);
                $this->$set($value);
            }

            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    public function buscaFuncao($idFuncao) {
        try {
            $this->setEncontrados(0);

            $array = array(
                'funcao' => $idFuncao
            );

            $retornoValida = $this->valida($array);

            if ($retornoValida === false) {
                throw new Exception($this->getMensagem());
            }

            $requisicao = array(
                'tabela' => 'funcao',
                'acao' => 'pesquisar',
                'dados' => array(
                    'id' => $this->getFuncao()
                )
            );
            
            $conexao = new Xmongo();
            $retorno = $conexao->requisitar($requisicao);
            if ($retorno === false) {
                throw new Exception($conexao->getMensagem());
            }
            if ($conexao->getEncontrados() < 1) {
                throw new Exception('Função não encontrada');
            }

            $this->setEncontrados($conexao->getEncontrados());

            $retornoDecod = json_decode($conexao->getMensagem());
            $analise = $retornoDecod[0];
            $analiseAcesso = $analise->acesso;

            $this->setMensagem($analiseAcesso);

            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    public function buscaModulo($idModulo) {
        try {
            $this->setEncontrados(0);
            
            $array = array(
                'modulo' => $idModulo
            );

            $retornoValida = $this->valida($array);

            if ($retornoValida === false) {
                throw new Exception($this->getMensagem());
            }

            $conexao = new Xmongo();
            $requisicao = array(
                'tabela' => 'modulos',
                'acao' => 'pesquisar',
                'dados' => array(
                    'id_modulo' => $this->getModulo()
                )
            );

            $retorno = $conexao->requisitar($requisicao);

            if ($retorno === false) {
                throw new Exception($conexao->getMensagem());
            }

            if ($conexao->getEncontrados() < 1) {
                throw new Exception('Módulo não encontrado');
            }
            
            $this->setEncontrados($conexao->getEncontrados());

            $retornoDec = json_decode($conexao->getMensagem());
            $recebeModulo = $retornoDec[0];
            $this->setMensagem($recebeModulo);
            
            return true;
            
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function getFuncao() {
        return $this->funcao;
    }

    private function getModulo() {
        return $this->modulo;
    }

    public function getEncontrados() {
        return $this->encontrados;
    }

    public function getMensagem() {
        return $this->mensagem;
    }

    private function setFuncao($funcao) {
        $this->funcao = $funcao;
    }

    private function setModulo($modulo) {
        $this->modulo = $modulo;
    }

    private function setEncontrados($encontrados) {
        $this->encontrados = $encontrados;
    }

    private function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

}
