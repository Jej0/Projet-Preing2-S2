// assets/js/ban-user.js

function setupBanButtons() {
    document.querySelectorAll('.ban-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            const button = this;
            const isBanned = button.getAttribute('data-banned') === '1';

            if (!userId) return;

            button.disabled = true;

            fetch('../scripts_php/ban_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_user: userId,
                    ban: !isBanned
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (isBanned) {
                            button.textContent = 'Bannir';
                            button.setAttribute('data-banned', '0');
                        } else {
                            button.textContent = 'Banni';
                            button.setAttribute('data-banned', '1');
                        }
                    } else {
                        button.textContent = 'Erreur';
                    }
                    button.disabled = false;
                })
                .catch(() => {
                    button.textContent = 'Erreur';
                    button.disabled = false;
                });
        });
    });
}

// Initialisation quand le DOM est chargé
document.addEventListener('DOMContentLoaded', setupBanButtons);