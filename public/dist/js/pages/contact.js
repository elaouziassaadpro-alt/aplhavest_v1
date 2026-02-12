document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('input-search');
    const tableRows = document.querySelectorAll('.search-items');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();

        tableRows.forEach(row => {
            // Récupère le nom et la raison sociale (ou autre colonne si besoin)
            const name = row.cells[1].textContent.toLowerCase();
            const raisonSocial = row.cells[2].textContent.toLowerCase(); // ou changer index si ce n'est pas la bonne colonne

            // Affiche la ligne si le query match name ou raisonSocial
            if (name.includes(query) || raisonSocial.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
