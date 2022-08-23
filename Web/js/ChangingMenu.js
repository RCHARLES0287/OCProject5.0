
/*Version alternative du changement de menu. J'utilise plutôt le changement de menu directement dans la vue avec Twig mais
je garde cette version pour mémoire*/

// noinspection JSUnusedGlobalSymbols
class ChangingMenu {
    /**
     *
     * @param baliseTest La balise dont on va lire l'attribut value (true si l'admin est connecté, sinon false)
     * @param baliseCible La balise dans laquelle on va remplacer l'ancien menu par le nouveau
     * @param arrayTagsAndLinks Le tableau contenant les intitulés du menu et le lien associé à chacun
     */
    constructor(baliseTest, baliseCible, arrayTagsAndLinks) {
        this.baliseCible = baliseCible;
        this.arrayTagsAndLinks = arrayTagsAndLinks;

        this.testCondition(baliseTest);
    }

    testCondition (baliseTest) {
        let connectionStatus = $(baliseTest).val();
        if (connectionStatus === true) {
            this.menuReplacement();
        }

    }

    menuReplacement () {
        let newMenu = null;
        this.arrayTagsAndLinks.forEach(function (currentName, currentLink) {
            newMenu += '<li class="nav-item"><a class="nav-link" href="' + currentLink + '">' + currentName + '</a></li>';
        });
        $(this.baliseCible).html(newMenu);
    }
}


