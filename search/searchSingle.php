<?php
$produto = isset($_GET['product']) ? filter_input(INPUT_GET, 'product', FILTER_SANITIZE_STRING) : '';
$parceiroNegocio = isset($_GET['business']) ? filter_input(INPUT_GET, 'business', FILTER_SANITIZE_STRING) : '';

include_once '/var/www/html/novoEmpreendimento/sistema.php';
include_once '/var/www/html/novoEmpreendimento/classes/SingleProduct.php';
include_once '/var/www/html/novoEmpreendimento/classes/SingleBusinessPartner.php';
?>

<html>

<head>
    <meta charset="utf-8">
    <title>Ipeças - Faça seu pedido</title>
    <!-- JS -->
    <script src="/novoEmpreendimento/js/searchSingle.js" type="text/javascript"></script>
    <script src="/novoEmpreendimento/js/sistema.js" type="text/javascript"></script>

    <!-- CSS -->
    <link href="/novoEmpreendimento/css/product.css" rel="stylesheet">
</head>

    <body class="cor-body">
        <?php
            try {
                if (!(include __DIR__ . '/../navbar.php')) {
                    echo 'Erro ao carregar o Navbar';
                }
        
                switch (true) {
                    case !empty($produto):
                        $classeSingleProdutc = new SingleProduct;
                        $retorno = $classeSingleProdutc->gerarEstrutura($produto);
                        if($retorno === false){
                            throw new Exception($classeSingleProdutc->mensagem);
                        }
                        echo $retorno;
                        break;
        
                    case !empty($parceiroNegocio):
                        $classeSingleBusiness = new SingleBusinessPartner;
                        $retorno = $classeSingleBusiness->gerarEstrutura($parceiroNegocio);
                        if($retorno === false){
                            throw new Exception($classeSingleBusiness->mensagem);
                        }
                        echo $retorno;
                        break;
                    
                    default:
                        echo 'Parâmetro não reconhecido. Por favor refaça o procedimento';
                        break;
                } 
            } catch (Exception $ex) {
                trigger_error($ex->getMessage());
                echo 'Erro: '.$ex->getMessage().'. Por favor refaça o procedimento';
                return false;
            }       
        ?> 
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Avaliações</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="myForm" name="myForm">
                        <input type="hidden" id="id_produto" name="id_produto" value="<?php echo !empty($produto) ? $produto : '' ?>">
                        <input type="hidden" id="id_negociacao" name="id_negociacao" value="">
                            <h6 class="text-center negrito color-vermelho">Produto</h6>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Título: </label>
                                <input type="text" class="form-control" id="titulo_avaliacao" name="titulo_avaliacao">
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Estrelas: </label>
                                <select id="estrelas_avaliacao" name="estrelas_avaliacao" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Descrição:</label>
                                <textarea class="form-control" id="descricao_avaliacao" name="descricao_avaliacao"></textarea>
                            </div>
                            
                            <hr class="negrito cor-letra-preto">
                            
                            <h6 class="text-center negrito color-vermelho">Vendedor</h6>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Título: </label>
                                <input type="text" class="form-control" id="titulo_vendedor" name="titulo_vendedor">
                            </div>

                            <div class="mb-3">
                                <label class="col-form-label">Atendimento: </label>
                                <select id="atendimento" name="atendimento" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="col-form-label">Tempo Entrega: </label>
                                <select id="tempo_entrega" name="tempo_entrega" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Observação:</label>
                                <textarea class="form-control" id="observacao_vendedor" name="observacao_vendedor"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="cadastrarAvaliacao()">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
   
</html>