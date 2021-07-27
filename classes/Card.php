<?php
include_once '/var/www/html/novoEmpreendimento/classes/repository/ProductsRepository.php';
/**
 * Classe para a base da criação de cards
 *
 * @author leoneves
 */
class Card {
    protected $encontrados;
    protected $mensagem;
    protected $titulo;
    
    function __construct($titulo) {
        $this->setTitulo($titulo);
    }

    public function gerarEstrutura($dados = [], $limite = 3){
        try {
            $retorno = $this->buscarDados($dados, $limite);
            
            if($retorno === false){
                throw new Exception($this->getMensagem());
            }

            if($this->getEncontrados() < 1){
                $retorno = "<div class='album py-5 bg-index'>"
                                ."<h4 class='text-center card-titulo'>Nenhum Produto Encontrado</h4>"
                                ."<div class='container'>"
                                    ."<div class='row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3'>"
                                    ."<div class='col'>"
                                    ."<div class='card shadow-sm card-max' style='widht:100%; height:100%; cursor:pointer;' onclick='adicionar_produto()'>"
                                        ."<img src='/novoEmpreendimento/img/mais.png' class='dist-adicionar'>"
                                        ."<span class='text-center espaco-5' style='color:green; font-weight:bold;'>Adicionar Produto</span>"
                                    ."</div>"
                                ."</div>"
                                    ."</div>"
                                ."</div>"
                            ."</div>";
                $this->setMensagem($retorno);
                return true;
            }

            $retornoEstrutura = $this->gerarDadosEstrutura();
            
            if($retornoEstrutura === false){
                throw new Exception($this->getMensagem());
            }
            
            if(count($this->getMensagem()) < 1){
                throw new Exception('Erro ao gerar a estrutura');
            }
            
            $conteudo = implode("\n", $this->getMensagem());
            
            $retorno = "<div class='album py-5 bg-index'>"
                        ."<h4 class='text-center card-titulo'>".$this->getTitulo()."</h4>"
                        ."<div class='container'>"
                            ."<div class='row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3'>"
                            .$conteudo
                            ."</div>"
                        ."</div>"
                    ."</div>";
            
            $this->setMensagem($retorno);
            return true;
            
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }
    
    // ARRAY $dados = Informações que pretende buscar no banco de dados
    protected function buscarDados($dados = [], $limite = 3){
        try {
            if(!is_array($dados) || !is_numeric($limite)){
                throw new Exception('Parâmetros inválidos');
            }
            $repository = new ProductsRepository;
            $retorno = $repository->getProductLimit($dados, $limite);
            if ($retorno === false) {
                throw new Exception($repository->mensagem);
            }

            if($repository->encontrados < 1){
                return null;
            }
            
            $this->setMensagem($retorno);
            $this->setEncontrados($repository->encontrados);
            
            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }


    protected function gerarDadosEstrutura(){
        try {
            if(!is_string($this->getMensagem())){
                throw new Exception('Retorno informação de Cards inesperado');
            }   

            $retornoDecod = json_decode($this->getMensagem());

            if(!is_array($retornoDecod) || count($retornoDecod) < 1){
                throw new Exception('Nenhum Card encontrado');
            }
         
            $array = [];
            foreach ($retornoDecod as $registros) {
                $variavelId = $registros->_id;
                foreach($variavelId as $value){
                    $id = $value;
                }
                $onclick = "onclick=abrirCard('".$id."')";
                $nome = $registros->nome;
                $descricao = $registros->descricao;
                $cor = isset($registros->cor) ? $registros->cor : '';
                $auxImg = isset($registros->imagens) ? $registros->imagens : '';
                $urlImagem = !empty($auxImg) ? $auxImg->link_1 : '';
                $imagem = !empty($urlImagem) && file_exists('/var/www/html'.$urlImagem) ? $urlImagem : '/novoEmpreendimento/img/imagemNotFound.png';
                $quantidadeEstoque = isset($registros->quantidade_estoque) ? $registros->quantidade_estoque : ''; 
                $quantidadeVendida = isset($registros->quantidade_vendida) ? $registros->quantidade_vendida : '';
                $valor = isset($registros->valor) ? 'R$ '.number_format($registros->valor, 2, ',', '.') : '';
                $tipo = isset($registros->tipo) ? $registros->tipo : '';
                $opinioes = isset($registros->opinioes) ? $registros->opinioes : '';
                $status = isset($registros->status) ? $registros->status : '';
                $dataCadastro = isset($registros->data_cadastro) ? $registros->data_cadastro : '';
                $visualizacao = isset($registros->visualizacao) ? ($registros->visualizacao > 1 ? $registros->visualizacao.' Visualizações' : $registros->visualizacao.' Visualização') : '';

                if(isset($registros->porcentagem_promocao) && !empty($registros->porcentagem_promocao) && $registros->porcentagem_promocao > 0){
                    $porcPromocao = $registros->porcentagem_promocao;
                    $valorSemDesconto = isset($registros->valor) ? 'R$ '.number_format((($registros->valor/100*$porcPromocao)+$registros->valor), 2, ',', '.') : '';
                    $array[] = "<div class='col'>"
                                ."<div class='card shadow-sm' style='cursor:pointer;' $onclick>"
                                    ."<img class='tamanho-imagem-card' src='$imagem'>"
                                    ."<div class='card-footer text-muted'>"
                                        ."<p class='card-text'>".$nome."</p>"
                                        ."<p class='card-font-valor-desconto'>De ".$valorSemDesconto." por</p>"
                                        ."<p class='card-font-valor'>".$valor."</p>"
                                        ."<p class='card-font-promocao'>".$porcPromocao."% OFF</p>"
                                        ."<div class='d-flex justify-content-between align-items-center'>"
                                        ."<small class='color-vermelho'>".$tipo."</small>"
                                            ."<small class='text-muted'>".$visualizacao."</small>"
                                        ."</div>"
                                    ."</div>"
                                ."</div>"
                            ."</div>";
                    continue;
                }
                
                $array[] = "<div class='col'>"
                                ."<div class='card shadow-sm' style='cursor:pointer;' $onclick>"
                                    ."<img class='tamanho-imagem-card' src='$imagem'>"
                                    ."<div class='card-footer text-muted'>"
                                        ."<p class='card-text'>".$nome."</p>"
                                        ."<p class='card-font-valor'>".$valor."</p>"
                                        ."<div class='d-flex justify-content-between align-items-center'>"
                                        ."<small class='color-vermelho'>".$tipo."</small>"
                                            ."<small class='text-muted'>".$visualizacao."</small>"
                                        ."</div>"
                                    ."</div>"
                                ."</div>"
                            ."</div>";
            }
            $this->setMensagem($array);
            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }
    
    public function getEncontrados() {
        return $this->encontrados;
    }

    public function getMensagem() {
        return $this->mensagem;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    protected function setEncontrados($encontrados) {
        $this->encontrados = $encontrados;
    }

    protected function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    protected function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
}
