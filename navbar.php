<?php
include_once '/var/www/html/novoEmpreendimento/classes/Navbar.php';
include_once '/var/www/html/novoEmpreendimento/classes/Login.php';
?>

<link href="/novoEmpreendimento/css/style.css" rel="stylesheet" type="text/css"/>
<script src="/novoEmpreendimento/js/navbar.js" type="text/javascript"></script>

<nav class="navbar navbar-expand-lg navbar-light bg-vermelho">
    <div class="container">
        <a class="navbar-brand ml-auto" href="/novoEmpreendimento/index.php"><img src="/novoEmpreendimento/img/iconePeca.png" style="width: 120px; height: 50px;"></a>
        <div class="input-group mb-1 mr-3 ml-1" style="width: 60%; margin-top: 1%;">
            <input type="text" class="form-control" placeholder="Buscar Peça ou Parceiro de Negócio" aria-label="Recipient's username" aria-describedby="button-addon2" id="buscar" name="buscar">
            <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="searchProdutcBusiness()"><i class="fa fa-search"></i></button>
        </div>

        <div class="flex-shrink-0 dropdown mr-auto">
            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="/novoEmpreendimento/img/user-circle-solid.svg" alt="mdo" width="32" height="32" class="rounded-circle">
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                <?php
                $login = new Login();
                $retornoSession = $login->validarExistenciaSession();
                
                if ($retornoSession === false || $login->getMensagem() == 'NAO EXISTE') {
                    ?>
                    <li><a class="dropdown-item" href="/novoEmpreendimento/login.php">Fazer Login</a></li>
          <?php }else{
                    $idSession = "'".$_SESSION['id']."'"; ?>
                    <li><a class="dropdown-item" href="#">Configuração</a></li>
                    <li><a class="dropdown-item" href="#">Perfil</a></li>
                    <li><a class="dropdown-item" href="#" onclick="myProducts(<?php echo $idSession ?>)">Meus Produtos</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/novoEmpreendimento/logout.php" style="color: red;" onclick="return confirmarLogout()">Logout</a></li>
          <?php } ?>
            </ul>
        </div>

        <button class="navbar-toggler"  type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> 
    </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-light bg-vermelho">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll mr-auto ml-auto" style="--bs-scroll-height: 100px;">
                <?php
                try {
                    $navbar = new Navbar();

                    $funcao = isset($_SESSION['funcao']) ? $_SESSION['funcao'] : '20';

                    $retorno = $navbar->buscaFuncao($funcao);

                    if ($retorno === false || $navbar->getEncontrados() < 1 || empty($navbar->getMensagem())) {
                        throw new Exception($navbar->getMensagem());
                    }

                    $dados = !empty($navbar->getMensagem()) ? $navbar->getMensagem() : '';

                    foreach ($dados as $key => $value) {
                        $moduloUsuario = substr($key, -1);
                        $linksUsuario = $value->links;
                        $linksUsuarioExp = explode(',', $linksUsuario);

                        unset($retorno);

                        $retorno = $navbar->buscaModulo($moduloUsuario);

                        if ($retorno === false) {
                            $mensagem = 'Modulo ' . $moduloUsuario . ' não carregado. Por favor contate o administrador';
                            continue;
                        }

                        $dadosModulo = $navbar->getMensagem();
                        
                        $acessoModulo = $dadosModulo->links;

                        foreach ($acessoModulo as $acessoLinksModulo => $dadosLink) {

                            $nomeLink = ucfirst(mb_strtolower($dadosLink->nome_link));
                            $urlLink = $dadosLink->url_link;
                            foreach ($linksUsuarioExp as $linkUser) {
                                if ($linkUser == $acessoLinksModulo) {
                                    $class = 'nav-link cor-branco';
                                    ?>
                                    <li class="nav-item">
                                        <a class="<?php echo $class ?>" href="<?php echo $urlLink ?>" id="navbar" role="button" aria-expanded="false">
                    <?php echo $nomeLink ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>


                        <?php } ?>
                        <!--</ul>-->
                        <?php
                    }
                } catch (Exception $ex) {
                    $mensagem = $ex->getMessage();
                    return false;
                }
                ?>
                <!--</li>-->
            </ul>
        </div>
    </div>
</nav>