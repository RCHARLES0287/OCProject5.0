
class AddingOneToQuantityAjax {
    /**
     *
     * @param objetSelect la balise (le sélecteur) sur laquelle porte l'action du button
     * @param currentQuantity la quantité qu'on va lire et modifier si elle répond aux conditions voulues
     * @param inputsData le tableau de clés et valeurs pour alimenter le paramètre data de la requête Ajax. Clés = les clés qui alimenteront $_POST. Valeurs = le sélecteur de l'attribut name de la balise voulue
     * @param urlTarget l'URL qui pointera vers la méthode de traitement du formulaire
     */
    constructor(objetSelect, currentQuantity, inputsData, urlTarget) {
        this.objetSelect = objetSelect;
        this.currentQuantity = currentQuantity;
        this.inputsData = inputsData;
        this.urlTarget = urlTarget;

        this.addOneToQuantityAjax();
    }

    addOneToQuantityAjax () {
        if (typeof this.objetSelect === "string" && typeof this.urlTarget === "string" && typeof this.inputsData !== "undefined") {
            let dataParam = {};
            console.log(this.inputsData);

            $(this.objetSelect).click(function (e){
                e.preventDefault();

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
    }
}