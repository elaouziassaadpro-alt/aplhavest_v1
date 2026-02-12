document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('departement_gestion_id');
        const selectContainer = document.getElementById('departement_gestion_data_container');
        const labelCheckbox = document.getElementById('labelcheckbox');

        

        // Toggle visibility on load
        selectContainer.style.display = checkbox.checked ? 'block' : 'none';

        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                selectContainer.style.display = 'block';
                labelCheckbox.textContent = 'Yes';

            } else {
                selectContainer.style.display = 'none';
                labelCheckbox.textContent = 'No';
            }
        });
    });