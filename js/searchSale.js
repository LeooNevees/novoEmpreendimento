function efetuarCompra(idProduto) {
    $.ajax({
        url: '/novoEmpreendimento/sale/ajax_product_sale.php',
        type: 'post',
        data: {
            'idProduto': idProduto
        },
        dataType: 'json',
        success: function (resposta) {
            if(resposta.erro == 1){
                alert(resposta.mensagem);
            }
            alert('Deu tudo certo');
        }
    })    
}

function adicionarCarrinho(idProduto) {
    alert('Entrou no Adicionar Carrinho para o produto '+idProduto);
}