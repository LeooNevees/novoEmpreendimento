<?php
include_once '/var/www/html/novoEmpreendimento/classes/Card.php';
/**
 * Classe para a criação de cards para os produtos
 *
 * @author leoneves
 */

class CardProductsUser extends Card{
    
    function __construct($titulo) {
        $this->setTitulo($titulo);
    }
    
    public function gerarDadosEstrutura($dados = ''){
        try {
            if(!is_string($this->getMensagem()) && !is_string($dados)){
                throw new Exception('Retorno informação de Cards inesperado');
            }   
            $retornoDecod = json_decode($this->getMensagem());
            if(!empty($dados)){
                $retornoDecod = json_decode($dados);
            }
    
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
                $onclickInativarItem = "onclick=editarItem('".$id."')";
                $nome = ucwords(mb_strtolower($registros->nome));
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
                                ."<div class='card shadow-sm card-max'>"
                                    ."<img class='card-max' src='$imagem'>"
                                    ."<div class='card-footer text-muted'>"
                                        ."<p class='card-text'>$nome</p>"
                                        ."<p class='card-font-valor-desconto'>De $valorSemDesconto por</p>"
                                        ."<p><font class='card-font-valor'>$valor &nbsp;</font><font class='card-font-promocao'> $porcPromocao% OFF</font></p>"
                                        ."<div class='d-flex justify-content-between align-items-center'>"
                                        ."<small class='color-vermelho'>$tipo</small>"
                                        ."<i class='far fa-trash-alt' style='cursor:pointer; color:red' $onclickInativarItem></i>"
                                        ."</div>"
                                    ."</div>"
                                ."</div>"
                            ."</div>";
                    continue;
                }
                
                $array[] = "<div class='col'>"
                                ."<div class='card-max card shadow-sm'>"
                                    ."<img class='tamanho-imagem-card card-max' src='$imagem'>"
                                    ."<div class='card-footer text-muted'>"
                                        ."<p class='card-text'>$nome</p>"
                                        ."<p class='card-font-valor'>$valor</p>"
                                        ."<div class='d-flex justify-content-between align-items-center'>"
                                        ."<small class='color-vermelho'>$tipo</small>"
                                        ."<i class='fas fa-edit' style='cursor:pointer;'></i>"
                                        ."</div>"
                                    ."</div>"
                                ."</div>"
                            ."</div>";
            }

            $array[] = "<div class='col'>"
                            ."<div class='card shadow-sm card-max' style='widht:100%; height:100%; cursor:pointer;' onclick='adicionar_produto()'>"
                                ."<img src='/novoEmpreendimento/img/mais.png' class='dist-adicionar'>"
                                ."<span class='text-center espaco-5' style='color:green; font-weight:bold;'>Adicionar Produto</span>"
                            ."</div>"
                        ."</div>";
            $this->setMensagem($array);
            return true;
        } catch (Exception $ex) {
            $this->setMensagem($ex->getMessage());
            return false;
        }
    }
    
}
