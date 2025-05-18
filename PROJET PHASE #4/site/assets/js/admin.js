let currentPage = 1;
const usersPerPage = 10;
let allUsers = [];

document.addEventListener('DOMContentLoaded', function () {
    // Récupérer tous les utilisateurs depuis le DOM
    const rows = document.querySelectorAll('#user-table-body tr');
    allUsers = Array.from(rows).map(row => ({
        id: row.querySelector('.ban-btn').getAttribute('data-user-id'),
        name: row.cells[0].textContent,
        email: row.cells[1].textContent,
        role: row.cells[2].textContent.trim(),
        status: row.cells[3].textContent.trim(),
        html: row.outerHTML
    }));

    setupBanButtons();
    setupPagination();
});

function setupBanButtons() {
    document.querySelectorAll('.ban-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            const isBanned = this.getAttribute('data-banned') === '1';
            const button = this;

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

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
                        // Mettre à jour l'interface utilisateur
                        const statusBadge = button.closest('tr').querySelector('.status-badge');
                        if (isBanned) {
                            button.innerHTML = '<i class="fas fa-ban"></i> Bannir';
                            button.setAttribute('data-banned', '0');
                            button.classList.remove('btn-unban');
                            button.classList.add('btn-ban');
                            statusBadge.textContent = 'Actif';
                            statusBadge.classList.remove('status-banned');
                            statusBadge.classList.add('status-active');
                        } else {
                            button.innerHTML = '<i class="fas fa-unlock"></i> Débannir';
                            button.setAttribute('data-banned', '1');
                            button.classList.remove('btn-ban');
                            button.classList.add('btn-unban');
                            statusBadge.textContent = 'Banni';
                            statusBadge.classList.remove('status-active');
                            statusBadge.classList.add('status-banned');
                        }
                    } else {
                        button.innerHTML = '<i class="fas fa-exclamation-circle"></i> Erreur';
                    }
                    button.disabled = false;
                })
                .catch(() => {
                    button.innerHTML = '<i class="fas fa-exclamation-circle"></i> Erreur';
                    button.disabled = false;
                });
        });
    });
}

function setupPagination() {
    const totalPages = Math.ceil(allUsers.length / usersPerPage);
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    const pageInfo = document.getElementById('page-info');

    function updatePagination() {
        pageInfo.textContent = `Page ${currentPage} sur ${totalPages}`;
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
        renderUsers();
    }

    function renderUsers() {
        const start = (currentPage - 1) * usersPerPage;
        const end = start + usersPerPage;
        const paginatedUsers = allUsers.slice(start, end);

        document.getElementById('user-table-body').innerHTML = paginatedUsers.map(u => u.html).join('');
        setupBanButtons();
    }

    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
        }
    });

    updatePagination();
}

function searchUsers() {
    const searchTerm = document.getElementById('user-search').value.toLowerCase();
    const filteredUsers = allUsers.filter(user =>
        user.name.toLowerCase().includes(searchTerm) ||
        user.email.toLowerCase().includes(searchTerm)
    );

    document.getElementById('user-table-body').innerHTML = filteredUsers.map(u => u.html).join('');
    setupBanButtons();
}

function sortTable(columnIndex) {
    allUsers.sort((a, b) => {
        const aValue = columnIndex === 0 ? a.name : a.email;
        const bValue = columnIndex === 0 ? b.name : b.email;
        return aValue.localeCompare(bValue);
    });

    currentPage = 1;
    renderUsers();
}