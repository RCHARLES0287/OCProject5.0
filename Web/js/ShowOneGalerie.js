$(function () {

    /*
    let startPage = $("[data-startpage]");
    let numberOfPages = $("[data-numberofpages]");
    let galerieId = $("[data-galerieid]");
    let urlOneGalerie = '/showonegalerie?galerie_id=' + galerieId;

    let divGalerieARemplacer = '.photo_dans_galerie';
    let activePageTargetClass = '.page-item';
    let newPageNumber = $("[data-pagenumber]");
    */

    let newPaginationButtons = new PaginationButtons();

    console.log('le fichier js est chargé');

    $(".previous-button").click(newPaginationButtons.previousButtonBehaviour);
    // $(".previous-button").click(newPaginationButtons.previousButtonBehaviour);
    $(".next-button").click(newPaginationButtons.nextButtonBehaviour);
    $(".page-number-button").click(newPaginationButtons.pageNumberButtonBehaviour);



});