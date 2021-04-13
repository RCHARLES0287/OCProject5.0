
new PlacingAssociatedValue('#dimensions_deroulant', 'prix', '#prix_article');



$("#validation_article").click(function(e){
    e.preventDefault();
    console.log('On est entré dans la requête ajax pour la validation du formulaire')

    $.ajax({
        url : '/validateonearticle', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP

        data : 'idPhoto=' + id_photo + 'idDimensions' + id_dimensions + 'nombreArticles' + nombre_articles,
        // dataType : 'json' // Le type de données à recevoir, ici, du json.
    });
    console.log('La requête ajax pour la validation du formulaire fonctionne')

});


