document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('createaccount-form');
    const roleSelect = document.getElementById('roleSelect');
    const signInButton = document.getElementById('signin2');
    const roleLinks = {
        client: 'index.html',
        admin: 'admin.html',
        guide: 'guide.html',
    };

    // Fonction pour valider tous les champs du formulaire
    function validateForm() {
        const nom = document.getElementById('nom1').value.trim();
        const prenom = document.getElementById('prenom1').value.trim();
        const email = document.getElementById('email1').value.trim();
        const password = document.getElementById('mdp1').value;
        const confirmPassword = document.getElementById('mdp2').value;
        const dateOfBirth = document.getElementById('DateN1').value;
        const role = roleSelect.value;

        // Vérifie si tous les champs sont remplis
        if (
            nom.length >= 3 &&
            prenom.length >= 3 &&
            email &&
            password.length >= 6 &&
            confirmPassword.length >= 6 &&
            password === confirmPassword &&
            dateOfBirth &&
            role
        ) {
            return true;
        }
        return false;
    }

    // Vérifie le formulaire à chaque modification
    form.addEventListener('input', () => {
        if (validateForm()) {
            signInButton.style.display = 'block'; // Affiche le bouton
            const selectedRole = roleSelect.value;

            // Ajoute le lien selon le rôle sélectionné
            signInButton.href = roleLinks[selectedRole];
        } else {
            signInButton.style.display = 'none'; // Masque le bouton
        }
    });
});