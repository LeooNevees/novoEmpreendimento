//MASCARA CNPJ E CPF
function formatarCampo(campoTexto) {
    if (campoTexto.value.length <= 11) {
        campoTexto.value = mascaraCpf(campoTexto.value);
    } else {
        campoTexto.value = mascaraCnpj(campoTexto.value);
    }
}
function retirarFormatacao(campoTexto) {
    campoTexto.value = campoTexto.value.replace(/(\.|\/|\-)/g, "");
}
function mascaraCpf(valor) {
    return valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "\$1.\$2.\$3\-\$4");
}
function mascaraCnpj(valor) {
    return valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3\/\$4\-\$5");
}

//MASCARA NUMERO TELEFONE
function mask(o, f) {
    setTimeout(function () {
        var v = mphone(o.value);
        if (v != o.value) {
            o.value = v;
        }
    }, 1);
}

function mphone(v) {
    var r = v.replace(/\D/g, "");
    r = r.replace(/^0/, "");
    if (r.length > 10) {
        r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
    } else if (r.length > 5) {
        r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
    } else if (r.length > 2) {
        r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
    } else {
        r = r.replace(/^(\d*)/, "($1");
    }
    return r;
}

function maskCep() {
    var v_cep = document.getElementById('cep').value.replace(/\D/g, "");
    if (v_cep.length == 8) {
        v_cep = v_cep.replace(/(\d{5})(\d{3})/g, "\$1-\$2");
    }
    document.getElementById('cep').value = v_cep;
}

//PESQUISAR CEP AUTOMATICO 
function limpa_formulario_cep() {
    //Limpa valores do formulario de cep.
    document.getElementById('rua').value = ("");
    document.getElementById('bairro').value = ("");
    document.getElementById('cidade').value = ("");
    document.getElementById('uf').value = ("");
    document.getElementById('ibge').value = ("");
}

function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        document.getElementById('rua').value = (conteudo.logradouro);
        document.getElementById('bairro').value = (conteudo.bairro);
        document.getElementById('cidade').value = (conteudo.localidade);
        document.getElementById('uf').value = (conteudo.uf);
        document.getElementById('ibge').value = (conteudo.ibge);
    } //end if.
    else {
        //CEP nao Encontrado.
        limpa_formulario_cep();
        alert("CEP não encontrado.");
    }
}

function pesquisacep(valor) {

    //Nova variavel "cep" somente com digitos.
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") {

        //Expressao regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if (validacep.test(cep)) {

            //Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('rua').value = "...";
            document.getElementById('bairro').value = "...";
            document.getElementById('cidade').value = "...";
            document.getElementById('uf').value = "...";
            document.getElementById('ibge').value = "...";

            //Cria um elemento javascript.
            var script = document.createElement('script');

            //Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';

            //Insere script no documento e carrega o conteudo.
            document.body.appendChild(script);
            document.getElementById('oculto').style.display = 'block';

        } else {
            //cep invalido.
            limpa_formulario_cep();
            alert("Formato de CEP invalido.");
            document.getElementById('oculto').style.display = 'none';
        }
    } else {
        //cep sem valor, limpa formulario.
        limpa_formulario_cep();
        document.getElementById('oculto').style.display = 'none';
    }
}

function validar_dados() {
    var analise = true;
    var dados = ['nomeCompleto', 'nomeFantasia', 'email', 'senha', 'confSenha', 'sexo', 'documento', 'cep', 'telefone', 'rua', 'numEndereco', 'bairro', 'cidade'];
    dados.forEach(function (dado) {
        if (analise === true) {
            if (document.getElementById(dado).value === '' && dado !== 'nomeFantasia') {
                alert('Necessario preencher o campo ' + dado);
                analise = false;
            }
        }
    });

    if (analise === true) {
        if (document.getElementById('senha').value !== document.getElementById('confSenha').value) {
            alert('Senhas nao compataveis. Necessario que as senhas sejam iguais');
            analise = false;
        }
    }

    return analise;
}


function cadastrar_parceiro() {
    var retorno = validar_dados();

    if (retorno === false) {
        alternaProtecao(false);
        return false;
    }

    alternaBotaoCadastrarParceiro(true);
    
    $.ajax({
        url: 'ajax_cadastrar_parceiro.php',
        type: 'post',
        data: new FormData($('#myForm')[0]),
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (resposta) {
            alternaBotaoCadastrarParceiro(false);
            
            alert(resposta.mensagem);
            
            if (resposta.status == 'SUCESSO') {
                window.location.href = '/novoEmpreendimento/index.php';
            }
        }
    })

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

function alternaBotaoCadastrarParceiro(a) {
    if (a === true) {
        document.getElementById('botaoCancelar').remove();
        document.getElementById('botaoCadastrar').remove();
        document.getElementById('botao').innerHTML = "<div class='col-md' id='botaoCancelar'><button class='btn btn-outline-danger btn-block' type='button' onclick='alertaCancelar()' style='width: 60%; height: 120%;'>Cancelar</button></div><div class='col-md' id='botaoCadastrar'><button class='btn btn-success btn-block' type='button' onclick='cadastrar_parceiro()' style='width: 142%; margin-left: -40%; height: 120%;' disabled><span class='spinner-border spinner-border-md' role='status' aria-hidden='true'></span></button></div><br>";
    }


    if (a === false) {
        document.getElementById('botaoCancelar').remove();
        document.getElementById('botaoCadastrar').remove();
        document.getElementById('botao').innerHTML = "<div class='col-md' id='botaoCancelar'><button class='btn btn-outline-danger btn-block' type='button' onclick='cancelar()' style='width: 60%; height: 130%;'>Cancelar</button></div><div class='col-md' id='botaoCadastrar'><button class='btn btn-success btn-block' type='button' onclick='cadastrar_parceiro()' style='width: 140%; margin-left: -40%; height: 130%;'>Cadastrar</button></div><br>";
    }
}

 