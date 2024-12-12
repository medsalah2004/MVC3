function validerformulaire() {
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;

    if (!email || !password) {
        alert('Veuillez remplir tous les champs.');
        return false; // Empêche la soumission
    }
    return true; // Autorise la soumission
}