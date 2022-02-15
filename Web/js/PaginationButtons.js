class PaginationButtons {

    constructor() {

        this.startPage = $("[data-startpage]");
        this.numberOfPages = $("[data-numberofpages]");
        // this.galerieId = $("[data-galerieid]");
        this.urlOneGalerie = '/showonegalerie?galerie_id=' + $("[data-galerieid]");

        this.divGalerieARemplacer = '.photo_dans_galerie';
        this.activePageTargetClass = '.page-item';
        this.newPageNumber = $("[data-pagenumber]");

        this.newAppelAjax = new AppelAjax();

    }


    jsReturnTreatment (response)
    {
        console.log (response);
        //On remplace les photos d'origine de la galerie par celles de la nouvelle page sélectionnée
        $(this.divGalerieARemplacer).html(response);

        //On supprime l'attribut "active" sur tous les marqueurs de pages
        $(this.activePageTargetClass).removeAttr('active');

        //On place l'attribut "active" sur la balise correspondant à la page nouvellement sélectionnée
        $('li[data-pagenumber=newPageNumber]').attr('active');
    }

    prepareAjaxCall ()
    {
        this.urlOneGalerie = this.urlOneGalerie + '&new_page_number=' + this.newPageNumber;
        this.newAppelAjax.callAndExtract(this.urlOneGalerie, this.jsReturnTreatment);
    }


    previousButtonBehaviour ()
    {
        if (this.startPage > '1')
        {
            this.newPageNumber = this.startPage - 1;
            this.prepareAjaxCall();
            /*this.urlOneGalerie = this.urlOneGalerie + '&new_page_number=' + this.newPageNumber;
            this.newAppelAjax.callAndExtract(this.urlOneGalerie, this.jsReturnTreatment);*/
        }
    }


    nextButtonBehaviour ()
    {
        if (this.startPage < this.numberOfPages)
        {
            this.newPageNumber = this.startPage + 1;
            this.prepareAjaxCall();
            /*this.urlOneGalerie = this.urlOneGalerie + '&new_page_number=' + this.newPageNumber;
            this.newAppelAjax.callAndExtract(this.urlOneGalerie, this.jsReturnTreatment);*/
        }
    }


    pageNumberButtonBehaviour ()
    {
        if (this.newPageNumber > 0 && this.newPageNumber <= this.numberOfPages)
        {
            this.prepareAjaxCall();
            /*this.urlOneGalerie = this.urlOneGalerie + '&new_page_number=' + this.newPageNumber;
            this.newAppelAjax.callAndExtract(this.urlOneGalerie, this.jsReturnTreatment);*/
        }
    }
}
