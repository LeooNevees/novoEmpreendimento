<?php
include_once '/var/www/html/novoEmpreendimento/classes/repository/NegotiationRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/BusinessPartnerRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/ProductsRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/GroupsRepository.php';
/**
 * Classe para a busca dos dados Dashboard
 *
 * @author leoneves
 */

class Dashboard{

    public $mensagem;

    public function gerarDashboard(){
        try {
            $classeNegotiation = new NegotiationRepository;
            $retornoVendas = json_decode($classeNegotiation->getNegotiation(['status_negociacao' => 'ABERTO'], 1000));
            if ($retornoVendas === false) {
                throw new Exception($classeNegotiation->mensagem);
            }

            $vendas = [];
            $vendasDiarias = [];
            $vendedor = [];
            $comprador = [];
            $produtos = [];

            foreach ($retornoVendas as $key => $value) {
                $data = explode('-', $value->data_negociacao);
                $dia = (int) $data[2];
                $mes = (int) $data[1];
                $ano = (int) $data[0];

                if ($ano != 2021) {
                    continue;
                }

                if ($mes == (int) date('m')) {
                    $vendasDiarias[$dia]['quantidade'] = isset($vendasDiarias[$dia]['quantidade']) ? $vendasDiarias[$dia]['quantidade'] + 1 : 1;
                }

                $vendas[$mes]['quantidade'] = isset($vendas[$mes]['quantidade']) ? (int) $vendas[$mes]['quantidade'] + (int) $value->quantidade_negociada : (int) $value->quantidade_negociada;
                $vendedor[$value->id_vendedor]['quantidade'] = isset($vendedor[$value->id_vendedor]) ? $vendedor[$value->id_vendedor]['quantidade'] + 1 : 1;
                $comprador[$value->id_comprador]['quantidade'] = isset($comprador[$value->id_comprador]) ? $comprador[$value->id_comprador]['quantidade'] + 1 : 1;
                $produtos[$value->id_produto]['quantidade'] = isset($produtos[$value->id_produto]) ? $produtos[$value->id_produto]['quantidade'] + 1 : 1;
            }

            $top5Vendedor = $this->buscarParceiro($vendedor);
            if($top5Vendedor === false){
                throw new Exception($this->mensagem);
            }

            $top5Comprador = $this->buscarParceiro($comprador);
            if($top5Comprador === false){
                throw new Exception($this->mensagem);
            }

            $top5Produtos = $this->buscarProdutos($produtos);
            if($top5Produtos === false){
                throw new Exception($this->mensagem);
            }

            $topGrupos = $this->buscarGrupos($produtos);
            if($topGrupos === false){
                throw new Exception($this->mensagem);
            }

            $retorno = array(
                'status' => 'SUCESSO',
                'mensagem' => 'BUSCA DE VENDAS OBTIDA COM SUCESSO',
                'vendas_mensais' => $vendas,
                'vendas_diarias' => $vendasDiarias,
                'top_vendedor' => $top5Vendedor,
                'top_comprador' => $top5Comprador,
                'top_grupos' => $topGrupos,
                'top_produtos' => $top5Produtos,
                'array_mes' => $this->getMonth(),
                'array_dia' => $this->getDay()
            );

            return $retorno;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function getMonth(){
        try {
            $newData = [];
            for ($i = 1; $i <= (int) date('m'); $i++) {
                switch ($i) {
                    case 1:
                        $newData[$i] = 'Janeiro';
                        break;
                    case 2:
                        $newData[$i] = 'Fevereiro';
                        break;
                    case 3:
                        $newData[$i] = 'Mar??o';
                        break;
                    case 4:
                        $newData[$i] = 'Abril';
                        break;
                    case 5:
                        $newData[$i] = 'Maio';
                        break;
                    case 6:
                        $newData[$i] = 'Junho';
                        break;
                    case 7:
                        $newData[$i] = 'Julho';
                        break;
                    case 8:
                        $newData[$i] = 'Agosto';
                        break;
                    case 9:
                        $newData[$i] = 'Setembro';
                        break;
                    case 10:
                        $newData[$i] = 'Outubro';
                        break;
                    case 11:
                        $newData[$i] = 'Novembro';
                        break;
                    case 12:
                        $newData[$i] = 'Dezembro';
                        break;
                    default:
                        break;
                }
            }
            return $newData;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function getDay(){
        try {
            for ($d = 1; $d <= (int) date('d'); $d++) {
                $newDay[$d] = $d;
            }
            return $newDay;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function buscarParceiro($array){
        try {
            if (!count($array)) {
                throw new Exception('Par??metros inv??lidos para a fun????o buscarParceiro');
            }

            //Limitando apenas aos 5 primeiros Parceiros (TOP 5), ordenando de dor MAIOR para o MENOR
            arsort($array);

            $classeParceiro = new BusinessPartnerRepository;
            $newDados = [];
            $contador = 0;
            foreach ($array as $key => $value) {
                if ($contador >= 5) {
                    break;
                }

                $retParceiro = json_decode($classeParceiro->getBusinessPartner(['_id' => new MongoDB\BSON\ObjectID($key)]));
                if ($retParceiro === false) {
                    throw new Exception($classeParceiro->mensagem);
                }
                $newDados[$key] = array(
                    'nome_parceiro' => isset($retParceiro[0]->nome_fantasia) ? $retParceiro[0]->nome_fantasia : $retParceiro[0]->nome_completo,
                    'quantidade' => $value['quantidade']
                );
                $contador++;
            }
            return $newDados;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function buscarProdutos($params){
        try {
            if (!count($params)) {
                throw new Exception('Par??metros inv??lidos para a fun????o buscar Produto');
            }
            arsort($params);
            $classeProduto = new ProductsRepository;
            $newDados = [];
            $contador = 0;
            foreach ($params as $key => $value) {
                if ($contador >= 5) {
                    break;
                }

                $retProduto = json_decode($classeProduto->getProduct($key));
                if ($retProduto === false) {
                    throw new Exception($classeProduto->mensagem);
                }
                $newDados[$key] = array(
                    'nome_produto' => isset($retProduto[0]->nome) ? $retProduto[0]->nome : '',
                    'quantidade' => $value['quantidade'],
                    'grupo' => isset($retProduto[0]->grupo) ? $retProduto[0]->grupo : ''
                );
                $contador++;
            }
            return $newDados;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function buscarGrupos(){
        try {
            $classeGrupos = new GroupsRepository;
            $retGrupos = json_decode($classeGrupos->getGroups(['situacao' => 'ATIVO']));
            if ($retGrupos === false) {
                throw new Exception($classeGrupos->mensagem);
            }

            $newGrupos = [];
            foreach ($retGrupos as $key => $value) {
                $newGrupos[$value->id] = array(
                    'nome' => $value->nome,
                    'quantidade' => 0
                );
            }

            $classeProduto = new ProductsRepository;
            $retProd = json_decode($classeProduto->getProductLimit(['status' => 'ATIVO'], 1000));
            foreach ($retProd as $produto) {
                $newGrupos[$produto->grupo]['quantidade'] = $newGrupos[$produto->grupo]['quantidade'] + $produto->quantidade_vendida;
            }
            return $newGrupos;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }
}
