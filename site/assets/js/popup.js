// Pop-up première visite
if (!localStorage.getItem('hasVisited')) {
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('welcomePopup');
        const blur = document.getElementById('backgroundBlur');
        const acceptBtn = document.getElementById('acceptBtn');
        const rejectBtn = document.getElementById('rejectBtn');
        
        // Affiche la pop-up et le flou
        popup.style.display = 'block';
        blur.style.display = 'block';
        
        // Bouton "Entrer"
        acceptBtn.addEventListener('click', function() {
            popup.style.display = 'none';
            blur.style.display = 'none';
            localStorage.setItem('hasVisited', 'true');
        });
        
        // Bouton "Quitter"
        rejectBtn.addEventListener('click', function() {
            window.location.href = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
        });
    });
}