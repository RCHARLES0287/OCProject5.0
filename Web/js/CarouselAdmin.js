
class CarouselAdmin {

    constructor() {
        this.dragDropPhotoCarousel();
        this.resetDroppedPhotoCarousel();
    }

    dragDropPhotoCarousel () {
        $('.all_photos').on({
            dragstart: function (e) {
                let idPhoto = $(this).attr('id');
                $(this).css('opacity', '0.5');
                /* on garde l'identifiant en mémoire */
                e.originalEvent.dataTransfer.setData('text/plain', idPhoto);
            },
            dragend: function () {
                // let id = $(this).attr('id');
                $(this).css('opacity', '1');
            }
        });
        $('.zone_cible').on({
            dragover: function (e) {
                e.preventDefault();
            },
            drop: function (e) {
                e.preventDefault();
                let idPhoto = e.originalEvent.dataTransfer.getData('text/plain');
                $(this).children('div').html($("#" + idPhoto + ' img').clone());
                //dans append
                $(this).children('input').val(idPhoto);
            }
        });
    }

    resetDroppedPhotoCarousel () {
        $('.reset_dropzone').click(function (){
            //Les trois méthodes ci-dessous sont équivalentes
            // $(this).parent().children('.zone_cible').children('input').val('');
            // $(this).siblings('.zone_cible').children('input').val('');
            $(this).parent().find('.zone_cible > input').val('');
            $(this).parent().children('.zone_cible').children('div').html(' Emplacement ' + $(this).parent().children('.zone_cible').attr('id').slice(5));
        })
    }
}
