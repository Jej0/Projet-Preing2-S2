document.addEventListener('DOMContentLoaded', function () {
    // Elements du formulaire
    const form = document.getElementById('register-form');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const passwordToggle = document.getElementById('password-toggle');
    const confirmPasswordToggle = document.getElementById('confirm-password-toggle');

    // Fonctions de validation
    function validateUsername() {
        const regex = /^[a-zA-Z0-9_]{3,}$/;
        if (!regex.test(usernameInput.value)) {
            usernameInput.setCustomValidity("3 caractères minimum, lettres, chiffres et underscores seulement");
            return false;
        }
        usernameInput.setCustomValidity("");
        return true;
    }

    function validateEmail() {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(emailInput.value)) {
            emailInput.setCustomValidity("Veuillez entrer une adresse email valide");
            return false;
        }
        emailInput.setCustomValidity("");
        return true;
    }

    function validatePassword() {
        const password = passwordInput.value;
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^a-zA-Z0-9]/.test(password)
        };

        // Mise à jour visuelle des exigences
        document.getElementById('req-length').style.color = requirements.length ? 'green' : 'red';
        document.getElementById('req-uppercase').style.color = requirements.uppercase ? 'green' : 'red';
        document.getElementById('req-number').style.color = requirements.number ? 'green' : 'red';
        document.getElementById('req-special').style.color = requirements.special ? 'green' : 'red';

        if (!requirements.length || !requirements.uppercase || !requirements.number || !requirements.special) {
            passwordInput.setCustomValidity("Le mot de passe ne respecte pas toutes les exigences");
            return false;
        }
        passwordInput.setCustomValidity("");
        return true;
    }

    function validatePasswordMatch() {
        const match = passwordInput.value === confirmPasswordInput.value;
        const matchElement = document.getElementById('password-match');

        if (confirmPasswordInput.value) {
            matchElement.textContent = match ? "Les mots de passe correspondent" : "Les mots de passe ne correspondent pas";
            matchElement.style.color = match ? 'green' : 'red';
        } else {
            matchElement.textContent = "";
        }

        if (!match) {
            confirmPasswordInput.setCustomValidity("Les mots de passe ne correspondent pas");
            return false;
        }
        confirmPasswordInput.setCustomValidity("");
        return true;
    }

    // Gestionnaires d'événements
    usernameInput.addEventListener('input', validateUsername);
    emailInput.addEventListener('input', validateEmail);
    passwordInput.addEventListener('input', function () {
        validatePassword();
        updateCharCounter('password', 'password-counter');
        if (confirmPasswordInput.value) validatePasswordMatch();
    });
    confirmPasswordInput.addEventListener('input', validatePasswordMatch);

    // Fonction pour basculer la visibilité du mot de passe
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Fonction pour mettre à jour le compteur de caractères
    function updateCharCounter(inputId, counterId) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        const maxLength = input.maxLength;
        const remaining = maxLength - input.value.length;

        counter.textContent = remaining + " caractère(s) restant(s)";
        counter.style.color = remaining < 5 ? '#ff0000' : '#666';
    }

    // Écouteurs pour les boutons d'affichage du mot de passe
    passwordToggle.addEventListener('click', () => togglePasswordVisibility('password', 'password-toggle'));
    confirmPasswordToggle.addEventListener('click', () => togglePasswordVisibility('confirm-password', 'confirm-password-toggle'));

    // Validation du formulaire avant soumission
    form.addEventListener('submit', function (event) {
        if (!validateUsername() || !validateEmail() || !validatePassword() || !validatePasswordMatch()) {
            event.preventDefault();
            // Forcer l'affichage des messages d'erreur
            usernameInput.reportValidity();
            emailInput.reportValidity();
            passwordInput.reportValidity();
            confirmPasswordInput.reportValidity();
        }
    });
});