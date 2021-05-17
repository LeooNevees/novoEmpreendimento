<?php
//include '/var/www/html/novoEmpreendimento/classes/Card.php';
/**
 * Classe para a criação de cards para os Vendedores
 *
 * @author leoneves
 */

class CardSalesman extends Card{
    
    function __construct($titulo) {
        $this->setTitulo($titulo);
    }
    
    // ARRAY $dados = Informações que pretende buscar no banco de dados
    protected function buscarDados($dados = [], $limite = 3){
        try {
            if(!is_array($dados) || !is_numeric($limite)){
                throw new Exception('Parâmetros inválidos');
            }
            $conexao = new Xmongo();
            $requisicao = array(
                'tabela' => 'parceiroNegocio',
                'acao' => 'pesquisar',
                'limit' => $limite
            );

            if(count($dados) > 0){
                $requisicao['dados'] = $dados;
            }

            $retorno = $conexao->requisitar($requisicao);
            if ($retorno === false) {
                throw new Exception($conexao->getMensagem());
            }

            if($conexao->getEncontrados() < 1){
                throw new Exception('Nenhum registro encontrado');
            }
            
            $this->setMensagem($conexao->getMensagem());
            $this->setEncontrados($conexao->getEncontrados());
            
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
            $contador = 0;
            foreach ($retornoDecod as $registros) {
                $contador ++;
                $variavelId = $registros->_id;
                foreach($variavelId as $value){
                    $id = $value;
                }
                $onclick = "onclick=abrirCard('".$id."')";
                $nomeCompleto = isset($registros->nome_completo) ? ucwords(mb_strtolower($registros->nome_completo)): '';
                $nomeFantasia = isset($registros->nome_fantasia) ? $registros->nome_completo : '';
                $sexo = isset($registros->sexo) ? $registros->sexo : '';
                $cidade = isset($registros->cidade) ? $registros->cidade : '';
                $uf = isset($registros->uf) ? $registros->uf : '';
                $foto = isset($registros->foto) && file_exists('/var/www/html'.$registros->foto) ? $registros->foto : '/novoEmpreendimento/img/imagemNotFound.png';
                $vendas = isset($registros->vendas) ? $registros->vendas : '0';
                
                $array[] = "<div class='col'>"
                                ."<div class='card shadow-sm' style='cursor:pointer;' $onclick>"
                                    ."<img class='tamanho-imagem-card' src='$foto'>"
                                    ."<div class='card-footer text-muted'>"
                                        ."<p class='card-text'>".$contador."ª Posição</p>"
                                        ."<p class='card-font-valor'>".$nomeCompleto."</p>"
                                        ."<div class='d-flex justify-content-between align-items-center'>"
                                        ."<small class='color-vermelho'>".$cidade."-".$uf."</small>"
                                            ."<small class='text-muted'>".$vendas." Vendas</small>"
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
    
}
