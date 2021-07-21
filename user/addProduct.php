<?php
include_once '/var/www/html/novoEmpreendimento/sistema.php';
include_once '/var/www/html/novoEmpreendimento/classes/Login.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/GroupsRepository.php';

try {
    $classeLogin = new Login;
    $retornoSession = $classeLogin->validarExistenciaSession();
    if($classeLogin->getMensagem() != 'EXISTE'){
        header('location:/novoEmpreendimento/login.php');
        return false;
    }

    $classeGroup = new GroupsRepository;
    $retornoGroup = $classeGroup->getGroups(['situacao' => 'ATIVO']);
    if($retornoGroup === false){
        throw new Exception('Erro ao buscar os Grupos de itens');
    }
    $retornoDecod = json_decode($retornoGroup);
} catch (Exception $ex) {
    echo $ex->getMessage();
    return false;
}
?>
<html>
    <head>
        <title>Ipeças - Faça seu pedido</title>
        <!--JS-->
        <script src="/novoEmpreendimento/js/addProduct.js" type="text/javascript"></script>
    </head>
    <body>
        <?php
        if (!(include '/var/www/html/novoEmpreendimento/navbar.php')) {
            echo 'Erro ao carregar o NavBar';
            return false;
        }?>
        <div class="formularioCadastroPn">
            <form id="myForm">
                <div class="col-12">
                    <a href="#" class="logo"><img src="/novoEmpreendimento/img/temp.png" class="img"> Cadastrar Produto <font style=" color: #DF0101;">Ipeças</font></a>
                </div>

                <div class="row" style="margin-top: 1%;">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="nome_produto" name="nome_produto" placeholder="Nome Produto">
                            <label for="floatingInput">Nome Produto</label>
                        </div><br>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="quantidade_produto" name="quantidade_produto" placeholder="Quantidade Disponível" onkeydown="return validarNumero(this, 1000)">
                            <label for="floatingInput">Qtde Disponível Estoque</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-12" >
                        <textarea class="form-control" id="descricao_produto" name="descricao_produto" rows="3" placeholder="Descrição Produto"></textarea>
                    </div>
                </div>

                <div class="row" style="margin-top: 3%;">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="cor_produto" name="cor_produto" placeholder="Cor Produto" list="listaCor">
                            <label for="floatingInput">Cor </label>
                        </div><br>
                        <datalist id="listaCor">
                            <option>Branco</option>
                            <option>Preto</option>
                            <option>Cinza</option>
                            <option>Amarelo</option>
                            <option>Laranja</option>
                            <option>Vermelho</option>
                            <option>Roxo</option>
                            <option>Azul</option>
                            <option>Verde</option>
                        </datalist>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-floating">
                            <select class="form-select" id="tipo" name="tipo" aria-label="Floating label select example" placeholder="Tipo">
                                <option value="">Selecione o Tipo</option>
                                <option value="NOVO">Novo</option>
                                <option value="USADO">Usado</option>
                            </select>
                            <label for="floatingSelectGrid">Tipo</label>
                        </div><br>
                    </div>
                </div>

                <div class="row">
                <div class="col-md-12 col-lg-6">
                        <div class="form-floating">
                            <select class="form-select" id="grupo" name="grupo" aria-label="Floating label select example" placeholder="Grupo">
                                <option value="">Selecione o Grupo</option>
                                <?php
                                foreach ($retornoDecod as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo ucfirst(mb_strtolower($value->nome)) ?></option>
                                <?php } ?>
                            </select>
                            <label for="floatingSelectGrid">Grupo</label>
                        </div><br>
                    </div>

                    <div class="col-md-12 col-lg-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="valor" name="valor" placeholder="Valor" onInput="mascaraMoeda(event);">
                            <label for="floatingInput">Valor </label>
                        </div><br>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-floating">
                            <select class="form-select" id="promocao" name="promocao" aria-label="Floating label select example" onchange="ativarPromocao()" placeholder="Promoção">
                                <option value="NAO">Não</option>
                                <option value="SIM">Sim</option>
                            </select>
                            <label for="floatingSelectGrid">Promoção</label>
                        </div><br>
                    </div>
                    <div class="col-md-12 col-lg-6" style="display: none;" id="div_porc_desconto">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="porcentagem_desconto" name="porcentagem_desconto" placeholder="Porcentagem Desconto" onkeydown="return validarNumero(this, 100)" maxlength="3" value="0">
                            <label for="floatingInput">Porcentagem de Desconto</label>
                        </div><br>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="formFileSm" class="form-label">Selecionar imagens (png, jpeg ou jpg)</label>
                            <input class="form-control form-control-sm" id="imagens_produto" name="imagens_produto[]" type="file" multiple="multiple">
                        </div>
                    </div>
                </div><br>
                
                <div id="botao" class="row g-2">
                    <div class="col-md-12 col-lg-4" id="botaoCancelar">
                        <button class="btn btn-outline-danger btn-block" type="button" onclick="cancelar()">Cancelar</button>
                    </div>
                    <div class="col-md-12 col-lg-8" id="botaoCadastrar">
                        <button class="btn btn-success btn-block" type="button" onclick="cadastrarProduto()">Cadastrar</button>
                    </div>
                </div>
                
                <input type="hidden" id="uf" name="uf">
                <input type="hidden" id="ibge" name="ibge">
            </form>
        </div>
    </body>
</html>



