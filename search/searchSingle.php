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
        echo 'Erro ao incluir navbar';
    }
    ?>
    <div class="d-md-flex flex-md-equal w-100 my-md-3 ps-md-3">
        <div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
            <div class="my-3 p-3">
                <!-- <h2 class="display-5">Another headline</h2> -->
                <!-- <p class="lead">And an even wittier subheading.</p> -->
                <img src="/novoEmpreendimento/files/60516e7e069c1f1d248b4569/20210422202305.jpg">
            </div>
        </div>

        <div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
            <div class="my-3 p-3">
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
                <div>
                    <button type="button" class="btn btn-success">Comprar Agora</button>
                    <button type="button" class="btn btn-outline-secondary">Adicionar ao carrinho</button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-5">
            <h1 class="display-4 fw-normal">Descrição</h1>
            <p class="lead fw-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pagesAnd an even wittier subheading to boot.</p>
        </div>
        <div class="product-device shadow-sm d-none d-md-block"></div>
        <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
    </div>

    <div class="d-md-flex flex-md-equal w-100 my-md-3 ps-md-3">
        <div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
            <div class="my-3 p-3">
                <h2 class="display-5">Avaliações sobre o produto</h2>
                <!-- <p class="lead">And an even wittier subheading.</p> -->
            </div>
        </div>

        <div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
            <div class="my-3 p-3">
                <h2 class="display-5">Dados sobre o vendedor</h2>
                <!-- <p class="lead">Dados do produto e da compra</p> -->
            </div>
        </div>
    </div>

</body>

</html>