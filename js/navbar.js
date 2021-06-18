function abrirProductBusiness(){
    if(!$("#selectBuscarDados") || $("#selectBuscarDados").val() == ''){
        alert('Id do produto/parceiro não idenficado. Por favor refaça o procedimento');
        return false;
    }
    window.location = '/novoEmpreendimento/search/searchSingle.php?'+$("#selectBuscarDados").val();
}