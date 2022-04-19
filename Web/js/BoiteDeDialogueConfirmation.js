class BoiteDeDialogueConfirmation {

    constructor(boutonCible, texteBoiteDeDialogue) {
        this.texteBoiteDeDialogue = texteBoiteDeDialogue;

        $(boutonCible).click(this.testConfirmation.bind(this));

    }

    testConfirmation ()
    {
        // Si la fonction retourne false, ça déclenche le e.preventdefault et donc l'exécution du formulaire est annulée.
        // Dans le cas contraire, le formulaire est validé normalement
        return confirm(this.texteBoiteDeDialogue);
    }
}

