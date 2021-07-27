<?php

use MongoDB\Operation\Update;

include_once '/var/www/html/novoEmpreendimento/classes/repository/ProductsRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/RelationshipBusinessPartnerRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/NegotiationRepository.php';

/**
 * Classe para realizar a conexão com o banco de dados
 *
 * @author leoneves
 */
class Evaluation{
    public $encontrados = 0;
    public $mensagem;
    public $idVendedor;
    private $classeProduct;

    public function __construct() {
        $this->classeProduct = new ProductsRepository;
    }


    public function adicionarAvaliacaoProduto(string $idProduto, string $titulo, string $mensagem, int $estrelas){
        try {
            if(empty($idProduto) || empty($titulo) || empty($estrelas)){
                throw new Exception('Parâmetros inválidos para a função adicionarAvaliacaoProduto');
            }
            $retProduto = $this->buscarAvaliacoesProduto($idProduto);
            if($retProduto === false){
                throw new Exception($this->mensagem); 
            }
            $this->idVendedor = $retProduto[0]->id_vendedor;
            $contadorOpinioes = 1;
            if(isset($retProduto[0]->opinioes)){
                $contadorOpinioes = count((array) $retProduto[0]->opinioes) + 1;
            }

            $retornoAdd = $this->adicionarOpiniao($idProduto, $contadorOpinioes, $titulo, $mensagem, $estrelas);
            if($retornoAdd === false){
                throw new Exception($this->mensagem);
            }
            
            $retornoAtualizarMedia = $this->atualizarMedia('PRODUTO', $idProduto);
            if($retornoAtualizarMedia === false){
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
            if(empty($idProduto) || empty($contadorOpinioes) || empty($titulo) || empty($estrelas)){
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

    public function adicionarAvaliacaoVendedor(string $idNegociacao, string $titulo, int $atendimento, int $tempoEntrega, string $observacao){
        try {
            if(empty($titulo) || empty($atendimento) || empty($tempoEntrega) || is_null($this->idVendedor) || empty($idNegociacao)){
                throw new Exception('Parâmetros inválidos para a função adicionarAvaliacaoVendedor');
            }
            
            $retornoRelacao = json_decode($this->buscarRelacaoParceiro($this->idVendedor));
            if($retornoRelacao === false){
                throw new Exception($this->mensagem);
            }

            $contadorAvaliacoes = 1;
            if(isset($retornoRelacao[0]->avaliacoes_vendas)){
                $contadorAvaliacoes = count((array) $retornoRelacao[0]->avaliacoes_vendas) + 1;
            }

            $retornoAdd = $this->adicionarOpiniaoVendedor($idNegociacao, $atendimento, $tempoEntrega, $observacao, $titulo, $contadorAvaliacoes, $this->idVendedor);
            if($retornoAdd === false){
                throw new Exception($this->mensagem);
            }
            
            $retornoAtualizarMedia = $this->atualizarMedia('PARCEIRO', $this->idVendedor);
            if($retornoAtualizarMedia === false){
                throw new Exception($this->mensagem);
            }

            return true;            
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function buscarRelacaoParceiro($idVendedor){
        try {
            if(empty($idVendedor)){
                throw new Exception('Parâmetro inválido para a função buscarRelacaoParceiro');
            }

            $classeRelacaoParceiro = new RelationshipBusinessPartnerRepository;
            $retornoRelacao = $classeRelacaoParceiro->getRelationBusiness(['id_parceiro' => $idVendedor]);
            if($retornoRelacao === false){
                throw new Exception($classeRelacaoParceiro->mensagem);
            }
            
            if($classeRelacaoParceiro->encontrados < 1){
                throw new Exception('Nenhum Parceiro encontrado com o Id: '.$idVendedor);
            }

            return $retornoRelacao;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function adicionarOpiniaoVendedor(string $idNegociacao, int $atendimento, int $tempoEntrega, string $observacao, string $titulo, int $contadorAvaliacoes, string $idVendedor){
        try {
            if(empty($idNegociacao) || empty($atendimento) || empty($tempoEntrega) || empty($titulo) || empty($contadorAvaliacoes)){
                throw new Exception('Parâmetros inválidos para a função adicionarOpiniaoVendedor');
            }

            $dados = array(
                'avaliacoes_vendas' => array(
                    "avaliacao_$contadorAvaliacoes" => array(
                        "id_parceiro" => $_SESSION['id'],
                        "nome_parceiro" => $_SESSION['nome'],
                        "id_negociacao" => $idNegociacao,
                        "atendimento" => $atendimento,
                        "tempo_entrega" => $tempoEntrega,
                        "observacao" => $observacao,
                        "titulo" => $titulo,
                        "data_hora" => date('Y-m-d H:i:s')
                    )
                )
            );
           
            $classeRelation = new RelationshipBusinessPartnerRepository;
            $retornoAdicionar = $classeRelation->updateRelationshipBusiness($idVendedor, $dados);
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

    private function atualizarMedia(string $tipo, string $id){
        try {
            if(empty($tipo) || empty($id)){
                throw new Exception('Parâmetros inválidos para a função atualizarMedia');
            }

            switch (true) {
                case $tipo == 'PARCEIRO':
                    $classeRelation = new RelationshipBusinessPartnerRepository;
                    $retornoRelacao = json_decode($classeRelation->getRelationBusiness(['id_parceiro' => $id]));
                    if($retornoRelacao === false){
                        throw new Exception($classeRelation->mensagem);
                    }
                    if($classeRelation->encontrados < 1){
                        throw new Exception('Id: '.$id.' não encontrado na tabela Relacao');
                    }
                    if(isset($retornoRelacao[0]->avaliacoes_vendas)){
                        $somaAtendimento = 0;
                        $somaTempoEntrega = 0;
                        $contador = 0;
                        foreach ($retornoRelacao[0]->avaliacoes_vendas as $key => $value) {
                            $somaAtendimento += $value->atendimento;
                            $somaTempoEntrega += $value->tempo_entrega;
                            $contador++;
                        }
                    }
                    $mediaEntrega = (float) $somaTempoEntrega/$contador;
                    $mediaAtendimento = (float) $somaAtendimento/$contador;
                    $classificacao = 'BRONZE';
                    if(($mediaEntrega + $mediaAtendimento)/2 >= 3){
                        $classificacao = 'PRATA';
                        if(($mediaEntrega + $mediaAtendimento)/2 >= 4){
                            $classificacao = 'OURO'; 
                        }
                    }
                    $dados = array(
                        'media_entrega' => (float) $mediaEntrega,
                        'media_atendimento' => (float) $mediaAtendimento,
                        'classificacao' => $classificacao
                    );
                    $retornoAdd = $classeRelation->updateRelationshipBusiness($id, $dados);
                    if($retornoAdd === false){
                        throw new Exception($classeRelation->mensagem);
                    }
                    break;

                case $tipo == 'PRODUTO':
                    $retornoProduto = json_decode($this->classeProduct->getProduct($id));
                    if($retornoProduto === false){
                        throw new Exception($this->classeProduct->mensagem);
                    }
                    if($this->classeProduct->encontrados < 1){
                        throw new Exception('Id: '.$id.' não encontrado na tabela Produtos');
                    }
                    if(isset($retornoProduto[0]->opinioes)){
                        $somaEstrela = 0;
                        $contador = 0;
                        foreach ($retornoProduto[0]->opinioes as $key => $value) {
                            $somaEstrela += $value->estrela;
                            $contador++;
                        }
                    }
                    $retornoAdd = $this->classeProduct->update($id, ['media' => (float) $somaEstrela/$contador]);
                    if($retornoAdd === false){
                        throw new Exception($this->classeProduct->mensagem);
                    }
                    break;                
                default:
                    throw new Exception('Tipo não esperado na função atualizarMedia');
                    break;
            }
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }
}
