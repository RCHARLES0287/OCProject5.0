$(function () {

    /*
    new AddingOneToQuantityAjax('#qte_plus_button_{{ unArticle.orderedArticle.id }}',
        '#number_of_articles_panier_span_{{ unArticle.orderedArticle.id }}',
        {'idPhoto': 'input[name=id_photo]', 'idDimensions': 'input[name=id_dimensions]', 'nombreArticles': 'input[name=nombre_articles]'},
        '/addonetoquantity');

    */

    new AddingOneToQuantityAjax('id_photo', 'id_dimensions', 'nombre_articles', '#qte_plus_button');



    /*
    new RemovingOneToQuantityAjax('#qte_moins_button_{{ unArticle.orderedArticle.id }}',
        '#number_of_articles_panier_span_{{ unArticle.orderedArticle.id }}',
        {'idPhoto': 'input[name=id_photo]', 'idDimensions': 'input[name=id_dimensions]', 'nombreArticles': 'input[name=nombre_articles]'},
        '/removeonetoquantity');
    */


});


