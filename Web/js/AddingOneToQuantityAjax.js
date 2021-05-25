
class AddingOneToQuantityAjax {
    /**
     *
     * @param objetSelect la balise (le sélecteur) sur laquelle porte l'action du button
     * @param inputsData le tableau de clés et valeurs pour alimenter le paramètre data de la requête Ajax. Clés = les clés qui alimenteront $_POST. Valeurs = le sélecteur de l'attribut name de la balise voulue
     * @param urlTarget l'URL qui pointera vers la méthode de traitement du formulaire
     */
    constructor(objetSelect, inputsData, urlTarget) {
        this.objetSelect = objetSelect;
        this.inputsData = inputsData;
        this.urlTarget = urlTarget;

        this.addOneToQuantityAjax();
    }

    addOneToQuantityAjax () {



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

                console.log(dataParam);

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

