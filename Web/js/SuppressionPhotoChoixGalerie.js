$(function () {

    new AffichagePhotosPourSuppression();

    new BoiteDeDialogueConfirmation("#bouton_validation_suppression_photos",
        "Etes-vous certain de vouloir supprimer ces photos DEFINITIVEMENT?");

});
