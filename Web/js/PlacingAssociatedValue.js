class PlacingAssociatedValue {
    newPlace;
    joinedValue;
    constructor(objetSelect, associatedValue, newLocation) {

        this.joinedValue = $('option[value="'+$(objetSelect).val()+'"]', $(objetSelect)).data(associatedValue);
        this.newPlace = newLocation;
        console.log(this.joinedValue, this.newPlace);

        this.placeAssociatedValue();
    }

    placeAssociatedValue () {
        console.log('La fonction placeAssociatedValue se lance correctemennt');
        console.log(this.newPlace);
        console.log(this.joinedValue);
        document.getElementById(this.newPlace).innerText = this.joinedValue;
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

