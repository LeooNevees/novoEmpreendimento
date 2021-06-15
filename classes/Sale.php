<?php
include_once __DIR__.'/repository/ProductsRepository.php';
include_once __DIR__.'/repository/RelationshipBusinessPartnerRepository.php';
include_once __DIR__.'/repository/NegotiationRepository.php';
include_once __DIR__.'/Login.php';

/**
 * Classe para efetivar a compra dos produtos
 *
 * @author leoneves
 */
class Sale{
    public $mensagem;
    public $erro;

    public function gerarCompraProduto($idProduto, $quantidade){
        try {
            if(empty($idProduto) || empty($quantidade)){
                throw new Exception('Parâmetros inválidos na função Gerar Compra Produto');
            }

            if(!$retornoSession = $this->validarSession()){
                $this->erro = 'LOGIN';
                throw new Exception($this->mensagem);                
            }

            if(!$retornoProdutos = $this->alterarProdutos($idProduto, $quantidade)){
                $this->erro = 'PRODUTO';
                throw new Exception($this->mensagem);
            }

            if(!$retornoAlteraRelacaoVendedor = $this->alterarRelacaoParceiro($retornoProdutos['id_vendedor'], 'VENDAS')){
                throw new Exception("Erro ao alterar Relacao Parceiro na funcao Gerar Venda Produto para o Id Parceiro ".$retornoProdutos['id_vendedor']);
            }

            if(!$retornoAlteraRelacaoComprador = $this->alterarRelacaoParceiro($_SESSION['id'], 'COMPRAS')){
                throw new Exception("Erro ao alterar Relacao Parceiro na funcao Gerar Compra Produto para o Id Parceiro ".$_SESSION['id']);
            }
            
            $dadosInserir = array(
                'id_produto' => $idProduto,
                'quantidade_negociada' => $quantidade,
                'id_vendedor' => $retornoProdutos['id_vendedor'],
                'id_comprador' => $_SESSION['id'],
                'valor_negociado' => $retornoProdutos['valor']
            );
            if(!$retornoNegociacao = $this->inserirNegociacao($dadosInserir)){
                throw new Exception("Erro ao inserir Negociacao na funcao Gerar Compra Produto para o Id Produto ".$idProduto);
            }

            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function validarSession(){
        try {
            $classeSession = new Login;
            $retornoSession = $classeSession->validarExistenciaSession();
            if($classeSession->getMensagem() == 'NAO EXISTE'){
                throw new Exception('Necessário fazer Login para realizar a compra');
            }
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }
    
    private function alterarProdutos($idProduto, $quantidade){
        try {
            if(empty($idProduto) || empty($quantidade)){
                throw new Exception('Parâmetros inválidos na função Analise Produto');
            }
            $classeProduto = new ProductsRepository;
            $retornoProduto = $classeProduto->getProduct($idProduto);
            $auxDecod = json_decode($retornoProduto);
            $retornoDecod = $auxDecod[0];
            if($retornoProduto === false){
                throw new Exception('Erro ao tentar buscar o produto. Por favor refaça o procedimento');
            }
            if($classeProduto->encontrados == 0){
                throw new Exception('Nenhum produto encontrado com o Id '.$idProduto);
            }
            if($retornoDecod->quantidade_estoque < $quantidade){
                throw new Exception("Quantidade solicitada indisponível no estoque");
            }

            $newQuantidadeEstoque = $retornoDecod->quantidade_estoque - $quantidade;
            $newQuantidadeVendida = $retornoDecod->quantidade_vendida + $quantidade;
            $retornoUpdate = $classeProduto->updateProducts($idProduto, $newQuantidadeEstoque, $newQuantidadeVendida);
            if($retornoUpdate === false){
                throw new Exception($classeProduto->mensagem);   
            }
            if($classeProduto->afetados <= 0){
                throw new Exception($classeProduto->mensagem);
            }

            return array(
                'erro' => 0,
                'id_vendedor' => $retornoDecod->id_vendedor,
                'valor' => $retornoDecod->valor
            );
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function alterarRelacaoParceiro($idParceiro, $tipo){
        try {
            if(empty($idParceiro) || empty($tipo)){
                throw new Exception('Parâmetros inválidos na função Alterar Relacao Parceiro');
            }

            $classeRelation = new RelationshipBusinessPartnerRepository;
            $retornoGetParceiro = $classeRelation->getRelationBusiness(['id_parceiro' => $idParceiro]);
            $auxRet = json_decode($retornoGetParceiro);
            $retDecod = $auxRet[0];
            if($retornoGetParceiro === false){
                throw new Exception("Erro ao encontrar o parceiro na tabela Relacao Parceiro Negocio");
            }

            switch(true) {
                case mb_strtoupper($tipo) == 'VENDAS':
                    $newVendas = $retDecod->vendas + 1;
                    $dados = ['vendas' => $newVendas];
                    $retornoUpdateParceiro = $classeRelation->updateRelationshipBusiness($idParceiro, $dados);
                    break;

                case mb_strtoupper($tipo) == 'COMPRAS':
                    $newCompras = $retDecod->compras + 1;
                    $dados = ['compras' => $newCompras];
                    $retornoUpdateParceiro = $classeRelation->updateRelationshipBusiness($idParceiro, $dados);
                    break;
                    
                default:
                    throw new Exception("Tipo inválido na funcao Alterar Relacao Parceiro");
                    break;
            }
            
            if($retornoUpdateParceiro === false){
                throw new Exception($classeRelation->mensagem);
            }

            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }

    private function inserirNegociacao($dados){
        try {
            $array = ['id_produto', 'quantidade_negociada', 'id_vendedor', 'id_comprador', 'valor_negociado'];
            foreach ($array as $value) {
                if(!isset($dados[$value]) || empty($dados[$value])){
                    throw new Exception('Parâmetros inválidos para a função Inserir Negociacao');
                }
            }
            $classeNegotiation = new NegotiationRepository;
            $dados['status_negociacao'] = 'ABERTO';
            $dados['data_negociacao'] = date('Y-m-d');
            $dados['data_prevista_entrega'] = date('Y-m-d', strtotime('+1 days'));
            $retornoInsert = $classeNegotiation->insertNegotiation($dados);
            if($retornoInsert === false){
                throw new Exception('Erro ao Inserir Negociacao para o produto '.$dados['id_produto']);
            }
            return true;
        } catch (Exception $ex) {
            $this->mensagem = $ex->getMessage();
            return false;
        }
    }
}
