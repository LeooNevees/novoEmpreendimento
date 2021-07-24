function efetuarCompra(idProduto) {
    if(idProduto == ''){
        alert('Produto inválido. Por favor refaça o procedimento');
        return false;
    }
    let quantidadeCompra = parseFloat($("#quantidade_compra").val());
    if(quantidadeCompra == '' || quantidadeCompra < 1){
        alert('Quantidade inválida. Por favor refaça o procedimento');
        return false;
    }

    if(!confirm("Realmente deseja efetuar a Compra?")){
        return false;
    }

    $.ajax({
        url: '/novoEmpreendimento/sale/ajax_product_sale.php',
        type: 'post',
        data: {
            'idProduto': idProduto,
            'quantidade': quantidadeCompra
        },
        dataType: 'json',
        success: function (resposta) {
            if(resposta.status == 1){
                if(!confirm(resposta.mensagem)){
                    return false;
                }
                if(resposta.erro == 'LOGIN'){
                    window.location.href="/novoEmpreendimento/login.php";
                    return false;
                }
            }
            alert(resposta.mensagem);
            window.location.reload();
        }
    })    
}

function adicionarCarrinho(idProduto) {
    alert('Adicionado Produto ao carrinho');
}