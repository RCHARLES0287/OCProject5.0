$('#dimensions_deroulant').change(function (event){
    // Ciblage de l'attribut "value" que l'on passe en paramètre à la nouvelle instance de MenuDeroulantSelection
    new PlacingAssociatedValue($(this).val(), $(this).data('prix'), 'prix_article');
    console.log();
    return true;
});


// $(this).data('prix')