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

    if($("#promocao").val() == 'SIM' && parseFloat($("#porcentagem_desconto").val()) < 1){
        alert('Necessário informar a Porcentagem de Desconto');
        return false;
    }

    if($("#imagens_produto").val() == ''){
        alert('Necessário selecionar pelo menos uma Imagem');
        return false;
    }

    alternaBotaoCadastrarParceiro(true);

    $.ajax({
        url: '/novoEmpreendimento/products/ajax_add_product.php',
        type: 'post',
        data: new FormData($('#myForm')[0]),
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (resposta) {
            alternaBotaoCadastrarParceiro(false);
            alert(resposta.mensagem);
            if(resposta.status === 'ERRO'){
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

function alternaBotaoCadastrarParceiro(a) {
    if (a === true) {
        document.getElementById('botaoCancelar').remove();
        document.getElementById('botaoCadastrar').remove();
        document.getElementById('botao').innerHTML = "<div class='col-md' id='botaoCancelar'><button class='btn btn-outline-danger btn-block' type='button' onclick='alertaCancelar()' style='width: 60%; height: 90%;'>Cancelar</button></div><div class='col-md' id='botaoCadastrar'><button class='btn btn-success btn-block' type='button' onclick='cadastrar_parceiro()' style='width: 142%; margin-left: -40%; height: 90%;' disabled><span class='spinner-border spinner-border-md' role='status' aria-hidden='true'></span></button></div><br>";
    }


    if (a === false) {
        document.getElementById('botaoCancelar').remove();
        document.getElementById('botaoCadastrar').remove();
        document.getElementById('botao').innerHTML = "<div class='col-md' id='botaoCancelar'><button class='btn btn-outline-danger btn-block' type='button' onclick='cancelar()' style='width: 60%; height: 90%;'>Cancelar</button></div><div class='col-md' id='botaoCadastrar'><button class='btn btn-success btn-block' type='button' onclick='cadastrar_parceiro()' style='width: 140%; margin-left: -40%; height: 90%;'>Cadastrar</button></div><br>";
    }
}

function cancelar() {
    if (window.confirm('Realmente deseja cancelar o cadastro?')) {
        window.location.href = '../index.php';
    }
}

function alertaCancelar(){
    alert('Os dados estao sendo processados. Por favor aguarde');
    return false;
}