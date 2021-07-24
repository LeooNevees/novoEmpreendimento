<?php

use MongoDB\Operation\Update;

include_once '/var/www/html/novoEmpreendimento/classes/repository/ProductsRepository.php';

/**
 * Classe para realizar a conexão com o banco de dados
 *
 * @author leoneves
 */
class Evaluation{
    public $encontrados = 0;
    public $mensagem;
    private $classeProduct;

    public function __construct() {
        $this->classeProduct = new ProductsRepository;
    }


    public function adicionarAvaliacaoProduto(string $idProduto, string $titulo, string $mensagem, int $estrelas){
        try {
            if(empty($idProduto) || empty($titulo) || empty($mensagem) || empty($estrelas)){
                throw new Exception('Parâmetros inválidos para a função adicionarAvaliacaoProduto');
            }
            $retProduto = $this->buscarAvaliacoesProduto($idProduto);
            if($retProduto === false){
                throw new Exception($this->mensagem); 
            }
            $contadorOpinioes = 1;
            if(isset($retProduto[0]->opinioes)){
                $contadorOpinioes = count((array) $retProduto[0]->opinioes) + 1;
            }

            $retornoAdd = $this->adicionarOpiniao($idProduto, $contadorOpinioes, $titulo, $mensagem, $estrelas);
            if($retornoAdd === false){
                throw new Exception($this->mensagem);
            }
            
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function buscarAvaliacoesProduto($idProduto){
        try {
            if(empty($idProduto)){
                throw new Exception('Parâmetro inválido para a função buscarAvaliacoesProduto');
            }

            $retornoProduct = $this->classeProduct->getProduct($idProduto);
            if($retornoProduct === false){
                throw new Exception($this->classeProduct->mensagem);
            }

            return json_decode($retornoProduct);
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function adicionarOpiniao(string $idProduto, int $contadorOpinioes, string $titulo, string $mensagem, int $estrelas){
        try {
            if(empty($idProduto) || empty($contadorOpinioes) || empty($titulo) || empty($mensagem) || empty($estrelas)){
                throw new Exception('Parâmetros inválidos para a função adicionarOpiniao');
            }

            $dados = array(
                'opinioes' => array(
                    "opiniao_$contadorOpinioes" => array(
                        "estrela" => $estrelas,
                        "titulo" => $titulo,
                        "descricao" => $mensagem,
                        "id_parceiro" => $_SESSION['id'],
                        "nome_parceiro" => $_SESSION['nome'],
                        "data_hora" => date('Y-m-d H:i:s')
                    )
                )
            );
           
            $retornoAdicionar = $this->classeProduct->update($idProduto, $dados);
            if($retornoAdicionar === false){
                throw new Exception($this->classeProduct->mensagem);
            }
            
            if($this->classeProduct->afetados < 1){
                throw new Exception('Erro ao tentar inserir a Opinião. Por favor refaça o procedimento');
            }

            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }
    
}
