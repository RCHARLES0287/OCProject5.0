
class ValidatingFormAjax {
    /**
     *
     * @param objetSelect la balise (le sélecteur) sur laquelle porte l'action de submit
     * @param urlTarget l'URL qui pointera vers la méthode de traitement du formulaire
     * @param inputsData le tableau de clés et valeurs pour alimenter le paramètre data de la requête Ajax. Clés = les clés qui alimenteront $_POST. Valeurs = le sélecteur de l'attribut name de la balise voulue
     */
    constructor(objetSelect, urlTarget, inputsData, ) {
        this.objetSelect = objetSelect;
        this.urlTarget = urlTarget;
        this.inputsData = inputsData;

        this.validateFormAjax();
    }

    validateFormAjax () {
        if (typeof this.objetSelect === "string" && typeof this.urlTarget === "string" && typeof this.inputsData !== "undefined") {
            let dataParam = [];
            $.each(this.inputsData, function (key, value) {
                dataParam.push(key + ' : $(' + value + ', $(this)).val()');
            })
            $(this.objetSelect).submit(function (e){
                e.preventDefault();

                $.ajax({
                    url: this.urlTarget,
                    type: 'POST',
                    data: dataParam,
                    success : function (data) {
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

