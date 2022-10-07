class ModifyPrixWithChangeOnDimensions {

    /**
     *
     * @param objetSelect la balise select sur laquelle on a agit
     */
    constructor(objetSelect) {
        this.objetSelect = objetSelect;
        this.newAppelAjax = new AppelAjax();
        this.urlCible = '/admin/getonetarif?';
        // this.detectChangeOnSelect();
        $(this.objetSelect).change(this.detectChangeOnSelect.bind(this));
        this.selectedOpt = '';
    }

    remplissageChampTarifAjax (response) {
        console.log(response);
        if (response !== false) {
            // $(this.objetSelect).siblings('input[class="tarifs_associes"]').val(response);
            $(this.selectedOpt).siblings('input[class="tarifs_associes"]').val(response);
        }
    }

    detectChangeOnSelect (e) {
        console.log(this.objetSelect);

        console.log("changement du select repéré");
        e.preventDefault();

        var selectedOption = $(e.target);
        this.selectedOpt = $(e.target);

        console.log(selectedOption);

        // let valueSelectedOption = selectedOption.val();
        let idDimensions = selectedOption.val();

        let idPhoto = selectedOption.data("idphoto");

        console.log(idDimensions);
        console.log(idPhoto);

        const urlWithParameters = this.urlCible + 'id_photo=' + idPhoto + '&id_dimensions=' + idDimensions;
        this.newAppelAjax.callAndExtract(urlWithParameters, this.remplissageChampTarifAjax.bind(this));
    }
}

