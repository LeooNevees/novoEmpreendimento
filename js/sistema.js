function alternaProtecao(a){    
    if(a === true){
       document.getElementById('carregando').style.display = 'block'; 
    }
    
    if(a === false){
        document.getElementById('carregando').style.display = 'none';
    }
}

function alternaBotao(a){    
    if(a === true){
       document.getElementById('botaoAcessar').remove();
       document.getElementById('botao').innerHTML = "<button id='botaoCarregar' class='btn btn-success' type='button' style='width: 100%;' disabled><span class='spinner-border spinner-border-md' role='status' aria-hidden='true'></span></button>"; 
       
       document.getElementById('btnCadastroPareciro').value = false;
    }
        
    if(a === false){
        document.getElementById('botaoCarregar').remove();
        document.getElementById('botao').innerHTML = "<div id='botaoAcessar'><button class='btn btn-outline-success btn-block' type='button' onclick='validarLogin()'>Acessar</button></div>"; 
        
        document.getElementById('btnCadastroPareciro').value = true;
    }
}

function cadastroParceiro(){
    var status = document.getElementById('btnCadastroPareciro').value;
    
    if(status == 'true'){
        window.location.href = '/novoEmpreendimento/businessPartner/cadastro_parceiro.php';
        return true;
    }
    
    alert('Tentativa de acesso em andamento. Por favor aguarde');
}


function validarLogin() {
    var usuario = document.getElementById('usuario').value;
    var senha = document.getElementById('senha').value;
    
    if (usuario.trim() == "") {
        alert("Usuário não fornecido");
        return false;
    }
    
    if (senha.trim() == "") {
        alert("Senha não fornecida");
        return false;
    }
    
    alternaBotao(true);
    
    $.ajax({
        url: 'validarLogin.php',
        type: 'post',
        data: {
            'usuario': usuario,
            'senha': senha
        },
        dataType: 'json',
        success: function (resposta) {
            var status = resposta.status;
            var mensagem = resposta.mensagem;
            if(status == 'SUCESSO'){
                window.location.href = "/novoEmpreendimento/index.php";
            }else{
                window.location.href = "/novoEmpreendimento/login.php";
            }
            
        }
    })
}

function confirmarLogout(){
    if(confirm("Realmente deseja sair?")){
        return true;
    }
    return false;
}

