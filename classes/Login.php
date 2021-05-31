<?php

if (!isset($_SESSION)) {
    session_start();
}

/**
 * Classe para a valida��o do usu�rio informado
 *
 * @author leoneves
 */
class Login {

    private $login;
    private $nome_completo;
    private $setor;
    private $funcao;
    private $senha;
    private $situacao;
    private $dispositivo;
    private $encontrados;
    private $mensagem;

    function __construct() {
        $this->encontrados = 0;
    }

    private function corrigeAcesso() {
        try {
            $login = mb_strtoupper(trim($this->getLogin()));
            $senha = trim($this->getSenha());

            if (empty($login) || trim($login) == '') {
                throw new Exception('Necess�rio informar o Login');
            }

            if (empty($senha) || trim($senha) == '') {
                throw new Exception('Necess�rio informar a Senha');
            }

            $this->setLogin($login);
            $this->setSenha($senha);

            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function validarAcessoBanco() {
        try {
            $conexao = new Xmongo;
            $requisicao = array(
                'tabela' => 'parceiroNegocio',
                'acao' => 'pesquisar',
                'dados' => array(
                    'login' => $this->getLogin(),
                    'senha' => $this->getSenha()
                )
            );

            $retUsuario = $conexao->requisitar($requisicao);

            if ($retUsuario === false) {
                throw new Exception($conexao->getMensagem());
            }

            if ($conexao->getEncontrados() < 1) {
                throw new Exception('Usu�rio n�o encontrado');
            }

            $this->setEncontrados($conexao->getEncontrados());

            $analiseRetorno = json_decode($conexao->getMensagem());
            $string = $analiseRetorno[0];

            $array = ['login', 'nome_completo', 'situacao', 'setor', 'funcao'];

            foreach ($array as $registros) {
                if (empty($string->$registros) || trim($string->$registros) == '') {
                    throw new Exception(ucfirst($registros) . 'n�o informado no retorno do banco');
                }
                $set = 'set' . ucfirst($registros);
                $this->$set($string->$registros);
            }

            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function DadosSession() {
        try {
            $array = ['login', 'nome_completo', 'setor', 'funcao'];

            foreach ($array as $registros) {
                $get = 'get' . ucfirst($registros);
                if (empty($this->$get()) || trim($this->$get()) == '') {
                    throw new Exception(ucfirst($registros) . ' n�o informado');
                }
            }

            $_SESSION = array(
                'login' => $this->getLogin(),
                'nome' => $this->getNome_completo(),
                'setor' => $this->getSetor(),
                'funcao' => $this->getFuncao()
            );

            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    public function validarLogin($login, $senha) {
        try {
            $this->setLogin($login);
            $this->setSenha($senha);

            $retorno = $this->corrigeAcesso();

            if ($retorno === false) {
                throw new Exception($this->getMensagem());
            }

            $retornoAcessoBanco = $this->validarAcessoBanco();

            if ($retornoAcessoBanco === false) {
                throw new Exception($this->getMensagem());
            }

            if ($this->getSituacao() != 'A') {
                throw new Exception('Usuário inativo');
            }

            $retornoSession = $this->DadosSession();

            if ($retornoSession === false) {
                throw new Exception($this->getMensagem());
            }

            $this->setMensagem('Usuário com permissão');

            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function verificarSession() {
        try {
            $sessao = 'INATIVO';

            if (session_status() == PHP_SESSION_ACTIVE) {
                $sessao = 'ATIVO';
            }

            $this->setMensagem($sessao);

            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }
    
    public function validarExistenciaSession(){
        try {
            if(isset($_SESSION['login'])){
                $retorno = 'EXISTE';
            }else{
                $retorno = 'NAO EXISTE';
            }
            $this->setMensagem($retorno);
            return true;            
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    public function fazerLogout() {
        try {
            $retorno = $this->verificarSession();

            if ($retorno === false) {
                throw new Exception($this->getMensagem());
            }

            $sessao = $this->getMensagem();

            if ($sessao == 'INATIVO') {
                throw new Exception('Sess�o j� inativada');
            }

            session_destroy();
            unset($_SESSION);

            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function getLogin() {
        return $this->login;
    }

    private function getNome_completo() {
        return $this->nome_completo;
    }

    private function getSetor() {
        return $this->setor;
    }

    private function getFuncao() {
        return $this->funcao;
    }

    private function getSenha() {
        return $this->senha;
    }

    private function getSituacao() {
        return $this->situacao;
    }

    private function getDispositivo() {
        return $this->dispositivo;
    }

    function getEncontrados() {
        return $this->encontrados;
    }

    function getMensagem() {
        return $this->mensagem;
    }

    private function setLogin($login) {
        $this->login = $login;
    }

    private function setNome_completo($nome_completo) {
        $this->nome_completo = $nome_completo;
    }

    private function setSetor($setor) {
        $this->setor = $setor;
    }

    private function setFuncao($funcao) {
        $this->funcao = $funcao;
    }

    private function setSenha($senha) {
        $this->senha = $senha;
    }

    private function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    private function setDispositivo($dispositivo) {
        $this->dispositivo = $dispositivo;
    }

    private function setEncontrados($encontrados) {
        $this->encontrados = $encontrados;
    }

    private function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

}
