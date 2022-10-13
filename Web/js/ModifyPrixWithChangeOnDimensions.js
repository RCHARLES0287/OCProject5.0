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
        this.selectedOption = '';
    }

    remplissageChampTarifAjax (response_raw) {
        let response = JSON.parse(response_raw);
        console.log(response);
        console.log(typeof response);

        $(this.selectedOption).siblings('input[class="tarifs_associes"]').val(response);

        // https://api.jquery.com/toggle/#toggle-display
        $(this.selectedOption).siblings('button[name="marqueur_save_vs_delete"][value="save"]').toggle(response === '');
        $(this.selectedOption).siblings('button[name="marqueur_save_vs_delete"][value="delete"]').toggle(response !== '');

    }

    detectChangeOnSelect (e) {
        console.log(this.objetSelect);

        console.log("changement du select repéré");
        e.preventDefault();

        // const selectedOption = $(e.target);
        this.selectedOption = $(e.target);

        console.log(this.selectedOption);

        // let valueSelectedOption = selectedOption.val();
        const idDimensions = this.selectedOption.val();

        const idPhoto = this.selectedOption.data("idphoto");

        console.log(idDimensions);
        console.log(idPhoto);

        const urlWithParameters = this.urlCible + 'id_photo=' + idPhoto + '&id_dimensions=' + idDimensions;
        this.newAppelAjax.callAndExtract(urlWithParameters, this.remplissageChampTarifAjax.bind(this));
    }

}

