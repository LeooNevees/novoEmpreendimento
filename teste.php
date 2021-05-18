<?php
include __DIR__ . '/sistema.php';
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
    if (!(include 'navbar.php')) {
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
                <div class='card shadow-sm' style='cursor:pointer;'>
                    <div class='card-footer text-muted'>
                        <div>
                            <span class="cor-letra-cinza">Novo | 4 vendidos</span>
                            <h2>Motor Completo Gol 1.6 2014</h2>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <span class="cor-letra-cinza"> 5 Opiniões</span>
                        </div>
                        <div class="valores">
                            <span class="cor-letra-cinza letra-tamanho-80 letra-riscado">R$ 1.000,00</span><br>
                            <label class="letra-tamanho-130">R$ 850,00</label><span class="letra-promocao">31% OFF</span><br>
                            <span>em até <font class="cor-letra-promocao">12x R$ 25,82 sem juros</font></span><br><br>
                        </div>
                        <div>
                            <label>Cor: <font><b>Preto</b></font></label>
                        </div>
                        <div class="d-grid gap-2 div-botao-comprar">
                            <button class="btn btn-success" type="button">Comprar Agora</button>
                            <button class="btn btn-secondary" type="button">Adicionar ao Carrinho</button>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row row-cols-1">
            <div class="col-12">
                <div class='card shadow-sm' style='cursor:pointer;'>
                    <div class='card-footer text-muted text-center'>
                        <h2>Descrição</h2>
                        <p class="lead fw-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot.</p>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row row-cols-1 row-cols-lg-2">
            <div class="col col-6">
                <div class='card shadow-sm' style='cursor:pointer;'>
                    <div class='card-footer text-muted text-center'>
                        <h2>Avaliações sobre o produto</h2>
                        <p class="lead fw-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot.</p>
                    </div>
                </div>
            </div>
            <div class="col col-6">
                <div class='card shadow-sm' style='cursor:pointer;'>
                    <div class='card-footer text-muted text-center'>
                        <h2>Dados sobre o vendedor</h2>
                        <p class="lead fw-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>