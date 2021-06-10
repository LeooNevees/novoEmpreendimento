<?php
include 'sistema.php';
session_start();
$teste = $_SERVER;
if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/search/') == true){
    $_SESSION['redirecionamento'] = $_SERVER['HTTP_REFERER'];
}
?>
<html>
    <head>
        <link href="/novoEmpreendimento/css/style.css" rel="stylesheet" type="text/css"/>
        <script src="/novoEmpreendimento/js/cadastro_parceiro.js"></script>
        <script src="/novoEmpreendimento/js/sistema.js"></script>
    </head>

    <body>
        <div class="fundo1">
            <div class="telaLogin">
                <form>
                    <img src="/novoEmpreendimento/img/logoiPeca.png" class="iconeLogin">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 form-floating mb-3">
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="">
                                <label for="floatingInput">Usuário</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 form-floating mb-3">
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="">
                                <label for="floatingInput">Senha</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <?php
                                if (!empty($_SESSION['nao_autenticado'])) {
                                    ?>
                                    <div class="alert alert-danger buscaUsuario" role="alert">
                                        <?php echo $_SESSION['nao_autenticado']; ?>
                                    </div>
                                <?php }unset($_SESSION['nao_autenticado']); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12" id="botao">
                                <div id="botaoAcessar">
                                    <button class="btn btn-outline-success btn-block" type="button" onclick="validarLogin()">Acessar</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <button type="button" id="btnCadastroPareciro" class="btn btn-link btn-block" style="color: #800000; text-decoration: none;" value="true" onclick="cadastroParceiro()">Criar Conta</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>