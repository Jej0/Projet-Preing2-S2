document.getElementById("simpleNewsletterForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Empêche le rechargement de la page
    
    const email = document.getElementById("simpleEmailInput").value;
    const messageElement = document.getElementById("simpleMessage");
    
    // Vérification simple de l'email
    if (!email.includes("@") || !email.includes(".")) {
        messageElement.textContent = "Email non valide !";
        messageElement.style.color = "red";
        return;
    }
    
    // Envoi des données
    fetch("../../../scripts_php/save_email.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "email=" + encodeURIComponent(email)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageElement.textContent = "Merci ! Email enregistré.";
            messageElement.style.color = "green";
        } else {
            messageElement.textContent = data.message;
            messageElement.style.color = "red";
        }
    })
    .catch(error => {
        messageElement.textContent = "Erreur de connexion.";
        messageElement.style.color = "red";
    });
});

// Timer Cadeau
function startCountdown() {
    let endDate = new Date();
    endDate.setSeconds(endDate.getSeconds() + 300);
    let hasSwitched = false;

    function updateTimer() {
        const now = new Date();
        let diff = endDate - now;

        if (!hasSwitched && diff <= 3000) {
            endDate = new Date();
            endDate.setDate(endDate.getDate() + 2);
            endDate.setHours(endDate.getHours() + 8);
            endDate.setMinutes(endDate.getMinutes() + 36);
            endDate.setSeconds(endDate.getSeconds() + 12);
            hasSwitched = true;
            diff = endDate - now;

            document.getElementById('debloquageText').classList.add('hidden');
            document.getElementById('timeErreur').classList.remove('hidden');
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('days').textContent = days.toString().padStart(2, '0');
        document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');

        requestAnimationFrame(updateTimer);
    }

    updateTimer();
}

document.addEventListener('DOMContentLoaded', startCountdown);