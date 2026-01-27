document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('formeInputpayresidence');
    const dropdown = document.getElementById('formeDropdownpayresidence');
    const hidden = document.getElementById('formeSelectpayresidence');

    if (!input || !dropdown || !hidden) {
        console.warn('Custom select: éléments introuvables');
        return;
    }

    const items = dropdown.querySelectorAll('li');

    // Ouvrir la liste au focus
    input.addEventListener('focus', () => {
        dropdown.style.display = 'block';
    });

    // Filtrer les pays pendant la saisie
    input.addEventListener('input', () => {
        const value = input.value.toLowerCase();

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(value) ? 'block' : 'none';
        });
    });

    // Sélection d’un pays
    items.forEach(item => {
        item.addEventListener('click', () => {
            input.value = item.textContent.trim();
            hidden.value = item.dataset.value;
            dropdown.style.display = 'none';
        });
    });

    // Fermer si clic خارج
    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

});
