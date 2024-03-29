class AppelAjax {

    // Exécute un appel AJAX GET
    // Prend en paramètres l'URL cible et la fonction callback appelée en cas de succès
    ajaxGet (url, callback) {
        let req = new XMLHttpRequest();
        req.open("GET", url, true);
        req.addEventListener("load", function () {
            if (req.status >= 200 && req.status < 400) {
                // Appelle la fonction callback en lui passant la réponse de la requête
                callback (req.responseText);
            } else {
                console.error (req.status + " " + req.statusText + " " + url);
            }
        });

        req.addEventListener ("error", function () {
            console.error ("Erreur réseau avec l'URL " + url);
        });

        req.send (null);
    }

    callAndExtract (url, callback) {
        this.ajaxGet (url, function (response) {
            callback(response);
        }.bind (this))
    }
}

