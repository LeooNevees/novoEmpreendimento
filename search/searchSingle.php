<?php
include __DIR__ . '/../sistema.php';
?>

<html>

<head>
    <meta charset="utf-8">
    <title>Ipeças - Faça seu pedido</title>

    <!-- CSS -->
    <link href="/novoEmpreendimento/css/product.css" rel="stylesheet">
</head>

<body>
    <?php
    if (!(include __DIR__ . '/../navbar.php')) {
        echo 'Erro ao carregar o Navbar';
    }
    ?>
    <br>
    <div class="container">
        <div class="row row-cols-1 row-cols-lg-2">
            <div class="col col-lg-8">
                <div class='card shadow-sm' style='cursor:pointer;'>
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="/novoEmpreendimento/files/60516e7e069c1f1d248b4569/20210422202305.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="/novoEmpreendimento/files/60516e7e069c1f1d248b4569/20210422202305.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="/novoEmpreendimento/files/60516e7e069c1f1d248b4569/20210422202305.jpg" class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <!-- <div class='card-footer text-muted'>
                        <img src="/novoEmpreendimento/files/60516e7e069c1f1d248b4569/20210422202305.jpg">
                    </div> -->
                </div>
            </div>
            <div class="col col-lg-4">
                <div class='card shadow-sm' style='height:100%;'>
                    <div class='card-footer text-muted' style="height:100%">
                        <div>
                            <span class="cor-letra-cinza">Novo | 4 vendidos</span>
                            <h2>Motor Completo Gol 1.6 2014</h2>
                            <i class="fas fa-star"></i>
                            <i class="cor-letra-vermelho fas fa-star"></i>
                            <i class="cor-letra-laranja fas fa-star"></i>
                            <i class="cor-letra-promocao fas fa-star-half-alt"></i>
                            <i class="cor-letra-laranja far fa-star"></i>
                            <span class="cor-letra-cinza"> 5 Opiniões</span>
                        </div>

                        <div class="valores">
                            <span class="cor-letra-cinza letra-tamanho-80 letra-riscado">R$ 1.000,00</span><br>
                            <label class="letra-tamanho-130">R$ 850,00</label><span class="letra-promocao">31% OFF</span><br>
                            <span>em até <font class="cor-letra-promocao">12x R$ 25,82 sem juros</font></span><br><br>
                        </div>

                        <div>
                            <i class="fas fa-truck"></i>
                            <span>Entrega prevista para <font><b>Quinta-Feira - 27/05<b></font></span><br>
                        </div><br>

                        <div>
                            <label>Cor: <font><b>Preto</b></font></label>
                        </div><br>

                        <div>
                            <label>
                                <font class="cor-letra-promocao">Estoque Disponível</font>
                            </label>
                        </div>
                        <div class="input-group">
                            <input type="number" class="form-control text-center" value="1" aria-label="Dollar amount (with dot and two decimal places)">
                            <span class="input-group-text">18 Disponíveis</span>
                        </div>

                        <div class="d-grid gap-2 div-botao-comprar">
                            <button class="btn btn-success" type="button">Comprar Agora</button>
                            <button class="btn btn-secondary" type="button">Adicionar ao Carrinho</button>
                        </div><br>
                        <div class="cor-letra-promocao text-center">
                            <i class="fas fa-shield-alt"></i>
                            <span class="letra-tamanho-80">Compra Garantida</span><br>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row row-cols-1">
            <div class="col-12">
                <div class='card shadow-sm' style='cursor:pointer;'>
                    <div class='card-footer text-muted text-center'>
                        <h2 class="cor-letra-titulo">Descrição</h2>
                        <p class="lead fw-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot.</p>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row row-cols-1 row-cols-lg-2">
            <div class="col col-lg-6">
                <div class='card shadow-sm'>
                    <div class='card-footer text-muted text-center'>
                        <h2 class="cor-letra-titulo">Avaliações sobre o produto</h2>
                        <h3 class="cor-letra-laranja">3.5</h3>
                        <i class="cor-letra-laranja fas fa-star"></i>
                        <i class="cor-letra-laranja fas fa-star"></i>
                        <i class="cor-letra-laranja fas fa-star"></i>
                        <i class="cor-letra-laranja fas fa-star-half-alt"></i>
                        <i class="cor-letra-laranja far fa-star"></i>
                        <div class="accordion" id="accordionPanelsStayOpenExample">

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                        Produto de ótima qualidade
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                                    <div class="accordion-body">
                                        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                        <footer class="blockquote-footer espaco-2 text-right" title="18-05-2021 14:44">Leonardo Neves</footer>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                        Problema na entrega
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                                    <div class="accordion-body">
                                        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-heading4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse4" aria-expanded="false" aria-controls="panelsStayOpen-collapse4">
                                        Problema na entrega
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapse4" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading4">
                                    <div class="accordion-body">
                                        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                    </div>
                                </div>
                            </div>

                            <nav id="navbar-example2" class="navbar navbar-light bg-light px-3">
                                <a class="navbar-brand" href="#">Navbar</a>
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#scrollspyHeading1">First</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#scrollspyHeading2">Second</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Dropdown</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#scrollspyHeading3">Third</a></li>
                                            <li><a class="dropdown-item" href="#scrollspyHeading4">Fourth</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#scrollspyHeading5">Fifth</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                            <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-offset="0" class="scrollspy-example" tabindex="0">
                                <h4 id="scrollspyHeading1">First heading</h4>
                                <p>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.</p>
                                <h4 id="scrollspyHeading2">Second heading</h4>
                                <p>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.</p>
                                <h4 id="scrollspyHeading3">Third heading</h4>
                                <p>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.</p>
                                <h4 id="scrollspyHeading4">Fourth heading</h4>
                                <p>...</p>
                                <h4 id="scrollspyHeading5">Fifth heading</h4>
                                <p>...</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-lg-6">
                <div class='card shadow-sm'>
                    <div class='card-footer text-muted text-left'>
                        <h2 class="cor-letra-titulo text-center">Dados sobre o vendedor</h2><br>
                        <div>
                            <table class="table table-borderless text-center">
                                <thead>
                                    <tr>
                                        <th class="col-4"><i class="fas fa-shopping-bag fa-2x"></i></th>
                                        <th class="col-4"><i class="cor-letra-vermelho fas fa-truck fa-2x"></i></th>
                                        <th class="col-4"><i class="cor-letra-promocao far fa-comment-dots fa-2x"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>625 Vendas</td>
                                        <td>Entrega dos produsos dentro do prazo (Ruim)</td>
                                        <td>Presta bom atendimento (Bom)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body><br>

</html>