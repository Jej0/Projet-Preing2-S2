document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('theme-toggle');

    // Fonctions de gestion des cookies
    const setCookie = (name, value, days) => {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
    };

    const getCookie = (name) => {
        return document.cookie
            .split('; ')
            .find(row => row.startsWith(`${name}=`))
            ?.split('=')[1];
    };

    // Appliquer le thème
    const applyTheme = (theme) => {
        document.documentElement.setAttribute('data-theme', theme);
        setCookie('theme', theme, 365); // Cookie valide 1 an
        updateToggleButton(theme);
    };

    // Mise à jour de l'icône
    const updateToggleButton = (theme) => {
        const icon = themeToggle.querySelector('i');
        icon.className = theme === 'dark' 
            ? 'fa-solid fa-sun' 
            : 'fa-solid fa-moon';
    };

    // Détection initiale
    const savedTheme = getCookie('theme') || 'light';
    applyTheme(savedTheme);

    // Gestionnaire de clic
    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
    });
});