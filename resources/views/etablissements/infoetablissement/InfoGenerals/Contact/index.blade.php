@extends('layouts.app')

@section('content')
<script src="{{ asset('dist/js/pages/contact.js') }}"></script>
<div class="container-fluid mw-100">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Contacts</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Contacts</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-end">
                    <img src="{{ asset('dist/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="card card-body boutons_header">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative">
                    <input type="text" class="form-control product-search ps-5" id="input-search" placeholder="Recherche rapide...">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                <div class="action-btn show-btn" style="display: none">
                    <a href="javascript:void(0)" id="delete-selection" class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </a>
                </div>

                <a href="/" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-users text-white me-1 fs-5"></i> Ajouter un contact
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table search-table align-middle text-nowrap">
                <thead class="header-item">
                    <tr>
                        <th>
                            <div class="n-chk align-self-center text-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input primary" id="contact-check-all" />
                                    <label class="form-check-label" for="contact-check-all"></label>
                                    <span class="new-control-indicator">Tous</span>
                                </div>
                            </div>
                        </th>
                        <th>Raison sociale</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Fonction</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr class="search-items" data-id="{{ $contact->id }}">
                        <td>
                            <div class="n-chk align-self-center text-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input contact-chkbox" data-contact-id="{{ $contact->id }}" />
                                </div>
                            </div>
                        </td>
                        <td>{{ $contact->infoGeneral->raisonSocial }}</td>
                        <td>{{ $contact->nom }}</td>
                        <td>{{ $contact->prenom }}</td>
                        <td>{{ $contact->fonction ?? '—' }}</td>
                        <td>{{ $contact->telephone ?? '—' }}</td>
                        <td>{{ $contact->email ?? '—' }}</td>
                        <td>
                            <a href="/" class="btn btn-sm btn-info">Voir</a>
                            <a href="/" class="btn btn-sm btn-primary">Modifier</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    axios.defaults.headers.common['X-CSRF-TOKEN'] =
        document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const checkboxes = document.querySelectorAll('.contact-chkbox');
    const deleteBtn   = document.getElementById('delete-selection');
    const actionBtns  = document.querySelectorAll('.action-btn.show-btn');

    function toggleButtons() {
        const anyChecked = [...checkboxes].some(cb => cb.checked);
        actionBtns.forEach(btn => btn.style.display = anyChecked ? 'inline-flex' : 'none');
    }

    checkboxes.forEach(cb => cb.addEventListener('change', toggleButtons));

    // Delete selected contacts
    function deleteSelected() {
        const ids = [...checkboxes]
            .filter(cb => cb.checked)
            .map(cb => cb.dataset.contactId);

        if (!ids.length) return;

        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette action supprimera définitivement les contacts sélectionnés !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post("{{ route('contacts.delete-multiple') }}", { ids: ids })
                    .then(() => {
                        // Remove rows from table
                        ids.forEach(id => {
                            const row = document.querySelector(`.contact-chkbox[data-contact-id="${id}"]`).closest('tr');
                            row.remove();
                        });
                        Swal.fire('Supprimé !', 'Les contacts ont été supprimés.', 'success');
                        toggleButtons();
                    })
                    .catch(err => {
                        Swal.fire('Erreur', 'Impossible de supprimer les contacts.', 'error');
                        console.error(err);
                    });
            }
        });
    }

    deleteBtn.addEventListener('click', deleteSelected);

});
</script>
@endsection
