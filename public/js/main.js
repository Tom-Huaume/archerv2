//Rendre les lignes du tableau cliquable (avec lien)
document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll("tr[data-href]");
    rows.forEach(row => {
        row.addEventListener("click", () => {
            window.location.href = row.dataset.href;
        })
    })
});

//Montrer/masquer formulaire lieu
function showHideLieu() {
    let formLieu = document.getElementById("formLieu");
    let labelLieu = document.getElementById("labelLieu");
    let listLieu = document.getElementById("listLieu");
    if (formLieu.style.display === "block") {
        formLieu.style.display = "none";
        labelLieu.style.display = "block";
        listLieu.style.display = "block";
    } else {
        formLieu.style.display = "block";
        labelLieu.style.display = "none";
        listLieu.style.display = "none";
    }
}

//Montrer/masquer formulaire etape
function showHideEtape() {
    let etapeToHide = document.getElementById("etapeToHide");
    let btnEtapeToHide = document.getElementById("btnEtapeToHide");
    if (etapeToHide.style.display === "block") {
        etapeToHide.style.display = "none";
        btnEtapeToHide.style.display = "block";
    } else {
        etapeToHide.style.display = "block";
        btnEtapeToHide.style.display = "none";
        // btnToHide.innerText = "Annuler";
    }
}

//Montrer/masquer formulaire trajet
function showHideTrajet() {
    let trajetToHide = document.getElementById("trajetToHide");
    let btnTrajetToHide = document.getElementById("btnTrajetToHide");
    if (trajetToHide.style.display === "block") {
        trajetToHide.style.display = "none";
        btnTrajetToHide.style.display = "block";
    } else {
        trajetToHide.style.display = "block";
        btnTrajetToHide.style.display = "none";
        // btnToHide.innerText = "Annuler";
    }
}

//Afficher/masquer les champs lieuDépart d'un trajet
let selectElem = document.getElementById('trajet_clubDefaut');

// Quand une nouvelle <option> est selectionnée
selectElem.addEventListener('change', function() {
    let lieuToHide = document.getElementById("lieuToHide");
    if (lieuToHide.style.display === "block") {
        lieuToHide.style.display = "none";
    } else {
        lieuToHide.style.display = "block";
    }
})
