$(function () {

    new PaginationButtons('/sendsearchrequest?texte_recherche=' + $("#texte_recherche_initiale").html(),
                                '.affichage_mosaique'
                                );

});




