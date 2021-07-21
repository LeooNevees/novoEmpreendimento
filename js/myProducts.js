function adicionar_produto() {
    window.location = '/novoEmpreendimento/user/addProduct.php';
}

function inativarItem(item) {
    if(item == ''){
        alert('Erro ao encontrar Id do item. Por favor recarregue a página');
        return false;
    }

    if(!confirm("Realmente deseja Inativar o item?")){
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
            alert(resposta.mensagem);

            if(resposta.status == 'ERRO'){
                return false;
            }

            myProducts(resposta.business);
        }
    })
}

function myProducts(idBusiness) {
    if(idBusiness == ''){
        alert('Erro ao abrir Meus Produtos. Por favor refaça o procedimento');
        return false;
    }
    
    window.location = '/novoEmpreendimento/user/myProducts.php?business='+idBusiness;
}