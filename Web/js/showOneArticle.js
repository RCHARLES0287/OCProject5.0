$(function () {
    new PlacingAssociatedValue('#dimensions_deroulant', 'prix', '#prix_article');

    new ValidatingFormAjax('#formulaire_panier',
        '/validateonearticle',
        {'idPhoto': 'input[name=id_photo]', 'idDimensions': 'select[name=id_dimensions]', 'nombreArticles': 'select[name=nombre_articles]'}
    );
});

