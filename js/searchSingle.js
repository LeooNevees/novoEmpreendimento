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
            $("#id_negociacao").val(resposta.idInserido);

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
    var erro = false;
    var arrayValidar = ['titulo_avaliacao', 'estrelas_avaliacao', 'mensagem_avaliacao'];
    arrayValidar.forEach(function(input) {
        if(erro == false && $("#"+input).val() == ''){
            alert('Necessário informar Título, Estrelas e Descrição para avaliar o Produto');
            erro = true;
            return false;
        }
    });
    if(erro == true){
        return false;
    }
    
    if($("#titulo_vendedor").val() != '' || $("#atendimento").val() != '' || $("#tempo_entrega").val() != '' || $("#observacao").val() != ''){
        if($("#titulo_vendedor").val() == '' || $("#atendimento").val() == '' || $("#tempo_entrega").val() == ''){
            alert('Necessário informar Título, Atendimento e Tempo Entrega para avaliar o Vendedor');
            return false;
        }
    }

    var teste = new FormData($('#avaliacoes')[0]);

    $.ajax({
        url: '/novoEmpreendimento/evaluation/ajax_evaluation.php',
        type: 'post',
        data: new FormData($('#myForm')[0]),
        cache: false,
        contentType: false,
        processData: false,
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