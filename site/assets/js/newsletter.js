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