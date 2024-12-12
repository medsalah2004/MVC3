function toggleEdit(fieldId) {
    const field = document.getElementById(fieldId);

    if (field.readOnly) {
        field.readOnly = false; // Permet de modifier
        field.style.backgroundColor = "#fff"; // Change la couleur de fond
        field.focus(); // Met le focus sur le champ
    } else {
        field.readOnly = true; // Rend le champ en lecture seule
        field.style.backgroundColor = "#e9ecef"; // Rétablit la couleur
    }
}

// Fonction pour enregistrer les modifications
function saveChanges() {
    const name = document.getElementById("nom").value;
    const prenom = document.getElementById("profilefn").value;
    const email = document.getElementById("profileEmail").value;
    const password = document.getElementById("profilepassword").value;
    const dateofbirth = document.getElementById("dateofbirth").value;

    // Exemple de sauvegarde côté client (affiche dans la console)
    console.log("Nom :", name);
    console.log("Email :", email);
    console.log("password :", password);
    console.log("prenom:", prenom);
    console.log("dateofbirth :", dateofbirth);

    alert("Modifications enregistrées !");
}
