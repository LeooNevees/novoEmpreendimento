function ativarPromocao() {
    $("#div_porc_desconto").css("display","none");
    if($("#promocao").val() == 'SIM'){
        $("#div_porc_desconto").css("display","block");
    }
}

function validarNumero(dados, limite = ''){
    var valor = dados.value;
    var idInput = dados.name;
    console.log('Valor: '+valor);
    console.log('Name: '+idInput);
    console.log('Limite: '+limite);
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