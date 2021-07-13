function ativarPromocao() {
    $("#div_porc_desconto").css("display","none");
    if($("#promocao").val() == 'SIM'){
        $("#div_porc_desconto").css("display","block");
    }
}

function validarNumero(dados, limite = ''){
    var valor = dados.value;
    var idInput = dados.name;
    if(isNaN(valor)){
        $("#"+idInput).val('');
        alert('Não é valor válido');
        return false
    }

    if(limite != ''){
        if(valor < 0 || valor > parseInt(limite)){
            alert('Valores permitidos de 0 a '+parseInt(limite));
            $("#"+idInput).val('');
            return false
        } 
    }
    return true;
}

function formataDinheiro() {
    var dinheiro = $('#valor').mask('000.000,00');
    console.log('Dinheiro: '+dinheiro);
}


function mascaraMoeda(event) {
    const onlyDigits = event.target.value
      .split("")
      .filter(s => /\d/.test(s))
      .join("")
      .padStart(3, "0")
    const digitsFloat = onlyDigits.slice(0, -2) + "." + onlyDigits.slice(-2)
    event.target.value = maskCurrency(digitsFloat)
  }
  
function maskCurrency(valor, locale = 'pt-BR', currency = 'BRL') {
return new Intl.NumberFormat(locale, {
    style: 'currency',
    currency
}).format(valor)
}

function cadastrarProduto(){
    console.log('Iniciando Cadastro de Produto');
    var arrayValidar = ['nome_produto', 'quantidade_produto', 'descricao_produto', 'cor_produto', 'tipo', 'grupo', 'valor', 'promocao'];
    var erro = false;
    arrayValidar.forEach(function (input) {
        if(erro === false && $("#"+input).val() == ''){
            erro = true;
            alert('Necessário informar '+$("#"+input).attr("placeholder"));
            return false;
        }
    });
    if(erro === true){
        return false;
    }

    console.log('Iniciando Ajax');
    $.ajax({
        url: '/novoEmpreendimento/products/ajax_add_product.php',
        type: 'post',
        data: {
            'nomeProduto': $("#nome_produto").val(),
            'quantidadeProduto': $("#quantidade_produto").val(),
            'descricaoProduto': $("#descricao_produto").val(),
            'corProduto': $("#cor_produto").val(),
            'tipo': $("#tipo").val(),
            'grupo': $("#grupo").val(),
            'valor': $("#valor").val(),
            'promocao': $("#promocao").val(),
            'porcentagemPromocao': $("#porcentagem_desconto").val()
        },
        dataType: 'json',
        success: function (resposta) {
            console.log(resposta);
            if(resposta.erro === true){
                alert(reposta.mensagem);
                return false;
            }
            alert(resposta.mensagem);
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