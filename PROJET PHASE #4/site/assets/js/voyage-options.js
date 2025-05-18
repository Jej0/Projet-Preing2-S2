// assets/js/voyage-options.js

function initVoyageOptions(config) {
    const { voyageId, reservationId, basePrice, currentTotalPrice, currentOptions } = config;

    // Fonction pour charger les options d'une étape
    function loadOptionsForEtape(etapeId) {
        fetch(`../scripts_php/get_options.php?voyage_id=${voyageId}&etape_id=${etapeId}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                const container = document.querySelector(`#options-${etapeId}`);
                if (!container) return;

                container.innerHTML = '';

                if (!data.success || !data.options || Object.keys(data.options).length === 0) {
                    container.innerHTML = `
                        <div class="voyage-alert">
                            <i class="fas fa-info-circle"></i> Aucune option disponible pour cette étape.
                        </div>`;
                    return;
                }

                const optionTypes = {
                    'activite': 'Activités',
                    'hebergement': 'Hébergements',
                    'restauration': 'Restauration',
                    'transport': 'Transports'
                };

                let hasOptions = false;

                Object.entries(optionTypes).forEach(([optionType, displayName]) => {
                    if (data.options[optionType]?.length > 0) {
                        hasOptions = true;
                        const group = document.createElement('div');
                        group.className = 'option-subgroup';
                        group.innerHTML = `
                            <h5>${displayName}</h5>
                            <select class="option-select" 
                                    data-option-type="${optionType}" 
                                    data-etape-id="${etapeId}"
                                    name="${optionType}_${etapeId}">
                                <option value="">Aucune sélection</option>
                            </select>`;

                        const select = group.querySelector('select');

                        data.options[optionType].forEach(option => {
                            const opt = document.createElement('option');
                            opt.value = option.id_option;
                            opt.textContent = `${option.nom} - ${option.description} (${option.prix_par_personne} €)`;
                            opt.dataset.price = option.prix_par_personne;
                            select.appendChild(opt);
                        });

                        const currentOption = currentOptions[`etape_${etapeId}`]?.[optionType];
                        if (currentOption) select.value = currentOption;

                        select.addEventListener('change', updatePrice);
                        container.appendChild(group);
                    }
                });

                if (!hasOptions) {
                    container.innerHTML = `
                        <div class="voyage-alert">
                            <i class="fas fa-info-circle"></i> Aucune option disponible pour cette étape.
                        </div>`;
                }
            })
            .catch(error => {
                console.error('Error loading options:', error);
                const container = document.querySelector(`#options-${etapeId}`);
                if (container) {
                    container.innerHTML = `
                        <div class="voyage-alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Erreur lors du chargement des options.
                        </div>`;
                }
            });
    }

    function updatePrice() {
        let newTotalPrice = basePrice;
        const options = {};

        document.querySelectorAll('.option-select').forEach(select => {
            const etapeId = select.dataset.etapeId;
            const optionType = select.dataset.optionType;

            if (!options[`etape_${etapeId}`]) options[`etape_${etapeId}`] = {};

            if (select.value) {
                options[`etape_${etapeId}`][optionType] = parseInt(select.value);
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption?.dataset.price) {
                    newTotalPrice += parseInt(selectedOption.dataset.price);
                }
            }
        });

        document.getElementById('total-price').textContent =
            new Intl.NumberFormat('fr-FR').format(newTotalPrice);
    }

    // Initialisation
    document.querySelectorAll('.etape-card').forEach(card => {
        loadOptionsForEtape(card.dataset.etapeId);
    });

    document.getElementById('total-price').textContent =
        new Intl.NumberFormat('fr-FR').format(currentTotalPrice);
}