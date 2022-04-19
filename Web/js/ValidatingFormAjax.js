
class ValidatingFormAjax {
    /**
     *
     * @param objetSelect la balise (le sélecteur) sur laquelle porte l'action de submit
     * @param urlTarget l'URL qui pointera vers la méthode de traitement du formulaire
     * @param inputsData le tableau de clés et valeurs pour alimenter le paramètre data de la requête Ajax. Clés = les clés qui alimenteront $_POST. Valeurs = le sélecteur de l'attribut name de la balise voulue
     */
    constructor(objetSelect, urlTarget, inputsData) {
        this.objetSelect = objetSelect;
        this.urlTarget = urlTarget;
        this.inputsData = inputsData;

        this.validateFormAjax();
    }

    validateFormAjax () {
        if (typeof this.objetSelect === "string" && typeof this.urlTarget === "string" && typeof this.inputsData !== "undefined") {
            let dataParam = {};
            console.log(this.inputsData);

            $(this.objetSelect).submit(function (e){
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
                        alert(data.message);
                    }.bind(this),
                    error : function (jqXHR, status, errorMessage) {
                        console.log(status + ':' + errorMessage);
                        alert('Echec du traitement');
                    }
                });
                return false;
            }.bind(this));
        }
    }
}


