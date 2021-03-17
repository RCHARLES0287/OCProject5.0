$('#dimensions_deroulant').change(function (event){
    // Ciblage de l'attribut "value" que l'on passe en paramètre à la nouvelle instance de MenuDeroulantSelection
    new MenuDeroulantSelection($(this).val());
    console.log();
    return true;
});