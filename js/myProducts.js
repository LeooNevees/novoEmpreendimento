function adicionar_produto() {
    window.location = '/novoEmpreendimento/user/addProduct.php';
}

function onclickInativarItem(item) {
    if(item == ''){
        alert('Erro ao encontrar Id do item. Por favor recarregue a página');
        return false;
    }

    $.ajax({
        url: '/novoEmpreendimento/products/ajax_delete_product.php',
        type: 'post',
        data: {
            'idProduto': item
        },
        dataType: 'json',
        success: function (resposta) {
            
        }
    })
}