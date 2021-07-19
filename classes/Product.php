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
    public $afetados = 0;

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

            $retornoCadastro = $this->efetuarCadastro($retornoValidacao);
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
            $v_array = array_map('mb_strtoupper', array_map('trim', $array));       

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

                if($campo == 'valor'){
                    $find = array("R$"," ", ".");
                    $replace = array("");
                    $arr = array($params['valor']);
                    $auxValor = str_replace($find,$replace,$arr);
                    $params['valor'] = substr(str_replace(',', '.', $auxValor[0]), 2);
                }

                if($campo == 'promocao' && $params[$campo] == 'SIM'){
                    if(empty($params['porcentagemPromocao']) || $params['porcentagemPromocao'] < 1){
                        throw new Exception('Porcentagem de desconto inválido (Mínimo:1 Max:99)');                        
                    }
                }
            }

            foreach ($_FILES['imagens_produto']['name'] as $imagem) {
                if(empty($imagem)){
                    throw new Exception('Necessário selecionar pelo menos uma imagem');   
                }
                if(!in_array(pathinfo($imagem, PATHINFO_EXTENSION), ['png', 'jpeg', 'jpg'])){
                    throw new Exception('Extensão inválida para a imagem '.$imagem);
                }
            }
            
            return $params;
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

            $retornoUpload = $this->uploadImagem();
            if($retornoUpload === false){
                throw new Exception($this->mensagem);
            }

            $dados = array(
                'nome' => $params['nomeProduto'],
                'descricao' => $params['descricaoProduto'],
                'cor' => $params['corProduto'],
                'quantidade_estoque' => $params['quantidadeProduto'],
                'quantidade_vendida' => 0,
                'porcentagem_promocao' => $params['porcentagemPromocao'],
                'valor' => (float) $params['valor'],
                'tipo' => $params['tipo'],
                'status' => 'ATIVO',
                'data_cadastro' => date('Y-m-d'),
                'visualizacao' => 0,
                'grupo' => $params['grupo'],
                'id_vendedor' => $_SESSION['id'],
                'imagens' => $retornoUpload
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

    private function uploadImagem(){
        try {
            if(!isset($_FILES['imagens_produto'])){
                throw new Exception('Parâmetros inválidos para uploadImagem');
            }
            $arrayRetorno = [];
            $pasta = '/var/www/html/novoEmpreendimento/files/'.$_SESSION['id'].'/';
            if (!file_exists($pasta)){
                mkdir($pasta, 0777);
            }

            $nomeArquivo = $_FILES['imagens_produto']['name'];
            $tmpArquivo = $_FILES['imagens_produto']['tmp_name'];

            for ($contador=0; $contador < count($nomeArquivo); $contador++) {     
                $novoNome = date('YmdHis').'.'.pathinfo($nomeArquivo[$contador], PATHINFO_EXTENSION);

                if(!move_uploaded_file($tmpArquivo[$contador], $pasta.$novoNome)){
                    throw new Exception('Erro ao fazer o Upload da imagem: '.$nomeArquivo[$contador].'. Por favor, refaça o procedimento');
                }
                
                $arrayRetorno['link_'.((int) $contador + 1)] = substr($pasta, 13).$novoNome;
            }

            return $arrayRetorno;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    public function inativarProduto($idProduto){
        try {
            if(empty($idProduto) || !is_string($idProduto)){
                throw new Exception('Parâmetro inválido para a função inativarProduto');
            }

            $retornoProduto = $this->buscarProduto($idProduto);
            if($retornoProduto === false){
                throw new Exception($this->mensagem);
            }

            if($this->encontrados < 1){
                throw new Exception('Produto não encontrado. Por favor refaça o procedimento');
            }

            $retornoInativ = $this->efetivarInativacao($idProduto);
            if($retornoInativ === false){
                throw new Exception($this->mensagem);
            }

            if($this->afetados < 1){
                throw new Exception('Erro não especificado ao tentar inativar o item '.$idProduto.'. Por favor refaça o procedimento');
            }

            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function buscarProduto($idProduto){
        try {
            if(empty($idProduto) || !is_string($idProduto)){
                throw new Exception('Parâmetro inválido para a função buscarProduto');
            }  
            
            $classeProducts = new ProductsRepository;
            $retornoProduto = $classeProducts->getProduct($idProduto);
            
            $this->encontrados = $classeProducts->encontrados;
            return $retornoProduto;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function efetivarInativacao($idProduto){
        try {
            if(empty($idProduto) || !is_string($idProduto)){
                throw new Exception('Parâmetro inválido para a função efetivarInativacao');
            }  
            
            $classeProducts = new ProductsRepository;
            $retornoProduto = $classeProducts->update($idProduto, array('status'=>'INATIVO'));
            if($retornoProduto === false){
                throw new Exception('Erro: '.$classeProducts->mensagem.' ao tentar inativar o Item: '.$idProduto);
            }            

            $this->afetados = $classeProducts->afetados;
            return $retornoProduto;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }
}
