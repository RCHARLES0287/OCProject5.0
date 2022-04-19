$(function () {

    /*
    new AddingOneToQuantityAjax('#qte_plus_button_{{ unArticle.orderedArticle.id }}',
        '#number_of_articles_panier_span_{{ unArticle.orderedArticle.id }}',
        {'idPhoto': 'input[name=id_photo]', 'idDimensions': 'input[name=id_dimensions]', 'nombreArticles': 'input[name=nombre_articles]'},
        '/addonetoquantity');

    */

    // new AddingOneToQuantityAjax('id_photo', 'id_dimensions', 'nombre_articles', '#qte_plus_button');

    new AddingOrRemovingOneToQuantityAjax(
        '.qte_button',
        {'idPhoto': 'idphoto', 'idDimensions': 'iddimensions', 'nombreArticles': 'articleqty'},
        '/addorremoveonetoquantity',
        '.number_of_articles_panier_span'
    );


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
    new RemovingOneToQuantityAjax('#qte_moins_button_{{ unArticle.orderedArticle.id }}',
        '#number_of_articles_panier_span_{{ unArticle.orderedArticle.id }}',
        {'idPhoto': 'input[name=id_photo]', 'idDimensions': 'input[name=id_dimensions]', 'nombreArticles': 'input[name=nombre_articles]'},
        '/removeonetoquantity');
    */


});


