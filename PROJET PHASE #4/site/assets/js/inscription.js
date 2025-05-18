document.addEventListener('DOMContentLoaded', function() {
    // Éléments du formulaire
    const form = document.getElementById('register-form');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const passwordToggle = document.getElementById('password-toggle');
    const confirmPasswordToggle = document.getElementById('confirm-password-toggle');
    const passwordCounter = document.getElementById('password-counter');
    const confirmPasswordCounter = document.getElementById('confirm-password-counter');

    // Initialisation des compteurs
    updateCharCounter('password', 'password-counter');
    updateCharCounter('confirm-password', 'confirm-password-counter');

    // Fonction de validation du nom d'utilisateur
    function validateUsername() {
        const regex = /^[a-zA-Z0-9_]{3,}$/;
        if (!regex.test(usernameInput.value)) {
            usernameInput.setCustomValidity("3 caractères minimum, lettres, chiffres et underscores seulement");
            return false;
        }
        usernameInput.setCustomValidity("");
        return true;
    }

    // Fonction de validation de l'email
    function validateEmail() {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(emailInput.value)) {
            emailInput.setCustomValidity("Veuillez entrer une adresse email valide");
            return false;
        }
        emailInput.setCustomValidity("");
        return true;
    }

    // Fonction de validation du mot de passe
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

    // Fonction de vérification de la correspondance des mots de passe
    function validatePasswordMatch() {
        const match = passwordInput.value === confirmPasswordInput.value;
        const matchElement = document.getElementById('password-match');

        if (confirmPasswordInput.value) {
            matchElement.textContent = match ? "✓ Les mots de passe correspondent" : "✗ Les mots de passe ne correspondent pas";
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

    // Fonction pour basculer la visibilité du mot de passe
    function togglePasswordVisibility(input, icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Fonction pour mettre à jour le compteur de caractères
    function updateCharCounter(inputId, counterId) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        
        if (!input || !counter) return;
        
        const maxLength = parseInt(input.getAttribute('maxlength')) || 32;
        const remaining = maxLength - input.value.length;
        
        counter.textContent = remaining + " caractère(s) restant(s)";
        counter.style.color = remaining < 5 ? 'red' : '#666';
    }

    // Écouteurs d'événements
    usernameInput.addEventListener('input', validateUsername);
    emailInput.addEventListener('input', validateEmail);
    
    passwordInput.addEventListener('input', function() {
        updateCharCounter('password', 'password-counter');
        validatePassword();
        if (confirmPasswordInput.value) validatePasswordMatch();
    });
    
    confirmPasswordInput.addEventListener('input', function() {
        updateCharCounter('confirm-password', 'confirm-password-counter');
        validatePasswordMatch();
    });

    passwordToggle.addEventListener('click', function() {
        togglePasswordVisibility(passwordInput, passwordToggle);
    });

    confirmPasswordToggle.addEventListener('click', function() {
        togglePasswordVisibility(confirmPasswordInput, confirmPasswordToggle);
    });

    // Validation du formulaire avant soumission
    form.addEventListener('submit', function(event) {
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