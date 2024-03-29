
class AddingOrRemovingOneToQuantityAjax {
    /**
     *
     * @param objetSelect la balise (le sélecteur) sur laquelle porte l'action du button
     * @param inputsData le tableau de clés et valeurs pour alimenter le paramètre data de la requête Ajax. Clés = les clés qui alimenteront $_POST. Valeurs = le sélecteur de l'attribut name de la balise voulue
     * @param urlTarget l'URL qui pointera vers la méthode de traitement du formulaire
     * @param newParentLocation la balise parent qui englobe la quantité et le prix total
     * @param newValueLocation la balise qui recevra la nouvelle quantité
     * @param newPriceLocation la balise qui recevra le nouveau prix
     */
    constructor(objetSelect, inputsData, urlTarget, newParentLocation, newValueLocation, newPriceLocation) {
        this.objetSelect = objetSelect;
        this.inputsData = inputsData;
        this.urlTarget = urlTarget;
        this.newParentLocation = newParentLocation;
        this.newValueLocation = newValueLocation;
        this.newPriceLocation = newPriceLocation;

        this.addOrRemoveOneToQuantityAjax();
    }

    addOrRemoveOneToQuantityAjax() {


        if (typeof this.objetSelect === "string" && typeof this.urlTarget === "string" && typeof this.inputsData !== "undefined") {
            $(this.objetSelect).click(function (e) {
                e.preventDefault();

                // let clickedButton = $(this); Est équivalent à la ligne ci-dessous
                let clickedButton = $(e.target);
                let dataParam = {};
                console.log(this.inputsData);

                $.each(this.inputsData, function (cleDuPost, selecteurDuData) {
                    dataParam[cleDuPost] = clickedButton.parent().data(selecteurDuData);
                });

                dataParam['modifType'] = clickedButton.data('modiftype');

                console.log(dataParam);

                $.ajax({
                    url: this.urlTarget,
                    type: 'POST',
                    data: dataParam,
                    success: function (data) {
                        console.log(data);
                        console.log(data.status);

                        if (data.status === 'Succès') {
                            const parent = clickedButton.parents(this.newParentLocation).first();
                            parent.find(this.newValueLocation).html(data.newQuantity);
                            parent.find(this.newPriceLocation).html(data.prixTotalPhoto);
                        }

                    }.bind(this),
                    error: function (jqXHR, status, errorMessage) {
                        console.log(status + ':' + errorMessage);
                        alert('Echec lors de la mise à jour du panier');
                    }
                });
                return false;
            }.bind(this));

        }



        /*
        if (typeof this.objetSelect === "string" && typeof this.urlTarget === "string" && typeof this.inputsData !== "undefined") {

            console.log(this.inputsData);

            $(this.objetSelect).click(function (e){
                e.preventDefault();

                let dataParam = {};

                $.each(this.inputsData, function (cleDuPost, selecteurBalise) {
                    dataParam[cleDuPost] = $(selecteurBalise, $(this.objetSelect)).val();
                    console.log(dataParam);
                }.bind(this));

                $.ajax({
                    url: this.urlTarget,
                    type: 'POST',
                    data: dataParam,
                    success : function (data) {
                        console.log(data);
                        console.log(data.status);
                    },
                    error : function (jqXHR, status, errorMessage) {
                        console.log(status + ':' + errorMessage);
                    }
                });
                return false;
            }.bind(this));
        }
        */

    }
}


/*
        $('.qte_plus_button').click(function (e) {
            e.preventDefault();

            // let clickedButton = $(this); Est équivalent à la ligne ci-dessous
            let clickedButton = $(e.target);
            let idPhoto = clickedButton.parent().data('idphoto');
            // let idPhoto = clickedButton.data('idphoto', nouvelle quantité);
            console.log(idPhoto);

            return false;
        });
*/


/*
        let laValeurDuData = $('input[name="'+this.currentQuantity+'"]').val();
        console.log(laValeurDuData);
*/

/*

$(this.objetSelect).click(function (e) {
    e.preventDefault();

    let clickedButton = $(e.target);

    let idPhoto = $('input[name="'+this.inputIdPhoto+'"]').val();
    let idDimensions = $('input[name="'+this.inputIdDimensions+'"]').val();
    let quantity = $('input[name="'+this.inputQuantity+'"]').val();

    console.log("L'idPhoto est : ", idPhoto);
    console.log("L'idDimensions est : ", idDimensions);
    console.log("La quantité est de : ", quantity);
});
*/

