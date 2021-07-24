function abrirCard(card){
    if(card.trim() == ''){
        alert('Erro ao identificar o Id do Produto. Por favor refaça o procedimento');
        return false;
    }
    window.location = '/novoEmpreendimento/search/searchSingle.php?product='+card;
}

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

            if(!confirm('Deseja avaliar o Produto? ')){
                window.location.reload();
            }
            $("#exampleModal").modal('show');
        }
    })    
}

function adicionarCarrinho(idProduto) {
    alert('Adicionado Produto ao carrinho');
}

function cadastrarAvaliacao() {
    if($("#titulo_avaliacao").val() == ''){
        alert('Necessário informar um título');
        return false;
    }

    if($("#estrelas_avaliacao").val() == ''){
        alert('Necessário selecionar pelo menos uma Estrela');
        return false;
    }

    if($("#mensagem_avaliacao").val() == ''){
        alert('Por favor insira uma mensagem');
        return false;
    }

    $.ajax({
        url: '/novoEmpreendimento/evaluation/ajax_evaluation.php',
        type: 'post',
        data: {
            'tipo': 'PRODUTO',
            'id_produto': $("#id_produto").val(),
            'titulo': $("#titulo_avaliacao").val(),
            'estrelas': $("#estrelas_avaliacao").val(),
            'descricao_avaliacao': $("#descricao_avaliacao").val()
        },
        dataType: 'json',
        success: function (resposta) {
            alert(resposta.mensagem);
            if(resposta.status == 'ERRO'){
                return false;
            }
            window.location.reload();
        }
    })  
}