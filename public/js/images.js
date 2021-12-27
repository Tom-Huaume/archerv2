window.onload = () => {
    // Gestion de sboutons "supprimer"
    let links = document.querySelectorAll("[data-delete]");

    for(link of links){
        //écoute click
        link.addEventListener("click", function (e){
            //On empêche la navigation
            e.preventDefault()

            //on demande confirmation
            if(confirm("Voulez-vous supprimer cette photo ?")){
                //On envoie une requête Ajax vers le href du lien en DELETE
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    //on récupère la réponse en JSON
                    response => response.json()
                ).then(data => {
                    if(data.success)
                        this.parentElement.remove()
                    else
                        alert(data.error)
                }).catch(e => alert(e))

            }
        })
    }
}