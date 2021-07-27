<?php

include_once 'Login.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/BusinessPartnerRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/RelationshipBusinessPartnerRepository.php';


/**
 * Classe criada para a gestao dos Parceiros de Negocios
 * 
 * @author leoneves
 */
class BusinessPartner {

    private $nomeCompleto;
    private $nomeFantasia;
    private $email;
    private $sexo;
    private $cpfcnpj;
    private $telefone;
    private $cep;
    private $rua;
    private $numero;
    private $bairro;
    private $cidade;
    private $uf;
    private $senha;
    private $confSenha;
    private $encontrados;
    private $alterados;
    private $cadastrados;
    private $mensagem;
    private $classeParceiro;

    public function __construct(){
        $this->encontrados = 0;
        $this->alterados = 0;
        $this->cadastrados = 0;
        $this->classeParceiro = new BusinessPartnerRepository;
    }
    
    public function cadastrarParceiro($array) {
        try {
            if (!is_array($array)) {
                throw new Exception('Esperado informações via Array. Por favor refaça o procedimento');
            }

            $retorno = $this->corrigeDadosParceiro($array);

            if ($retorno === false) {
                return false;
            }

            $retornoValidacao = $this->validarDadosParceiro('CADASTRAR');

            if ($retornoValidacao === false) {
                return false;
            }

            $params = array(
                'cpf_cnpj' => $this->getCpfcnpj()
            );
            $retornoBanco = $this->classeParceiro->getBusinessPartner($params);
            if ($this->classeParceiro->encontrados > 0) {
                throw new Exception('CPF/CNPJ já sendo utilizado');
            }
            
            $buscaEmail = array(
                'login' => $this->getEmail()
            );
            $retornoLogin = $this->classeParceiro->getBusinessPartner($buscaEmail);
            if ($this->classeParceiro->encontrados > 0) {
                throw new Exception('E-mail já sendo utilizado');
            }
            
            $retornoEfetivar = $this->efetivarCadastro();
            if($retornoEfetivar === false){
                throw new Exception($this->mensagem);
            }

            $classeLogin = new Login;
            $retLogin = $classeLogin->validarLogin($this->getEmail(), $this->getSenha());
            if($retLogin === false){
                throw new Exception('Erro ao validar o usuário. Por favor faça o Login manualmente');
            }

            $retUpload = $this->uploadImagem();
            if($retUpload === false){
                throw new Exception('Erro ao fazer o Upload da Imagem. Faça o Login e tente incluir manualmente');
            }

            if($retUpload != null){
                $newRequisicao = array(
                    'foto' => $retUpload
                );
                $retNew = $this->classeParceiro->update($_SESSION['id'], $newRequisicao);
                if ($retNew === false) {
                    throw new Exception($this->classeParceiro->mensagem);
                }
    
                if ($this->classeParceiro->afetados < 1) {
                    throw new Exception('Problema ao cadastar o parceiro. Por favor refaça o procedimento');
                }
            }
            
            $retornoRelacaoParceiro = $this->cadastrarRelacaoParceiro();
            if($retornoRelacaoParceiro === false){
                throw new Exception($this->mensagem);
            }

            $this->setMensagem('Parceiro cadastrado com sucesso');
            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function corrigeDadosParceiro($array) {
        try {
            if (!is_array($array)) {
                throw new Exception('Necessário que os dados fornecidos sejam passados via Array');
            }

            $v_array = [];

            $v_array = array_map('mb_strtoupper', array_map('trim', $array));


            foreach ($v_array as $key => $value) {
                $v_myClass = ['nomeCompleto', 'nomeFantasia', 'email', 'sexo', 'cpfcnpj', 'telefone', 'cep', 'rua', 'numero', 'bairro', 'cidade', 'uf', 'senha', 'confSenha'];

                if(!in_array($key, $v_myClass)){
                    continue;
                }
                
                $set = 'set' . ucfirst($key);
                
                //FAZER A TRATATIVA DOS DADOS RECEBIDOS
                if (empty($value) || trim($value) == '') {
                    continue;
                }
                if ($key == 'cpfcnpj' || $key == 'telefone' || $key == 'cep' || $key == 'numero') {
                    $value = preg_replace('/[^0-9]/', '', $value);
                }
                $this->$set($value);
            }

            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function validarDadosParceiro($tipo) {
        try {
            switch ($tipo) {
                case 'CADASTRAR':

                    $v_myClass = ['nomeCompleto', 'nomeFantasia', 'email', 'sexo', 'cpfcnpj', 'telefone', 'cep', 'rua', 'numero', 'bairro', 'cidade', 'uf', 'senha', 'confSenha'];

                    foreach ($v_myClass as $key) {
                        $get = 'get' . ucfirst($key);
                        if (empty($this->$get()) || trim($this->$get()) === '') {
                            throw new Exception('Necessário informar ' . ucfirst($key));
                        }

                        if (($key === 'cpfcnpj') && (strlen($this->$get()) < 11 || strlen($this->$get()) > 14)) {
                            throw new Exception('CPF ou CNPJ inválido');
                        }

                        if (($key === 'telefone') && (strlen($this->$get()) < 9 || strlen($this->$get()) > 12)) {
                            throw new Exception('Número telefônico inválido');
                        }

                        if ($key === 'cep' && strlen($this->$get()) != 8) {
                            throw new Exception('CEP inválido');
                        }

                        if ($key === 'senha' && strlen($this->$get()) < 6) {
                            throw new Exception('Necessário que a senha tenha no mínimo 6 caracteres');
                        }
                        
                        if($key === 'confSenha' && $this->getSenha() != $this->getConfSenha()){
                            throw new Exception('Necessário que as senhas sejam idênticas');
                        }
                    }

                    return true;

                    break;

                case 'EDITAR':
                    $v_myClass = ['nomeFantasia', 'email', 'telefone', 'cep', 'rua', 'numero', 'bairro', 'cidade', 'uf', 'senha'];


                    break;

                default:
                    throw new Exception('Erro desconhecido. Por favor contate o administrador do sistema');
                    break;
            }
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function efetivarCadastro(){
        try {
            $dados = array(
                'nome_completo' => $this->getNomeCompleto(),
                'nome_fantasia' => $this->getNomeFantasia(),
                'email' => $this->getEmail(),
                'sexo' => $this->getSexo(),
                'cpf_cnpj' => $this->getCpfcnpj(),
                'telefone' => $this->getTelefone(),
                'cep' => $this->getCep(),
                'rua' => $this->getRua(),
                'numero' => $this->getNumero(),
                'bairro' => $this->getBairro(),
                'cidade' => $this->getCidade(),
                'uf' => $this->getUf(),
                'login' => $this->getEmail(),
                'senha' => $this->getSenha(),
                'situacao' => 'A',
                'setor' => '2',
                'descricao_setor' => 'ACESSO CLIENTE',
                'funcao' => '20',
                'descricao_funcao' => 'MERCADO',
                'data_cadastro' => date('Y-m-d H:i:s')
            );
            
            $retornoCadastro = $this->classeParceiro->insert($dados);
            if ($retornoCadastro === false) {
                throw new Exception($this->classeParceiro->mensagem);
            }

            if ($this->classeParceiro->afetados < 1) {
                throw new Exception('Problema ao cadastar o parceiro. Por favor refaça o procedimento');
            }
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function uploadImagem(){
        try {
            if(empty($_FILES['imagens_parceiro']['name'])){
                return null;
            }
            $arrayRetorno = [];
            $pasta = '/var/www/html/novoEmpreendimento/files/'.$_SESSION['id'].'/';
            if (!file_exists($pasta)){
                mkdir($pasta, 0777);
            }

            $nomeArquivo = $_FILES['imagens_parceiro']['name'];
            $tmpArquivo = $_FILES['imagens_parceiro']['tmp_name'];
            $novoNome = date('YmdHis').'.'.pathinfo($nomeArquivo, PATHINFO_EXTENSION);

            if(!move_uploaded_file($tmpArquivo, $pasta.$novoNome)){
                throw new Exception('Erro ao fazer o Upload da imagem: '.$nomeArquivo.'. Por favor, refaça o procedimento');
            }

            return substr($pasta, 13).$novoNome;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    public function editarParceiro($array) {
        try {
            if (!is_array($array)) {
                throw new Exception('Necessário que os dados fornecidos sejam passados via Array');
            }

            $retorno = $this->corrigeDadosParceiro($array);

            if ($retorno === false) {
                return false;
            }

            $retornoValidar = $this->validarDadosParceiro('ALTERAR');
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }

    private function cadastrarRelacaoParceiro(){
        try {
            $dados = array(
                'id_parceiro' => $this->classeParceiro->idInserido,
                'nome_completo' => $this->getNomeCompleto(),
                'nome_fantasia' => $this->getNomeFantasia(),
                'compras' => 0,
                'vendas' => 0,
                'media_entrega' => 0,
                'media_atendimento' => 0,
                'classificacao' => 'BRONZE'
            );

            $classeRelation = new RelationshipBusinessPartnerRepository;
            $retorno = $classeRelation->insert($dados);
            if($retorno === false){
                throw new Exception($classeRelation->mensagem);
            }

            if($classeRelation->afetados < 1){
                throw new Exception('Erro ao cadastrar a Relacao Parceiro');
            }

            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function getNomeCompleto() {
        return $this->nomeCompleto;
    }

    private function getNomeFantasia() {
        return $this->nomeFantasia;
    }

    private function getTipo() {
        return $this->tipo;
    }

    private function getEmail() {
        return $this->email;
    }

    private function getSexo() {
        return $this->sexo;
    }

    private function getCpfcnpj() {
        return $this->cpfcnpj;
    }

    private function getTelefone() {
        return $this->telefone;
    }

    private function getCep() {
        return $this->cep;
    }

    private function getRua() {
        return $this->rua;
    }

    private function getNumero() {
        return $this->numero;
    }

    private function getBairro() {
        return $this->bairro;
    }

    private function getCidade() {
        return $this->cidade;
    }

    private function getUf() {
        return $this->uf;
    }

    private function getSenha() {
        return $this->senha;
    }

    public function getMensagem() {
        return $this->mensagem;
    }
    
    public function getConfSenha() {
        return $this->confSenha;
    }

    private function setNomeCompleto($nomeCompleto) {
        $this->nomeCompleto = $nomeCompleto;
    }

    private function setNomeFantasia($nomeFantasia) {
        $this->nomeFantasia = $nomeFantasia;
    }

    private function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    private function setEmail($email) {
        $this->email = $email;
    }

    private function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    private function setCpfcnpj($cpfcnpj) {
        $this->cpfcnpj = $cpfcnpj;
    }

    private function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    private function setCep($cep) {
        $this->cep = $cep;
    }

    private function setRua($rua) {
        $this->rua = $rua;
    }

    private function setNumero($numero) {
        $this->numero = $numero;
    }

    private function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    private function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    private function setUf($uf) {
        $this->uf = $uf;
    }

    private function setSenha($senha) {
        $this->senha = $senha;
    }

    private function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }
    
    private function setConfSenha($confSenha){
        $this->confSenha = $confSenha;
    }

}
