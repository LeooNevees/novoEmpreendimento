function searchProdutcBusiness(){
    var buscar = $("#buscar").val().trim(); 
    if(buscar == ''){
        alert('Necessário informar um Nome ou Produto');
        return false;
    }

    window.location = '/novoEmpreendimento/search/searchProductBusiness.php?search='+buscar;
}

function myProducts(idBusiness) {
    if(idBusiness == ''){
        alert('Erro ao abrir Meus Produtos. Por favor refaça o procedimento');
        return false;
    }
    
    window.location = '/novoEmpreendimento/user/myProducts.php?business='+idBusiness;
}