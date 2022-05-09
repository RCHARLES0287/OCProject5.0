class PaginationButtons {

    constructor() {

        $(".previous-button").click(this.previousButtonBehaviour.bind(this));
        $(".next-button").click(this.nextButtonBehaviour.bind(this));
        $(".page-number-button").click(this.pageNumberButtonBehaviour.bind(this));

        this.currentPage = $("[data-startpage]").data('startpage');
        this.numberOfPages = $("[data-numberofpages]").data('numberofpages');
        this.urlOneGalerie = '/showonegalerie?galerie_id=' + $("[data-galerieid]").data('galerieid');

        // this.divGalerieARemplacer = '.affichage_galerie';
        this.divGalerieARemplacer = '.affichage_mosaique';
        this.activePageTargetClass = '.page-item.active';
        this.newPageNumber = 1;

        this.newAppelAjax = new AppelAjax();
    }


    jsReturnTreatment (response)
    {
        //On remplace les photos d'origine de la galerie par celles de la nouvelle page sélectionnée
        $(this.divGalerieARemplacer).html(response);

        //On supprime la classe "active" sur tous les marqueurs de pages
        $(this.activePageTargetClass).removeClass('active');

        //On place la classe "active" sur la balise correspondant à la page nouvellement sélectionnée
        $('li[data-pagenumber=' + this.newPageNumber + ']').addClass('active');

        this.currentPage = this.newPageNumber;
    }

    prepareAjaxCall ()
    {
        const urlGalerieAndPage = this.urlOneGalerie + '&new_page_number=' + this.newPageNumber;
        this.newAppelAjax.callAndExtract(urlGalerieAndPage, this.jsReturnTreatment.bind(this));
    }


    previousButtonBehaviour (e)
    {
        e.preventDefault();
        if (this.currentPage > 1)
        {
            this.newPageNumber = this.currentPage - 1;
            this.prepareAjaxCall();
        }
    }


    nextButtonBehaviour (e)
    {
        e.preventDefault();
        if (this.currentPage < this.numberOfPages)
        {
            this.newPageNumber = this.currentPage + 1;
            this.prepareAjaxCall();
        }
    }


    pageNumberButtonBehaviour (e)
    {
        e.preventDefault();
        this.newPageNumber = $(e.currentTarget).data('pagenumber');
        if (this.newPageNumber > 0 && this.newPageNumber <= this.numberOfPages)
        {
            this.prepareAjaxCall();
        }
    }
}
