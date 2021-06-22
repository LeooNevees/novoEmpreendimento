function abrirCard(card){
    if(card.trim() == ''){
        alert('Erro ao identificar o Id do Produto. Por favor refaça o procedimento');
        return false;
    }
    window.location = '/novoEmpreendimento/search/searchSingle.php?product='+card;
}

function abrirBusiness(idBusiness){
    if(idBusiness.trim() == ''){
        alert('Erro ao identificar o Id do Parceido Negócio. Por favor refaça o procedimento');
        return false;
    }
    window.location = '/novoEmpreendimento/search/searchSingle.php?business='+idBusiness;
}