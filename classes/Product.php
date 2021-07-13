<?php

use MongoDB\Operation\Executable;

include_once 'Xmongo.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/GroupsRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/ProductsRepository.php';

/**
 * Classe criada para a criação de Produtos
 * 
 * @author leoneves
 */
class Product {
    public $mensagem;
    public $encontrados = 0;
    public $cadastrados = 0;

    public function __construct() {
        if(!isset($_SESSION)){
            session_start();
        }
    }

    public function cadastrarProdutos($params){
        try {
            if(empty($params) || !count($params)){
                throw new Exception('Parâmetros inválidos para a função cadastrarProdutos');
            }
            $retornoCorrigido = $this->corrigeDados($params);
            if($retornoCorrigido === false){
                throw new Exception($this->mensagem);
            }

            $retornoValidacao = $this->validarDados($retornoCorrigido);
            if($retornoValidacao === false){
                throw new Exception($this->mensagem);
            }

            $retornoCadastro = $this->efetuarCadastro($retornoCorrigido);
            if($retornoCadastro === false){
                throw new Exception('Erro ao tentar cadastrar o Produto. '.$this->mensagem);
            }

            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function corrigeDados($array){
        try {
            if(!is_array($array)){
                throw new Exception('Necessário que os dados passados sejam no formato Array');
            }

            if(empty($array) || !count($array)){
                throw new Exception('Parâmetros inválidos para a função corrigeDados');
            }

            $v_array = [];
            $v_array = array_map('mb_strtoupper', array_map('utf8_encode', array_map('trim', $array)));

            // $teste = number_format($array['valor'], 2);

            return $v_array;    
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function validarDados($params){
        try {   
            if(empty($params) || !count($params)){
                throw new Exception('Parâmetros inválidos para a função validarDados');
            }
            
            $arrayValidar = ['nomeProduto', 'quantidadeProduto', 'descricaoProduto', 'corProduto', 'tipo', 'grupo', 'valor', 'promocao'];
            foreach ($arrayValidar as $campo) {
                if(empty($params[$campo])){
                    throw new Exception('Necessário informar '.$campo);
                }
                if($campo == 'grupo'){
                    $classeGrupo = new GroupsRepository;
                    $dados = array(
                        'id' => $params['grupo']
                    );
                    $retornoValidaGrupo = json_decode($classeGrupo->getGroups($dados));
                    if($retornoValidaGrupo === false){
                        throw new Exception('Erro ao buscar o Grupo fornecido. Erro: '.$classeGrupo->mensagem);
                    }                    

                    if($classeGrupo->encontrados < 1){
                        throw new Exception('Grupo não encontrado. Por favor refaça o procedimento');                        
                    }
                    $retornoValidaGrupo = $retornoValidaGrupo[0];
                    if($retornoValidaGrupo->situacao == 'INATIVO'){
                        throw new Exception('Grupo Inativo. Por favor selecione outro Grupo');
                    }
                }
                if($campo == 'promocao' && $params[$campo] == 'SIM'){
                    if(empty($params['porcentagemPromocao']) || $params['porcentagemPromocao'] < 1){
                        throw new Exception('Porcentagem de desconto inválido (Mínimo:1 Max:99)');                        
                    }
                }
            }
            return true;

        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function efetuarCadastro($params){
        try {
            if(empty($params) || !count($params)){
                throw new Exception('Parâmetros inválidos para a função efetuarCadastro');
            }

            $dados = array(
                'nome' => $params['nomeProduto'],
                'descricao' => $params['nomeProduto'],
                'quantidade_estoque' => $params['nomeProduto'],
                'quantidade_vendida' => 0,
                'porcentagem_promocao' => $params['porcentagemPromocao'],
                'valor' => (float) $params['valor'],
                'tipo' => $params['tipo'],
                'status' => 'ATIVO',
                'data_cadastro' => date('Y-m-d'),
                'visualizacao' => 0,
                'grupo' => $params['grupo'],
                'id_vendedor' => $_SESSION['id']
            );
            $classeProduto = new ProductsRepository;
            $retornoCadastro = $classeProduto->insertProduct($dados);
            if($retornoCadastro === false){
                throw new Exception('Erro ao cadastrar o Produto. Erro: '.$classeProduto->mensagem);
            }
            if($classeProduto->afetados < 1){
                throw new Exception('Produto não cadastrado. Por favor tente novamente (Erro: '.$classeProduto->mensagem.')');
            }
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }
}
