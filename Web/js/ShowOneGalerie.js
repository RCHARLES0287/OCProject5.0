$(function () {

    new PaginationButtons('/showonegalerie?galerie_id=' + $("[data-galerieid]").data('galerieid'),
                                '.affichage_mosaique'
                                );

});
