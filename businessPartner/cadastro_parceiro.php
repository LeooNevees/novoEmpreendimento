<?php
session_start();
include '/var/www/html/novoEmpreendimento/sistema.php';
?>
<html>
    <head>
        <!--CSS-->
        <link href="/novoEmpreendimento/css/style.css" rel="stylesheet" type="text/css"/>
        <script src="/novoEmpreendimento/js/cadastro_parceiro.js"></script>
        <script src="/novoEmpreendimento/js/sistema.js"></script>

    </head>

    <body>
    <div class="fundo1">
            <div class="formularioCadastroPn">
                <form id="myForm">
                    <div class="col-12">
                        <a href="#" class="logo"><img src="/novoEmpreendimento/img/temp.png" class="img"> Criar a sua conta no <font style=" color: #DF0101;">Ipeças</font></a>
                    </div>

                    <div class="row" style="margin-top: 1%;">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" placeholder="Insira seu nome completo">
                                <label for="floatingInput">Nome completo</label>
                            </div><br>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nomeFantasia" name="nomeFantasia" placeholder="Insira seu nome completo">
                                <label for="floatingInput">Nome Fantasia</label>
                            </div><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Insira seu e-mail">
                                <label for="floatingInput">E-mail</label>
                            </div><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Insira sua senha">
                                <label for="floatingInput">Senha</label>
                                <span><font style="color: red; font-size: 70%; top: -10%;">Necessário ter no minímo 6 caracteres</font></span>
                            </div><br>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="confSenha" name="confSenha" placeholder="Insira seu nome completo">
                                <label for="floatingInput">Confirmar Senha</label>
                            </div><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-floating">
                                <select class="form-select" id="sexo" name="sexo" aria-label="Floating label select example">
                                    <option value ="" selected>Selecione o sexo</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Feminimo</option>
                                </select>
                                <label for="floatingSelectGrid">Sexo</label>
                            </div><br>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="documento" name="documento" placeholder="Insira seu CPF/CNPJ" onfocus="javascript: retirarFormatacao(this);" onblur="javascript: formatarCampo(this);" maxlength="14">
                                <label for="floatingInput">CPF/CNPJ</label>
                            </div><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="cep" name="cep" onblur="maskCep(), pesquisacep(this.value);" placeholder="Insira seu CEP">
                                <label for="floatingInput">CEP</label>
                            </div><br>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="telefone" name="telefone" onkeypress="mask(this, mphone);" placeholder="Insira seu telefone">
                                <label for="floatingInput">Telefone</label>
                            </div><br>
                        </div>
                    </div>

                    <div id="oculto">    
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="rua" name="rua" placeholder="Insira a rua">
                                    <label for="floatingInput">Rua</label>
                                </div><br>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="numEndereco" name="numEndereco" placeholder="Insira o n?mero do endere?o">
                                    <label for="floatingInput">N°</label>
                                </div><br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Insira o bairro">
                                    <label for="floatingInput">Bairro</label>
                                </div><br>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Insira a cidade">
                                    <label for="floatingInput">Cidade</label>
                                </div><br>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="formFileSm" class="form-label">Selecionar Foto Perfil (png, jpeg ou jpg)</label>
                            <input class="form-control form-control-sm" id="imagens_parceiro" name="imagens_parceiro" type="file">
                        </div>
                    </div>
                </div><br>

                    <div id="botao" class="row g-2">
                        <div class="col-md-12 col-lg-4" id="botaoCancelar">
                            <button class="btn btn-outline-danger btn-block" type="button" onclick="cancelar()">Cancelar</button>
                        </div>
                        <div class="col-md-12 col-lg-8" id="botaoCadastrar">
                            <button class="btn btn-success btn-block" type="button" onclick="cadastrar_parceiro()">Cadastrar</button>
                        </div>
                    </div>

                    <input type="hidden" id="uf" name="uf">
                    <input type="hidden" id="ibge" name="ibge">
                </form>
            </div>
        </div>
    </body>
</html>
