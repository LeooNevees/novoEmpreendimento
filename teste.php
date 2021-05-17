<?php
include __DIR__ . '/sistema.php';
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.83.1">
    <title>Product example · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/product/">


    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="/novoEmpreendimento/css/product.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
        <div class='col-9'>
                <div class='card shadow-sm' style='cursor:pointer;'>
                    <img class='tamanho-imagem-card' src='$imagem'>
                    <div class='card-footer text-muted'>
                        <p class='card-text'>Nome</p>
                        <p class='card-font-valor'>Valor</p>
                        <div class='d-flex justify-content-between align-items-center'>
                            <small class='color-vermelho'>Tipo</small>
                            <small class='text-muted'>Visualização</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-3'>
                <div class='card shadow-sm' style='cursor:pointer;'>
                    <img class='tamanho-imagem-card' src='$imagem'>
                    <div class='card-footer text-muted'>
                        <p class='card-text'>Nome</p>
                        <p class='card-font-valor'>Valor</p>
                        <div class='d-flex justify-content-between align-items-center'>
                            <small class='color-vermelho'>Tipo</small>
                            <small class='text-muted'>Visualização</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>