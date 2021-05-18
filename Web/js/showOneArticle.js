$(function () {
    new PlacingAssociatedValue('#dimensions_deroulant', 'prix', '#prix_article');

    new ValidatingFormAjax('#formulaire_panier',
        '/validateonearticle',
        {'idPhoto': 'input[name=id_photo]', 'idDimensions': 'select[name=id_dimensions]', 'nombreArticles': 'select[name=nombre_articles]'}
    );
});


/*

$("#formulaire_panier").submit(function(e){
    e.preventDefault();
    console.log('On est entré dans la requête ajax pour la validation du formulaire')

    $.ajax({
        url : '/validateonearticle', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP

        data : {
            'idPhoto' : $('input[name=id_photo]', $(this)).val(),
            'idDimensions' : $('select[name=id_dimensions]', $(this)).val(),
            'nombreArticles' : $('select[name=nombre_articles]', $(this)).val()
        },
        success : function (data) {
            console.log(data.status);
        },
        error : function (jqXHR, status, errorMessage) {
            console.log(status + ':' + errorMessage);
        }
    });
    console.log('La requête ajax pour la validation du formulaire fonctionne');

    return false;
});

*/


/*

class ValidatingFormAjax {
    constructor(objetSelect, urlTarget, inputsData, ) {
        this.objetSelect = objetSelect;
        this.urlTarget = urlTarget;
        this.inputsData = inputsData;

        this.validateFormAjax();
    }

    validateFormAjax () {
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

*/
