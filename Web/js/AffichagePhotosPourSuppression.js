class AffichagePhotosPourSuppression {
    constructor() {

        $(".bouton_selection_galerie").click(this.getOneGaleriePhotos.bind(this));

        this.divPhotosARemplacer = '';

        this.newAppelAjax = new AppelAjax();
    }

    jsReturnTreatment (response)
    {
        //On place les photos de la galerie sélectionnée dans la page HTML
        $(this.divPhotosARemplacer).html(response);

    }


    getOneGaleriePhotos (e)
    {
        e.preventDefault();

        let selectedGalerieId = $(e.currentTarget).data('idgalerie');
        let urlSuppressionPhotos = '/admin/suppressionphotochoixgalerie?idgalerie=' + selectedGalerieId;
        this.divPhotosARemplacer = '#photos_galerie_' + selectedGalerieId;

        this.newAppelAjax.callAndExtract(urlSuppressionPhotos, this.jsReturnTreatment.bind(this));
    }

}