class PaginationButtons {

    constructor() {

        this.currentPage = $("[data-startpage]").data('startpage');
        this.numberOfPages = $("[data-numberofpages]").data('numberofpages');
        // this.galerieId = $("[data-galerieid]");
        this.urlOneGalerie = '/showonegalerie?galerie_id=' + $("[data-galerieid]").data('galerieid');

        this.divGalerieARemplacer = '.affichage_galerie';
        this.activePageTargetClass = '.page-item.active';
        this.newPageNumber = 1;

        this.newAppelAjax = new AppelAjax();
    }


    jsReturnTreatment (response)
    {
        console.log ('Bip bip');
        console.log (response);
        //On remplace les photos d'origine de la galerie par celles de la nouvelle page sélectionnée
        // $(this.divGalerieARemplacer).html(response);
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
        console.log('ça va encore');
        console.log(urlGalerieAndPage);
        this.newAppelAjax.callAndExtract(urlGalerieAndPage, this.jsReturnTreatment.bind(this));
    }


    previousButtonBehaviour (e)
    {
        e.preventDefault();
        if (this.currentPage > 1)
        {
            this.newPageNumber = this.currentPage - 1;
            this.prepareAjaxCall();
            /*this.urlOneGalerie = this.urlOneGalerie + '&new_page_number=' + this.newPageNumber;
            this.newAppelAjax.callAndExtract(this.urlOneGalerie, this.jsReturnTreatment);*/
        }
    }


    nextButtonBehaviour (e)
    {
        e.preventDefault();
        if (this.currentPage < this.numberOfPages)
        {
            this.newPageNumber = this.currentPage + 1;
            this.prepareAjaxCall();
            /*this.urlOneGalerie = this.urlOneGalerie + '&new_page_number=' + this.newPageNumber;
            this.newAppelAjax.callAndExtract(this.urlOneGalerie, this.jsReturnTreatment);*/
        }
    }


    pageNumberButtonBehaviour (e)
    {
        e.preventDefault();
        this.newPageNumber = $(e.currentTarget).data('pagenumber');
        if (this.newPageNumber > 0 && this.newPageNumber <= this.numberOfPages)
        {
            console.log('jusque là tout va bien');

            this.prepareAjaxCall();
            /*this.urlOneGalerie = this.urlOneGalerie + '&new_page_number=' + this.newPageNumber;
            this.newAppelAjax.callAndExtract(this.urlOneGalerie, this.jsReturnTreatment);*/
        }
    }
}
