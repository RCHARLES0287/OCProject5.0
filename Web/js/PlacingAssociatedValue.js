/*

$('#dimensions_deroulant').change(function (event){
    new PlacingAssociatedValue('#dimensions_deroulant', 'prix', 'prix_article');
});
*/


class PlacingAssociatedValue {
    /**
     *
     * @param objetSelect la balise (le selecteur) sur laquelle porte le changement
     * @param dataName nom de l'attribut data recherché
     * @param newLocation l'emplacement cible (sélecteur)
     */
    constructor(objetSelect, dataName, newLocation) {
        /// la balise (le selecteur) sur laquelle porte le changement
        this.objetSelect = objetSelect;
        /// nom de l'attribut data recherché
        this.dataName = dataName;
        /// l'emplacement cible (sélecteur)
        this.newLocation = newLocation;


        // this.joinedValue = $('option[value="'+$(objetSelect).val()+'"]', $(objetSelect)).data(associatedValue);

        console.log(this.dataName, this.newLocation);

        this.placeAssociatedValue();
    }

    placeAssociatedValue () {
        console.log('La fonction placeAssociatedValue se lance correctemennt');
        console.log(this.newLocation);
        console.log(this.dataName);

        $(this.objetSelect).change(function (event){
            console.log('On est entré dans l\'event change');
            /// valeur associée au dataName
            let dataValue = $('option[value="'+$(this.objetSelect).val()+'"]', $(this.objetSelect)).data(this.dataName);
            console.log(dataValue);
            $(this.newLocation).html(dataValue);
        }.bind(this));



        // document.getElementById(this.newPlace).innerText = this.joinedValue;
        // console.log($('+ this.newPlace +'));
        // $('+ this.newPlace +').text($(this).joinedValue);
    }
}





/*

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


/*

<select name="status" id="status">
    <option value="1">Active</option>
    <option value="0">Inactive</option>
</select>


$(function(){

    $("#status").change(function(){
        var status = this.value;
        alert(status);
        if(status=="1")
            $("#icon_class, #background_class").hide();// hide multiple sections
    });

});


<section class="infos_et_commande">
    <select id="dimensions_deroulant" class="form-select dimensions_article" aria-label="Default select example">
        <option selected>Dimensions</option>
        {% for photoTarif in photoTarifs %}
        <option class="menu_deroulant_dimensions" value="{{ photoTarif.dimensions_id }}">{{ allDimensions[photoTarif.dimensions_id].dimensions }}</option>
        {% endfor %}
    </select>
    <div class="prix_article">
        Prix : {{ photoTarifs.prix }} €
    </div>
</section>
*/

