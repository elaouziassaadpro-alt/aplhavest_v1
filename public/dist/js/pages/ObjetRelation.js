document.addEventListener('DOMContentLoaded', function () {

    const checkbox = document.getElementById('mandataire_id');
    const elements = document.querySelectorAll('.mandat-hide');
    const label = document.getElementById('mandataireLabel');

    checkbox.addEventListener('change', function () {
        if (checkbox.checked) {
        label.textContent = 'Oui';
        elements.classList.remove('d-none');
    }
        if (this.checked) {
            elements.forEach(el => el.style.display = 'block');
            label.textContent = 'Oui';
        } else {
            elements.forEach(el => el.style.display = 'none');
            label.textContent = 'Non';
        }
    });

});
