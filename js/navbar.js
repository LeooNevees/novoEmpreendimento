function searchProdutcBusiness(){
    var buscar = $("#buscar").val().trim(); 
    if(buscar == ''){
        alert('Necessário informar um Nome ou Produto');
        return false;
    }

    window.location = '/novoEmpreendimento/search/searchProductBusiness.php?search='+buscar;
}