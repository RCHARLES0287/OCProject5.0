
class ValidatingFormAjax {
    /**
     *
     * @param objetSelect la balise (le sélecteur) sur laquelle porte l'action de submit
     * @param urlTarget l'URL qui pointera vers la méthode de traitement du formulaire
     * @param inputsData le tableau de clés et valeurs pour alimenter le paramètre data de la requête Ajax. Clés = les clés qui alimenteront $_POST. Valeurs = le sélecteur de l'attribut name de la balise voulue
     * @param successMessage le message qui sera affiché dans la fenêtre d'alerte en cas de succès
     */
    constructor(objetSelect, urlTarget, inputsData, successMessage) {
        this.objetSelect = objetSelect;
        this.urlTarget = urlTarget;
        this.inputsData = inputsData;
        this.successMessage = successMessage;

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
                        if (data.status === 'Succès') {
                            alert(this.successMessage);
                        } else {
                            alert("Echec de l'action");
                        }
                    }.bind(this),
                    error : function (jqXHR, status, errorMessage) {
                        console.log(status + ':' + errorMessage);
                    }
                });
                return false;
            }.bind(this));
        }
    }
}


