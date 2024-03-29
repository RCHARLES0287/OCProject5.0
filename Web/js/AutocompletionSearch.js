class AutocompletionSearch {

    constructor(baliseRecherche, urlAInterroger) {
        this.inputRecherche = baliseRecherche;
        this.urlAInterroger = urlAInterroger;

        this.autocompletionCall();
    }

    autocompletionCall()
    {
        $( this.inputRecherche ).autocomplete({
            source: function( request, response ) {
                $.ajax( {
                    url: this.urlAInterroger,
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                } );
            }.bind(this),
            minLength: 2,
            select: function (event, ui) {
                event.preventDefault();
                // window.location.href=window.location.protocol+'//'+window.location.host+'/showonephoto?photo_id='+ui.item.id;
                window.location.href='/showonephoto?photo_id='+ui.item.id;
            }

        } );
    }
}



