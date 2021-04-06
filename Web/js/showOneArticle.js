/*

$('#dimensions_deroulant').change(function (event){
    // Ciblage de l'attribut "value" que l'on passe en paramètre à la nouvelle instance de MenuDeroulantSelection
    new PlacingAssociatedValue($('option[value="'+$(this).val()+'"]', $(this)).data('prix'), 'prix_article');
    console.log();
    return true;
});


// $(this).data('prix')

$('#toto').change(function (event){
    // Ciblage de l'attribut "value" que l'on passe en paramètre à la nouvelle instance de MenuDeroulantSelection
    new PlacingAssociatedValue($('option[value="'+$(this).val()+'"]', $(this)).data('tata'), 'tutu');
    console.log();
    return true;
})


// Ne peut travailler qu'avec un select '
new Nouvelleclasse ('#toto', 'tata', '#tutu');


new NouvelleClasse ('#dimensions_deroulant', 'prix', '#prix_article');
*/
/*

$('#dimensions_deroulant').change(function (event){
   new PlacingAssociatedValue('#dimensions_deroulant', 'prix', 'prix_article');
});
*/

new PlacingAssociatedValue('#dimensions_deroulant', 'prix', '#prix_article');

/*

class NouvelleClasse {
    constructor(objetSelect, associatedValue, newLocation) {

        let joinedValue = $('option[value="'+$(objetSelect).val()+'"]', $(objetSelect)).data(associatedValue);
        let newPlace = newLocation;

        this.placeAssociatedValue();
    }

    placeAssociatedValue () {
        console.log('La fonction placeAssociatedValue se lance correctemennt');
        console.log(this.place);
        $('.place').text($(this).joinedValue);
    }
}

class PlacingAssociatedValue {
    constructor(value, associatedValue, newLocation) {
        // const menuValue = value;
        let initialValue = value;
        let joinedValue = associatedValue;
        let place = newLocation;
        console.log(initialValue, joinedValue, place);
        this.placeAssociatedValue();
    }

    placeAssociatedValue () {
        console.log('La fonction placeAssociatedValue se lance correctemennt');
        console.log(this.place);
        $('.place').text($(this).joinedValue);
    }
}
*/
