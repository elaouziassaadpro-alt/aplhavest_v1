@extends('layouts.app')

@section('content')
<script src="{{ asset('dist/js/pages/update.js') }}"></script>
<script src="{{ asset('dist/js/pages/Administrateurupdate.js') }}"></script>
<script src="{{ asset('dist/js/pages/coordonneesBoncaireUpdate.js') }}"></script>

<script>
    // Data for Administrators section
    window.APP_DATA = {
        pays: @json($pays),
        ppes: @json($ppes)
    };
    
    // Data for Bank Details section
    const banquesData = @json($banques);
    const villesData = @json($villes);
</script>

<style>
/* Professional Locked Mode Styles */
.locked-mode input:not([type="checkbox"]), 
.locked-mode textarea, 
.locked-mode select {
    pointer-events: none;
    background-color: #f4f7f9 !important;
    border-color: #dee2e6 !important;
    color: #495057 !important;
}

.locked-mode input[type="file"] {
    display: none;
}

.locked-mode .btn-primary:not(.download-btn),
.locked-mode .update,
.locked-mode .ti-trash,
.locked-mode .btn-light-info[onclick],
.locked-mode #addContactRowBtn {
    display: none !important;
}

.locked-mode input[type="checkbox"], 
.locked-mode input[type="radio"] {
    pointer-events: none;
    cursor: not-allowed;
    opacity: 0.7;
}

.locked-mode .destroy {
    pointer-events: none;
    cursor: not-allowed;
    opacity: 0.5;
    display: none !important;
}

/* Edit Mode Highlighting */
.editing-mode {
    border: 1px solid #00abff !important;
    box-shadow: 0 0 10px rgba(0, 171, 255, 0.1);
}

.edit-section-btn i {
    transition: transform 0.2s;
}

.btn-circle {
    width: 35px;
    height: 35px;
    padding: 0;
}
</style>




<div class="container-fluid mw-100">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Ecom-Shop</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="./index.html">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Shop</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-end">
                    <img src="../../dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>
    <div class="card card-body">                
        <div class="card-body">
        <div class="mb-4">
            <h5 class="mb-0"> <span class="fw-semibold">Etablissement :</span> {{$etablissement->name}}</h5>
        </div>
        <div
            class="accordion accordion-flush"
            id="accordionFlushExample"
        >
                <div class="accordion-item" >
                <h2 class="accordion-header" id="flush-headingOne">
                    <button
                        class="accordion-button collapsed text-center justify-content-center"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseOne"
                        aria-expanded="false"
                        aria-controls="flush-collapseOne"
                        style="display: flex; flex-wrap: nowrap; flex-direction: column;"
                    >
                        Informations générales
                    </button>
                </h2>
                    <div
                        id="flush-collapseOne"
                        class="accordion-collapse collapse locked-mode p-4"
                        aria-labelledby="flush-headingOne"
                        data-bs-parent="#accordionFlushExample"
                    >
                    @if ($etablissement->infoGenerales)

                                <!-- ===================== ROW 1 ===================== -->
                        <div class="text-end">
                            <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                <i class="fs-5 ti ti-pencil"></i>
                            </button>
                        </div>
                                <form action="{{ route('infoGenerales.update', $etablissement->infoGenerales) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label>Raison sociale</label>
                                        <input type="text" class="form-control" name="raisonSocial" 
                                            value="{{ old('raisonSocial', $etablissement->infoGenerales->raisonSocial ?? '') }}">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label>Capital social</label>
                                        <input type="number" class="form-control" name="capitalSocialPrimaire" 
                                            value="{{ old('capitalSocialPrimaire', $etablissement->infoGenerales->capitalSocialPrimaire ?? '') }}">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Forme juridique</label>
                                        <select class="form-select" name="forme_juridique_id" >
                                            @foreach($formejuridiques as $forme)
                                                <option value="{{ $forme->id }}"
                                                    @selected(old('forme_juridique_id', $etablissement->infoGenerales->FormeJuridique ?? '') == $forme->id)>
                                                    {{ $forme->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Date d'immatriculation</label>
                                        <input type="date" class="form-control" name="dateImmatriculation" 
                                            value="{{ old('dateImmatriculation', $etablissement->infoGenerales->dateImmatriculation ?? '') }}">
                                    </div>
                                </div>

                                <!-- ===================== ROW 2 ===================== -->
                                <div class="row">
                                    <!-- ICE -->
                                    <div class="col-md-2 mb-3">
                                        <label>ICE</label>
                                        <input type="text" class="form-control" name="ice" 
                                            value="{{ old('ice', $etablissement->infoGenerales->ice ?? '') }}">
                                    </div>
                                    <div class="col-md-1 mb-3 d-flex align-items-end">
                                        <label class="btn btn-primary w-100 mb-0">
                                            <i class="ti ti-upload"></i> ICE
                                            <input type="file" name="ice_file" hidden accept=".pdf,.jpg,.png">
                                        </label>
                                        @if(!empty($etablissement->infoGenerales->ice_file))
                                            <a href="{{ asset('storage/'.$etablissement->infoGenerales->ice_file) }}" class="btn btn-link download-btn" download>
                                                <i class="ti ti-download"></i>
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Statut -->
                                    <div class="col-md-1 mb-3 d-flex align-items-end">
                                        <label class="btn btn-primary w-100 mb-0">
                                            <i class="ti ti-upload"></i> Statut
                                            <input type="file" name="status_file" hidden accept=".pdf,.jpg,.png">
                                        </label>
                                        @if(!empty($etablissement->infoGenerales->status_file))
                                            <a href="{{ asset('storage/'.$etablissement->infoGenerales->status_file) }}" class="btn btn-link download-btn" download>
                                                <i class="ti ti-download"></i>
                                            </a>
                                        @endif
                                    </div>

                                    <!-- RC -->
                                    <div class="col-md-3 mb-3">
                                        <label>RC</label>
                                        <input type="text" class="form-control" name="rc_input" 
                                            value="{{ old('rc', $etablissement->infoGenerales->rc ?? '') }}">
                                    </div>
                                    <div class="col-md-1 mb-3 d-flex align-items-end">
                                        <label class="btn btn-primary w-100 mb-0">
                                            <i class="ti ti-upload"></i> RC
                                            <input type="file" name="rc_file" hidden accept=".pdf,.jpg,.png">
                                        </label>
                                        @if(!empty($etablissement->infoGenerales->rc_file))
                                            <a href="{{ asset('storage/'.$etablissement->infoGenerales->rc_file) }}" class="btn btn-link download-btn" download>
                                                <i class="ti ti-download"></i>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>IF</label>
                                        <input type="text" class="form-control" name="ifiscal" 
                                            value="{{ old('ifiscal', $etablissement->infoGenerales->ifiscal ?? '') }}">
                                    </div>
                                </div>

                                <!-- ===================== ROW 3 ===================== -->
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label>Siège social</label>
                                        <input type="text" class="form-control" name="siegeSocial" 
                                            value="{{ old('siegeSocial', $etablissement->infoGenerales->siegeSocial ?? '') }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Lieu d'activité</label>
                                        <select class="form-select" name="paysActivite" >
                                            <option value="">-- Choisir --</option>
                                            @foreach($pays as $pay)
                                                <option value="{{ $pay->id }}"
                                                    @selected(old('paysActivite', $etablissement->infoGenerales->paysActivite ?? '') == $pay->id)>
                                                    {{ $pay->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Pays de résidence fiscale</label>
                                        <select class="form-select" name="paysResidence" >
                                            <option value="">-- Choisir --</option>
                                            @foreach($pays as $pay)
                                                <option value="{{ $pay->id }}"
                                                    @selected(old('paysResidence', $etablissement->infoGenerales->paysResidence ?? '') == $pay->id)>
                                                    {{ $pay->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- ===================== ROW 4 ===================== -->
                                <div class="row align-items-end">
                                    <div class="col-md-4 mb-3">
                                        <label>Autorité de régulation</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="autorite_regulation_check" name="regule" value="1"
                                                @checked(old('regule', $etablissement->infoGenerales->regule ?? false))>
                                            <label class="form-check-label"><span id="autoriteLabel">
                                                {{ old('regule', $etablissement->infoGenerales->regule ?? false) ? 'Oui' : 'Non' }}
                                            </span></label>
                                        </div>

                                        <!-- Champ contrôlé par la checkbox -->
                                        <div class="mt-2" id="autoriteInputWrapper"
                                            style="{{ old('regule', $etablissement->infoGenerales->regule ?? false) ? '' : 'display:none;' }}">
                                            <input type="text" class="form-control" name="nomRegulateur"
                                                value="{{ old('nomRegulateur', $etablissement->infoGenerales->nomRegulateur ?? '') }}"
                                                placeholder="Nom de l’autorité">
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>Téléphone</label>
                                        <input type="tel" class="form-control" name="telephone" 
                                            value="{{ old('telephone', $etablissement->infoGenerales->telephone ?? '') }}">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>E-mail</label>
                                        <input type="email" class="form-control" name="email" 
                                            value="{{ old('email', $etablissement->infoGenerales->email ?? '') }}">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label>Site web</label>
                                        <input type="text" class="form-control" name="siteweb"
                                            value="{{ old('siteweb', $etablissement->infoGenerales->siteweb ?? '') }}">
                                    </div>
                                </div>

                                <!-- ===================== ROW 5: Société de gestion ===================== -->
                                    <div class="row align-items-end">
                                        <div class="col-md-2 mb-3">
                                            <label>Société gestion</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="Societe_gestion_check" name="societe_gestion" value="1"
                                                    @checked(old('societe_gestion', $etablissement->infoGenerales->societe_gestion ?? false))>
                                                <label class="form-check-label">
                                                    <span id="Societe_gestionLabel">
                                                        {{ old('societe_gestion', $etablissement->infoGenerales->societe_gestion ?? false) ? 'Oui' : 'Non' }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                    <!-- Files controlled by checkbox -->
                                    <div id="societeGestionFiles" class="row mt-3 " 
                                        style="{{ old('societe_gestion', $etablissement->infoGenerales->societe_gestion ?? false) ? '' : 'display:none;' }}">
                                        @php
                                            $societeFiles = ['agrement_file','NI','FS','RG'];
                                        @endphp
                                            @foreach($societeFiles as $file)
                                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                                <label class="btn btn-primary w-100 mb-0">
                                                    <i class="ti ti-upload"></i> {{ strtoupper($file) }}
                                                    <input type="file" name="{{ $file }}" hidden accept=".pdf,.jpg,.png">
                                                </label>
                                                @if(!empty($etablissement->infoGenerales->$file))
                                                    <a href="{{ asset('storage/'.$etablissement->infoGenerales->$file) }}" class="btn btn-link download-btn" download>
                                                        <i class="ti ti-download"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                        
                                    </div>
                                </div>






                                <!-- ===================== CONTACTS ===================== -->
                                <div class="mt-4">
                                    <h5>Contacts</h5>
                                    <button type="button" class="btn btn-light-info btn-sm mb-2 add-row-btn" id="addContactRowBtn" onclick="ajoutercontact()">
                                        Ajouter un contact
                                    </button>
                                    <div class="contactsRows">

                                        
                                            @if ($etablissement->infoGenerales->contacts->count()>0)
                                                
                                            
                                            @foreach($etablissement->infoGenerales->contacts as $index => $contact)

                                                <div class="row align-items-end contactRow{{ $index }}">

                                                    <div class="col-md-2 mb-3">
                                                        <label>Nom</label>
                                                        <input type="text" class="form-control"
                                                            name="noms_contacts[]"
                                                            value="{{ old('noms_contacts.' . $index, $contact->nom ?? '') }}">
                                                    </div>

                                                    <div class="col-md-2 mb-3">
                                                        <label>Prénom</label>
                                                        <input type="text" class="form-control"
                                                            name="prenoms_contacts[]"
                                                            value="{{ old('prenoms_contacts.' . $index, $contact->prenom ?? '') }}">
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control"
                                                            name="emails_contacts[]"
                                                            value="{{ old('emails_contacts.' . $index, $contact->email ?? '') }}">
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label>fonction</label>
                                                        <input type="text" class="form-control"
                                                            name="fonctions_contacts[]"
                                                            value="{{ old('fonctions_contacts.' . $index, $contact->fonction ?? '') }}">
                                                    </div>

                                                    <div class="col-md-2 mb-3">
                                                        <label>Téléphone</label>
                                                        <input type="text" class="form-control"
                                                            name="telephones_contacts[]"
                                                            value="{{ old('telephones_contacts.' . $index, $contact->telephone ?? '') }}">
                                                    </div>

                                                    <div class="col-md-1 mb-3 d-flex align-items-end destroy">
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm remove-row-btn"
                                                                onclick="supprimercontact(this)">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>

                                                </div>

                                            @endforeach
@endif
                                        

                                    </div>

                                </div>

                                <button type="submit" class="update btn btn-success mt-3">Mettre à jour</button>
                            </form>
                        @else
                            <h5 class="text-center mt-4 ">Aucune information générale</h5>
                        @endif
                        
                    </div>
                    </div>

            
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingTwo">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                            aria-expanded="false" aria-controls="flush-collapseTwo"
                            style="display: flex; flex-wrap: nowrap; flex-direction: column;">
                            Coordonnées bancaires
                        </button>
                    </h2>
                    
                    <script>
                        const banques = @json($banques); // tableau d’objets {id, nom}
                        const villes  = @json($villes);  // tableau d’objets {id, libelle}
                    </script>

                    <div id="flush-collapseTwo" class="accordion-collapse collapse locked-mode p-4"
                        aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                        
                        <div class="accordion-body">
                            @if($etablissement->coordonneesBancaires->count() > 0)
                            <div class="text-end">
                                <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                    <i class="fs-5 ti ti-pencil"></i>
                                </button>
                            </div>
                            @endif
                            <form action="{{ route('coordonneesbancaires.update', $etablissement) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="CBancairesRows">
                                    @forelse($etablissement->coordonneesBancaires as $index => $cb)
                                        <div class="row CBancairesRowInfos CBancairesRow{{ $index }} mb-2 align-items-center">
                                            <div class="col-md-3 mb-3">
                                                <label>Banque</label>
                                                <select name="banque_id[]" class="form-select" >
                                                    <option value="">Sélectionnez une banque</option>
                                                    @foreach($banques as $banque)
                                                        <option value="{{ $banque->id }}" @selected(optional($cb->banque)->id == $banque->id)>{{$banque->nom}}  </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label>Agence</label>
                                                <input type="text" class="form-control" placeholder="Agence" name="agences_banque[]" value="{{ $cb->agences_banque }}">
                                            </div>

                                            <div class="col-md-2 mb-3">
                                                <label>Ville</label>
                                                <select name="ville_id[]" class="form-select" >
                                                    <option value="">Sélectionnez une ville</option>
                                                    @foreach($villes as $ville)
                                                        <option value="{{ $ville->id }}" @selected(optional($cb->ville)->id == $ville->id)>{{ $ville->libelle }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label>RIB</label>
                                                <input type="text" class="form-control" placeholder="RIB" name="rib_banque[]" value="{{ $cb->rib_banque }}">
                                            </div>
                                            

                                            <div class="col-md-1 text-center">
                                                <button type="button" class="btn btn-danger btn-sm destroy" onclick="removeCBancaire({{ $index }})" title="Supprimer cette coordonnée bancaire">
                                                    Supprimer
                                                </button>
                                            </div>
                                            
                                        </div>
                                    @empty
                                        <h5 class="text-center">Aucune coordonnées bancaires</h5>
                                    @endforelse
                                </div>

                                
                                <div class="text-start">
                                    <a href="{{ route('coordonneesbancaires.create', ['etablissement_id' => $etablissement->id, 'redirect_to' => 'dashboard']) }}" class="btn btn-light-info btn-sm mb-2">
                                        Ajouter un compte bancaire
                                    </a>
                                </div>

                                <div class="text-start">
                                    <button type="submit" class="update btn btn-success mt-3">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingThree">
                        <button
                            class="accordion-button collapsed text-center justify-content-center"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseThree"
                            style="display: flex; flex-wrap: nowrap; flex-direction: column;"

                        >
                            Typologie du client
                        </button>
                    </h2>

                    <div id="flush-collapseThree" class="accordion-collapse collapse locked-mode p-4">
                        <div class="accordion-body">

                            <!-- ✏️ Bouton édition -->
                            @if($etablissement->typologieClient)
                            <div class="text-end">
                                <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                    <i class="fs-5 ti ti-pencil"></i>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('typologie.update', $etablissement->id) }}">
                                @csrf
                            <div class="row">

                                <!-- Secteur -->
                                <div class="col-md-2 mb-3">
                                    <label>Secteur d'activité</label>
                                    <select name="secteurActivite"
                                        class="form-select editable"
                                        disabled>
                                        <option value="">—</option>
                                        @foreach($secteurs as $secteur)
                                            <option value="{{ $secteur->id }}"
                                                @selected(optional($etablissement->typologieClient)->secteurActivite == $secteur->id)>
                                                {{ $secteur->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Segment -->
                                <div class="col-md-2 mb-3">
                                    <label>Segment</label>
                                    <select name="segment"
                                        class="form-select editable"
                                        disabled>
                                        <option value="">—</option>
                                        @foreach($segments as $segment)
                                            <option value="{{ $segment->id }}"
                                                @selected(optional($etablissement->typologieClient)->segment == $segment->id)>
                                                {{ $segment->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Activité à l'étranger -->
                                <div class="col-md-2 mb-3">
                                    <label>Activité à l'étranger</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox"
                                            class="form-check-input editable"
                                            id="activite_etranger_check"
                                            name="activiteEtranger"
                                            value="1"
                                            disabled
                                            @checked(optional($etablissement->typologieClient)->activiteEtranger)>
                                        <label class="form-check-label">
                                            <span id="activite_etranger_label">
                                                {{ optional($etablissement->typologieClient)->activiteEtranger ? 'Oui' : 'Non' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Pays étranger -->
                                <div class="col-md-2 mb-3"
                                    id="pays_etranger_wrapper"
                                    style="display: {{ optional($etablissement->typologieClient)->activiteEtranger ? 'block' : 'none' }}">
                                    <label>Pays</label>
                                    <select name="paysEtranger"
                                        class="form-select editable"
                                        disabled>
                                        <option value="">—</option>
                                        @foreach($pays as $pay)
                                            <option value="{{ $pay->id }}"
                                                @selected(optional($etablissement->typologieClient)->paysEtranger == $pay->id)>
                                                {{ $pay->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Marché financier -->
                                <div class="col-md-2 mb-3">
                                    <label>Marché financier</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox"
                                            class="form-check-input editable"
                                            id="marche_check"
                                            name="publicEpargne"
                                            value="1"
                                            disabled
                                            @checked(optional($etablissement->typologieClient)->publicEpargne)>
                                        <label class="form-check-label">
                                            <span id="marche_label">
                                                {{ optional($etablissement->typologieClient)->publicEpargne ? 'Oui' : 'Non' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Précisez -->
                                <div class="col-md-2 mb-3"
                                    id="marche_wrapper"
                                    style="display: {{ optional($etablissement->typologieClient)->publicEpargne ? 'block' : 'none' }}">
                                    <label>Précisez</label>
                                    <input type="text"
                                        name="publicEpargne_label"
                                        class="form-control editable"
                                        disabled
                                        value="{{ optional($etablissement->typologieClient)->publicEpargne_label }}">
                                
                            </div>
                            <div class="text-start">
                                    <button type="submit" class="update btn btn-success mt-3">Mettre à jour</button>
                                </div>
                        </form>
                            @else
                                <h5 class="text-center mt-3">Aucun statut Typologie du client disponible pour cet établissement.</h5>
                            @endif
                        </div>
                    </div>
                </div>
                    </div>


                     <!-- Accordion: Statut FATCA -->
                <div class="accordion-item text-center">
                    <h2 class="accordion-header" id="flush-headingFATCA">
                        <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseFATCA"
                            aria-expanded="false"
                            aria-controls="flush-collapseFATCA"
                            style="display: flex; flex-direction: column;"
                        >
                            Statut FATCA
                        </button>
                    </h2>

                    <div
                        id="flush-collapseFATCA"
                        class="accordion-collapse collapse locked-mode p-4"
                        aria-labelledby="flush-headingFATCA"
                        data-bs-parent="#accordionFlushExample"
                    >
                        <div class="accordion-body">
                            
                           @if($etablissement->statutFatca)
                           <div class="text-end">
                                <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                    <i class="fs-5 ti ti-pencil"></i>
                                </button>
                            </div>

                        <form
                            action="{{ route('statutfatca.update', $etablissement->statutFatca->id) }}"
                            method="POST"
                            enctype="multipart/form-data"
                        >
                            @csrf                            
                            <div class="row mt-4 justify-content-center">

                                <!-- ================= US ENTITY ================= -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="mb-3 text-center">
                                        Votre établissement est-il considéré comme <b>"US Entity"</b> ?
                                    </h5>

                                    <div class="d-flex align-items-center gap-3 justify-content-center">

                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="us_entity_id"
                                                name="usEntity"
                                                value="1"
                                                @checked($etablissement->statutFatca->usEntity)
                                            >
                                            <label class="form-check-label">
                                                <span id="usEntityLabel">
                                                    {{ $etablissement->statutFatca->usEntity ? 'Oui' : 'Non' }}
                                                </span>
                                            </label>
                                        </div>

                                        <div class="flex-grow-1">
                                            <input type="file" name="fichier_usEntity" id="fatca_hidden_input" class="d-none" />

                                            <div id="dropzone-fichierFATCA" class="dropzone mt-2"
                                                style="{{ $etablissement->statutFatca->usEntity ? 'block' : 'none' }};
                                                    min-height: 80px; border: 2px dashed #ccc; border-radius: 8px;
                                                    padding: 10px; cursor:pointer;">
                                                <p class="text-muted text-center my-2">
                                                    Glissez-déposez votre fichier FATCA ici ou cliquez pour sélectionner
                                                </p>
                                            </div>

                                            @if($etablissement->statutFatca->fichier_usEntity)
                                                <a href="{{ asset('storage/'.$etablissement->statutFatca->fichier_usEntity) }}"
                                                class="btn btn-link mt-2" download>
                                                    <i class="ti ti-download"></i> Télécharger le fichier existant
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- ================= GIIN ================= -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="mb-3 text-center">
                                        Votre établissement est une <b>"Participating Financial Institution"</b> ?
                                    </h5>

                                    <div class="d-flex align-items-center gap-3 justify-content-center">

                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="giin_id"
                                                name="giin"
                                                value="1"
                                                @checked($etablissement->statutFatca->giin)
                                            >
                                            <label class="form-check-label">
                                                <span id="giinLabel">
                                                    {{ $etablissement->statutFatca->giin ? 'Oui' : 'Non' }}
                                                </span>
                                            </label>
                                        </div>

                                        <div class="flex-grow-1"
                                            id="giin_data_id"
                                            style="display: {{ $etablissement->statutFatca->giin ? 'block' : 'none' }}">
                                            <label>GIIN :</label>
                                            <input type="text" class="form-control" name="giin_label"
                                                value="{{ $etablissement->statutFatca->giin_label }}">
                                        </div>

                                        <div class="flex-grow-1">
                                            <label>Autres :</label>
                                            <input type="text" class="form-control" name="giin_label_Autres"
                                                value="{{ $etablissement->statutFatca->giin_label_Autres }}">
                                        </div>

                                    </div>
                                </div>

                                <!-- ================= SAVE ================= -->
                                <div class=" text-start">
                                    <button type="submit" class="update btn btn-success mt-3 ">Mettre à jour</button>
                                </div>

                            </div>
                        </form>


                        @else
                            <h5 class="text-center mt-3">
                                Aucun statut FATCA disponible pour cet établissement.
                            </h5>
                        @endif


                                                </div>
                                            </div>
                                        </div>

                <!-- Accordion: Situation financière et patrimoniale -->
                <div class="accordion-item text-center justify-content-center">
                    <h2 class="accordion-header" id="flush-headingThree">
                        <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseFive"
                            aria-expanded="false"
                            aria-controls="flush-collapseFive"
                            style="display: flex; flex-direction: column;"
                        >
                            Situation financière et patrimoniale
                        </button>
                    </h2>

                    <div
                        id="flush-collapseFive"
                        class="accordion-collapse collapse locked-mode p-4"
                        aria-labelledby="flush-headingThree"
                        data-bs-parent="#accordionFlushExample"
                    >
                        <div class="accordion-body">

                            @if($etablissement->SituationFinanciere)
                            <div class="text-end">
                                <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                    <i class="fs-5 ti ti-pencil"></i>
                                </button>
                            </div>
                            <form action="{{ route('situationfinanciere.update', $etablissement) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                            <div class="mt-4">

                                <!-- Capital social, origine des fonds, pays -->
                                <div class="row mb-4 justify-content-center">
                                    <div class="col-md-4 mb-3">
                                        <label>Capital social</label>
                                        <input type="text" class="form-control number-format" name="capitalSocial"
                                            value="{{ old('capitalSocial', $etablissement->SituationFinanciere->capitalSocial ?? 0) }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Origine des fonds</label>
                                        <input type="text" class="form-control" name="origineFonds"
                                            value="{{ old('origineFonds', $etablissement->SituationFinanciere->origineFonds ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Pays de résidence fiscale</label>
                                        <select name="paysOrigineFonds" class="form-select" >
                                            
                                            @foreach($pays as $pay)
                                                <option value="{{ $pay->id }}" 
                                                    @selected(optional($etablissement->SituationFinanciere->paysOr)->id == $pay->id)>
                                                    {{ $pay->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Chiffre d'affaires, Résultat net, Groupe holding -->
                                <div class="row mb-4 justify-content-center">
                                    <div class="col-md-4 mb-3">
                                        <label>Chiffre d'affaires (exercice écoulé)</label><br>
                                        @php $ca = old('chiffreAffaires', $etablissement->SituationFinanciere->chiffreAffaires ?? null); @endphp
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="chiffreAffaires" value="<5M" @checked($ca === '<5M')>
                                            <label class="form-check-label">< 5 M.DH</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="chiffreAffaires" value="5-10M" @checked($ca === '5-10M')>
                                            <label class="form-check-label">5 M.DH < CA < 10 M.DH</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="chiffreAffaires" value=">10M" @checked($ca === '>10M')>
                                            <label class="form-check-label">> 10 M.DH</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Résultat net (exercice écoulé)</label>
                                        <input type="text" class="form-control number-format" name="resultatsNET"
                                            value="{{ old('resultatsNET', $etablissement->SituationFinanciere->resultatsNET ?? 0) }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Appartient à un groupe <b>"holding"</b></label><br>
                                        @php $holding = old('holding', $etablissement->SituationFinanciere->holding ?? 0); @endphp
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="holding" value="1" @checked($holding == 1)>
                                            <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="holding" value="0" @checked($holding == 0)>
                                            <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload états de synthèse -->
                                <div class="row mb-4 justify-content-center">
                                    <div class="col-md-4 mb-3">
                                        <label for="etat_synthese" class="btn btn-primary w-100" >
                                            <i class="ti ti-upload"></i> Etats de synthèse
                                        </label>
                                        <input type="file" name="etat_synthese" id="etat_synthese" class="d-none">
                                        @if(!empty($etablissement->SituationFinanciere->etat_synthese))
                                            <a href="{{ asset('storage/' . $etablissement->SituationFinanciere->etat_synthese) }}" class="btn btn-link mt-2" download>
                                                <i class="ti ti-download"></i> Télécharger le fichier existant
                                            </a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <!-- ================= SAVE ================= -->
                                <div class=" text-start">
                                    <button type="submit" class="update btn btn-success mt-3 ">Mettre à jour</button>
                                </div>
                            </form>
                            @else
                                <h5 class="text-center mt-3">Aucune situation financière disponible pour cet établissement.</h5>
                            @endif
                        </div>
                    </div>
                </div>

                      <!-- Accordion: Actionnariat -->
                <div class="accordion-item text-center justify-content-center">
                    <h2 class="accordion-header" id="flush-headingActionnariat">
                        <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseActionnariat"
                            aria-expanded="false"
                            aria-controls="flush-collapseActionnariat"
                            style="display: flex; flex-direction: column; flex-wrap: nowrap;"
                        >
                            Actionnariat
                        </button>
                    </h2>

                    <div
                        id="flush-collapseActionnariat"
                        class="accordion-collapse collapse locked-mode p-4"
                        aria-labelledby="flush-headingActionnariat"
                        data-bs-parent="#accordionFlushExample"
                    >
                        <div class="accordion-body">
                            <form action="{{ route('actionnariat.update', $etablissement) }}" method="POST">
                                @csrf
                                @if($etablissement->Actionnaire && $etablissement->Actionnaire->count())
                                <div class="text-end">
                                    <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                        <i class="fs-5 ti ti-pencil"></i>
                                    </button>
                                </div>
                                @endif
                                @if($etablissement->Actionnaire && $etablissement->Actionnaire->count())
                                <div class="mt-4 actionnairesRows">

                                    @foreach($etablissement->Actionnaire as $index => $actionnaire)
                                        <div class="row border rounded p-3 mb-4  actionnaireRowInfos actionnaireRow{{ $index }} mb-3 justify-content-center align-items-center">
                                            <div class="col-md-2 mb-2">
                                                <label>Nom / Raison sociale</label>
                                                <input type="text" class="form-control" name="noms_rs_actionnaires[]" placeholder="Nom / Raison sociale" value="{{ $actionnaire->nom_rs }}">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label>Prénom</label>
                                                <input type="text" class="form-control" name="prenoms_actionnaires[]" placeholder="Prénom (p.physique)" value="{{ $actionnaire->prenom }}">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label>N° d'identité / RC</label>
                                                <input type="text" class="form-control" name="identite_actionnaires[]" placeholder="N° d'identité / RC" value="{{ $actionnaire->identite }}">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label>Nombre de titres</label>
                                                <input type="number" class="form-control" name="nombre_titres_actionnaires[]" placeholder="Nombre de titres" value="{{ $actionnaire->nombre_titres }}">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label>Pourcentage capital</label>
                                                <input type="number" step="0.01" class="form-control" name="pourcentage_capital_actionnaires[]" placeholder="% Capital" value="{{ $actionnaire->pourcentage_capital }}">
                                            </div>
                                            <div class="col-md-1 ">
                                                <button type="button" class="btn btn-danger btn-sm destroy" onclick="removeActionnaire({{ $index }})" title="Supprimer cette actionnaire">
                                                    Supprimer
                                                </button>
                                            </div>  

                                        </div>
                                    @endforeach

                                </div>
                                @else
                                    <h5 class="text-center">Aucun actionnaire enregistré</h5>
                                    <div class="mt-4 actionnairesRows"></div>
                                @endif

                                <div class="text-start">
                                    <a href="{{ route('actionnariat.create', ['etablissement_id' => $etablissement->id, 'redirect_to' => 'dashboard']) }}" class="btn btn-light-info btn-sm mb-2">
                                        Ajouter un actionnaire
                                    </a>
                                </div>

                                <div class="text-start">
                                    <button type="submit" class="update btn btn-success mt-3">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                    <!-- Accordion: Bénéficiaire effectif -->
                <div class="accordion-item text-center">
                    <h2 class="accordion-header" id="flush-headingEight">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseEight"
                                aria-expanded="false"
                                aria-controls="flush-collapseEight"
                                style="display:flex;flex-direction:column;">
                            Bénéficiaire effectif
                        </button>
                    </h2>

                    <div id="flush-collapseEight"
                        class="accordion-collapse collapse locked-mode p-4"
                        aria-labelledby="flush-headingEight"
                        data-bs-parent="#accordionFlushExample">

                        <div class="accordion-body">
                            <form action="{{ route('benificiaireeffectif.update', $etablissement) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if($etablissement->BeneficiaireEffectif && $etablissement->BeneficiaireEffectif->count())
                                <div class="text-end">
                                    <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                        <i class="fs-5 ti ti-pencil"></i>
                                    </button>
                                </div>
                                @endif
                                <div class="beneficiairesRows">

                                    @if($etablissement->BeneficiaireEffectif && $etablissement->BeneficiaireEffectif->count())
                                        @foreach($etablissement->BeneficiaireEffectif as $index => $be)
                                            <div class="border rounded p-3 mb-4 benificiaireRow{{ $index }}">
                                                
                                                {{-- LIGNE 1 --}}
                                                <div class="row mb-2">
                                                    <div class="col-md-3">
                                                        <label>Nom / Raison sociale</label>
                                                        <input type="text" class="form-control"
                                                            name="noms_rs_benificiaires[]"
                                                            value="{{ $be->nom_rs }}">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Prénom</label>
                                                        <input type="text" class="form-control"
                                                            name="prenoms_benificiaires[]"
                                                            value="{{ $be->prenom }}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Date de naissance</label>
                                                        <input type="date" class="form-control"
                                                            name="dates_naissance_benificiaires[]"
                                                            value="{{ $be->date_naissance }}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>CIN / Passeport</label>
                                                        <input type="text" class="form-control"
                                                            name="identite_benificiaires[]"
                                                            value="{{ $be->identite }}">
                                                    </div>
                                                    <div class="col-md-1 mt-4">
                                                        <button type="button" class="btn btn-danger btn-sm destroy" onclick="benificiaireeffectifDestroy({{ $index }})" title="Supprimer cette actionnaire">
                                                            Supprimer
                                                        </button>
                                                    </div>  
                                                </div>

                                                {{-- LIGNE 2 --}}
                                                <div class="row mb-2">
                                                    <div class="col-md-3">
                                                        <label>Pays de naissance</label>
                                                        <select class="form-select" name="pays_naissance_benificiaires[]">
                                                            <option value="">---</option>
                                                            @foreach($pays as $pay)
                                                                <option value="{{ $pay->id }}"
                                                                    @selected($be->pays_naissance_id == $pay->id)>
                                                                    {{ $pay->libelle }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Nationalité</label>
                                                        <select class="form-select" name="nationalites_benificiaires[]">
                                                            <option value="">---</option>
                                                            @foreach($pays as $pay)
                                                                <option value="{{ $pay->id }}"
                                                                    @selected($be->nationalite_id == $pay->id)>
                                                                    {{ $pay->libelle }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>% Capital</label>
                                                        <input type="number" step="0.01"
                                                            class="form-control"
                                                            name="benificiaires_pourcentage_capital[]"
                                                            value="{{ $be->pourcentage_capital }}">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="cin_file_{{ $index }}">CIN (fichier)</label>
                                                        <input type="file"
                                                            id="cin_file_{{ $index }}"
                                                            class="d-none"
                                                            name="cin_Beneficiaires_Effectif[]"
                                                            accept=".pdf,.jpg,.png">
                                                        <input type="hidden" name="existing_cin_beneficiaire[]" value="{{ $be->cin_file }}">
                                                        <label for="cin_file_{{ $index }}" class="btn btn-primary w-100">
                                                            <i class="ti ti-upload"></i> CIN
                                                        </label>

                                                        @if(!empty($be->cin_file))
                                                            <a href="{{ asset('storage/' . $be->cin_file) }}" 
                                                                target="_blank" 
                                                                class="text-primary ms-2">
                                                                <i class="ti ti-download"></i> 
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- LIGNE PPE --}}
                                                <div class="row mb-2 align-items-center">
                                                    <div class="col-md-1">
                                                        <label>PPE</label>
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="ppe2_benificiaires[]" value="{{ $be->ppe_id ? 1 : 0 }}" id="hidden_ppe_{{ $index }}">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                id="ppe_{{ $index }}"
                                                                onchange="togglePPE({{ $index }})"
                                                                @checked($be->ppe_id)>
                                                        </div>
                                                        <label><span id="label_ppe_{{ $index }}">{{ $be->ppe_id ? 'Oui' : 'Non' }}</span></label>
                                                    </div>

                                                    <div class="col-md-3"
                                                        id="ppe_block_{{ $index }}"
                                                        style="{{ $be->ppe_id ? '' : 'display:none' }}">
                                                        <label>Libellé PPE</label>
                                                        <select class="form-select"
                                                                name="benificiaires_ppe_input[]">
                                                            <option value="">---</option>
                                                            @foreach($ppes as $ppe)
                                                                <option value="{{ $ppe->id }}"
                                                                    @selected($be->ppe_id == $ppe->id)>
                                                                    {{ $ppe->libelle }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <label>Lien PPE</label>
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="lien2_benificiaires[]" value="{{ $be->ppe_lien_id ? 1 : 0 }}" id="hidden_lien_{{ $index }}">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                id="lien_ppe_{{ $index }}"
                                                                onchange="toggleLienPPE({{ $index }})"
                                                                @checked($be->ppe_lien_id)>
                                                        </div>
                                                        <label><span id="label_lien_{{ $index }}">{{ $be->ppe_lien_id ? 'Oui' : 'Non' }}</span></label>
                                                    </div>

                                                    <div class="col-md-3"
                                                        id="ppe_lien_block_{{ $index }}"
                                                        style="{{ $be->ppe_lien_id ? '' : 'display:none' }}">
                                                        <label>Type de lien PPE</label>
                                                        <select class="form-select"
                                                                name="benificiaires_ppe_lien_input[]">
                                                            <option value="">---</option>
                                                            @foreach($ppes as $ppe)
                                                                <option value="{{ $ppe->id }}"
                                                                    @selected($be->ppe_lien_id == $ppe->id)>
                                                                    {{ $ppe->libelle }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                    @else
                                        <h5 class="text-center">Aucun bénéficiaire effectif enregistré</h5>
                                    @endif

                                </div>

                                <div class="text-start">
                                    <a href="{{ route('benificiaireeffectif.create', ['etablissement_id' => $etablissement->id, 'redirect_to' => 'dashboard']) }}" class="btn btn-light-info btn-sm mb-2">
                                        Ajouter un bénéficiaire
                                    </a>
                                </div>

                                <div class="text-start">
                                    <button type="submit" class="update btn btn-success mt-3">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                <!-- Accordion: Administrateurs -->
                <div class="accordion-item text-center">
                    <h2 class="accordion-header" id="flush-headingAdmin">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseAdmin"
                                aria-expanded="false"
                                aria-controls="flush-collapseAdmin"
                                style="display: flex; flex-wrap: nowrap; flex-direction: column;">
                            Administrateurs
                        </button>
                    </h2>

                    <div id="flush-collapseAdmin"
                        class="accordion-collapse collapse locked-mode p-4"
                        aria-labelledby="flush-headingAdmin"
                        data-bs-parent="#accordionFlushExample">

                        <div class="accordion-body">
                            <form action="{{ route('administrateurs.update', $etablissement) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if($etablissement->Administrateur && $etablissement->Administrateur->count())
                                <div class="text-end">
                                    <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                        <i class="fs-5 ti ti-pencil"></i>
                                    </button>
                                </div>
                                @endif
                                <div class="administrateursRows">
                                    @if(optional($etablissement->Administrateur)->count())
                                        @foreach($etablissement->Administrateur as $index => $adm)
                                            <div class="border rounded p-3 mb-4 administrateurRowInfos administrateurRow{{ $index }}">
                                                <div class="row align-items-center">

                                                    <!-- Nom / Prénom -->
                                                    <div class="col-md-2">
                                                        <label>Nom</label>
                                                        <input type="text" class="form-control mb-2" name="noms_administrateurs[]" value="{{ $adm->nom }}" placeholder="Nom">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Prénom</label>
                                                        <input type="text" class="form-control mb-2" name="prenoms_administrateurs[]" value="{{ $adm->prenom }}" placeholder="Prénom">
                                                    </div>

                                                    <!-- Pays / Date de naissance / CIN -->
                                                    <div class="col-md-2">
                                                        <label>Pays</label>
                                                        <select class="form-select" name="pays_administrateurs[]">
                                                            <option value="">---</option>
                                                            @foreach($pays as $pay)
                                                                <option value="{{ $pay->id }}" @selected($adm->pays_id == $pay->id)>{{ $pay->libelle }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Date de naissance</label>
                                                        <input type="date" class="form-control" name="dates_naissance_administrateurs[]" value="{{ $adm->date_naissance }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>CIN / Passeport</label>
                                                        <input type="text" class="form-control" name="cins_administrateurs[]" value="{{ $adm->identite }}" placeholder="CIN / Passeport">
                                                    </div>

                                                    <!-- Nationalité -->
                                                    <div class="col-md-2">
                                                        <label>Nationalité{{ $adm->nationalite_id }}</label> 
                                                        <select class="form-select" name="nationalites_administrateurs[]">
                                                            <option value="">---</option>
                                                            @foreach($pays as $pay)
                                                                <option value="{{ $pay->id }}" @selected($adm->nationalite_id == $pay->id)>{{ $pay->libelle }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <label>PPE</label>

                                                        <div class="form-check form-switch">

                                                            <input type="hidden"
                                                                name="ppe2_administrateurs[{{ $index }}]"
                                                                id="hidden_admin_ppe_{{ $index }}"
                                                                value="{{ $adm->ppe_id ? 1 : 0 }}">

                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                id="administrateur_ppe_id_{{ $index }}"
                                                                onchange="toggleAdminPPE({{ $index }})"
                                                                @checked($adm->ppe_id)>
                                                        </div>

                                                        <label>
                                                            <span id="label_admin_ppe_{{ $index }}">
                                                                {{ $adm->ppe_id ? 'Oui' : 'Non' }}
                                                            </span>
                                                        </label>
                                                    </div>

                                                    <div class="col-md-3"
                                                        id="administrateur_ppe_data_id_{{ $index }}"
                                                        style="{{ $adm->ppe_id ? '' : 'display:none' }}">

                                                        <label>Libellé PPE</label>

                                                        <select class="form-select"
                                                                name="administrateur_ppe_input[{{ $index }}]">

                                                            <option value="">---</option>

                                                            @foreach($ppes as $ppe)
                                                                <option value="{{ $ppe->id }}"
                                                                    @selected($adm->ppe_id == $ppe->id)>
                                                                    {{ $ppe->libelle }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>Lien PPE</label>

                                                        <div class="form-check form-switch">

                                                            <input type="hidden"
                                                                name="lien2_administrateurs[{{ $index }}]"
                                                                id="hidden_admin_lien_{{ $index }}"
                                                                value="{{ $adm->lien_ppe_id ? 1 : 0 }}">

                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                id="administrateur_ppe_lien_id_{{ $index }}"
                                                                onchange="toggleAdminLienPPE({{ $index }})"
                                                                @checked($adm->lien_ppe_id)>
                                                        </div>

                                                        <label>
                                                            <span id="label_admin_lien_{{ $index }}">
                                                                {{ $adm->lien_ppe_id ? 'Oui' : 'Non' }}
                                                            </span>
                                                        </label>
                                                    </div>

                                                    <div class="col-md-3"
                                                        id="administrateur_ppe_lien_data_id_{{ $index }}"
                                                        style="{{ $adm->lien_ppe_id ? '' : 'display:none' }}">

                                                        <label>Type de lien PPE</label>

                                                        <select class="form-select"
                                                                name="administrateur_ppe_lien_input[{{ $index }}]">

                                                            <option value="">---</option>

                                                            @foreach($ppes as $ppe)
                                                                <option value="{{ $ppe->id }}"
                                                                    @selected($adm->lien_ppe_id == $ppe->id)>
                                                                    {{ $ppe->libelle }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                

                                                

                                                    <!-- Fonction -->
                                                    <div class="col-md-1">
                                                        <label>Fonction</label>
                                                        <input type="text" class="form-control" name="fonctions_administrateurs[]" value="{{ $adm->fonction }}" placeholder="Fonction">
                                                    </div>

                                                    <!-- Fichiers CIN / PVN -->
                                                    <div class="col-md-1">
                                                        <label>CIN</label>
                                                        <label for="cin_file_adm_{{ $index }}" class="btn btn-primary w-100">
                                                            <i class="ti ti-upload"></i> CIN
                                                        </label>
                                                        <input type="file" id="cin_file_adm_{{ $index }}" name="cin_administrateurs[]" class="d-none" accept=".pdf,.jpg,.png">
                                                        @if($adm->cin_file)
                                                            <a href="{{ asset('storage/'.$adm->cin_file) }}" target="_blank" class="text-primary ms-2">
                                                                <i class="ti ti-download"></i>
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-1">
                                                        <label>PVN</label>
                                                        <label for="pvn_file_adm_{{ $index }}" class="btn btn-primary w-100">
                                                            <i class="ti ti-upload"></i> PVN
                                                        </label>
                                                        <input type="file" id="pvn_file_adm_{{ $index }}" name="pvn_administrateurs[]" class="d-none" accept=".pdf,.jpg,.png">
                                                        <input type="hidden" name="existing_cin_administrateurs[]" value="{{ $adm->cin_file }}">
                                                        <input type="hidden" name="existing_pvn_administrateurs[]" value="{{ $adm->pvn_file }}">
                                                        @if($adm->pvn_file)
                                                            <a href="{{ asset('storage/'.$adm->pvn_file) }}" target="_blank" class="text-primary ms-2">
                                                                <i class="ti ti-download"></i>
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-1 text-center">
                                                        <button type="button" class="btn btn-danger btn-sm destroy" onclick="removeAdministrateur({{ $index }})" title="Supprimer cet administrateur">
                                                                 Supprimer
                                                        </button>
                                                    </div>

                                                    
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <h5 class="text-center">Aucun administrateur enregistré</h5>
                                    @endif
                                </div>

                                <div class="text-start">
                                    <a href="{{ route('administrateurs.create', ['etablissement_id' => $etablissement->id, 'redirect_to' => 'dashboard']) }}" class="btn btn-light-info btn-sm mb-2">
                                        Ajouter un administrateur
                                    </a>
                                </div>

                                <div class="text-start">
                                    <button type="submit" class="update btn btn-success mt-3">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                    <!-- Accordion: Personnes habilitées à faire fonctionner le compte -->
                <div class="accordion-item justify-content-center text-center">
                    <h2 class="accordion-header" id="flush-headingPersonnes">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#flush-Personnes"
                                aria-expanded="false"
                                aria-controls="flush-Personnes"
                                style="display:flex;flex-direction:column;">
                            Personnes habilitées à faire fonctionner le compte
                        </button>
                    </h2>

                    <div id="flush-Personnes"
                        class="accordion-collapse collapse locked-mode p-4"
                        data-bs-parent="#accordionFlushExample">

                        <div class="accordion-body">
                            <form action="{{ route('personneshabilites.update', $etablissement->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if($etablissement->PersonnesHabilites && $etablissement->PersonnesHabilites->count())
                                <div class="text-end">
                                    <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                        <i class="fs-5 ti ti-pencil"></i>
                                    </button>
                                </div>
                                @endif

                                <!-- Conteneur pour lignes dynamiques -->
                                <div class="personneHabiliteRows"></div>

                                @if(optional($etablissement->PersonnesHabilites)->count())
                                    @foreach($etablissement->PersonnesHabilites as $index => $hab)
                                        <div class="border rounded p-3 mb-4 phabiliteRow{{ $index }}">
                                            <div class="row mt-3 phabiliteRowInfos">

                                                <!-- Nom -->
                                                <div class="col-md-2">
                                                    <label>Nom</label>
                                                    <input type="text" class="form-control" name="noms_habilites[]" value="{{ $hab->nom_rs }}" placeholder="Nom">
                                                </div>

                                                <!-- Prénom -->
                                                <div class="col-md-2">
                                                    <label>Prénom</label>
                                                    <input type="text" class="form-control" name="prenoms_habilites[]" value="{{ $hab->prenom }}" placeholder="Prénom">
                                                </div>

                                                <!-- CIN -->
                                                <div class="col-md-2">
                                                    <label>CIN / Passeport</label>
                                                    <input type="text" class="form-control" name="cin_habilites[]" value="{{ $hab->identite }}" placeholder="N° CIN / Passeport">
                                                </div>

                                                <!-- CIN file -->
                                                <div class="col-md-1">
                                                    <label for="">CIN</label>
                                                    <input type="file" id="cin_file_hab_{{ $index }}" name="cin_habilites_file[]" class="d-none" accept=".pdf,.jpg,.png">
                                                    <label for="cin_file_hab_{{ $index }}" class="btn btn-primary w-100">
                                                        <i class="ti ti-upload"></i> CIN
                                                    </label>
                                                    @if($hab->cin_file)
                                                        <a href="{{ asset('storage/'.$hab->cin_file) }}" target="_blank" class="text-primary d-block text-center mt-1">
                                                            <i class="ti ti-download"></i>
                                                        </a>
                                                    @endif
                                                </div>

                                                <!-- Fonction -->
                                                <div class="col-md-2">
                                                    <label>Fonction</label>
                                                    <input type="text" class="form-control" name="fonctions_habilites[]" value="{{ $hab->fonction }}" placeholder="Fonction">
                                                </div>
                                                

                                                <!-- Habilitation file -->
                                                <div class="col-md-2">
                                                    <Label>Habilitation</Label>
                                                    <input type="file" id="hab_file_hab_{{ $index }}" name="hab_habilites[]" class="d-none" accept=".pdf,.jpg,.png">
                                                    <input type="hidden" name="existing_cin_habilites_file[]" value="{{ $hab->cin_file }}">
                                                    <input type="hidden" name="existing_hab_habilites[]" value="{{ $hab->fichier_habilitation_file }}">
                                                    <label for="hab_file_hab_{{ $index }}" class="btn btn-primary w-100">
                                                        <i class="ti ti-upload"></i> Habilitation
                                                    </label>
                                                    @if($hab->fichier_habilitation_file)
                                                        <a href="{{ asset('storage/'.$hab->fichier_habilitation_file) }}" target="_blank" class="text-primary d-block text-center mt-1">
                                                            <i class="ti ti-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="col-md-1 text-end mt-4">
                                                    <button type="button" class="btn btn-danger btn-sm destroy" onclick="removeHabilite({{ $index }})" title="Supprimer cet administrateur">
                                                        Supprimer
                                                    </button>
                                                </div>
                                                <!-- PPE -->
                                                <div class="col-md-1 d-flex flex-column align-items-start">
                                                    <label>PPE</label>
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="ppes_habilites_check[]" value="{{ $hab->ppe ? 1 : 0 }}" id="hidden_habilite_ppe_{{ $index }}">
                                                        <input type="checkbox" class="form-check-input" id="habilite_ppe_id_{{ $index }}" onchange="toggleHabilitePPE({{ $index }})" @checked($hab->ppe)>
                                                    </div>
                                                    <label><span id="label_habilite_ppe_{{ $index }}">{{ $hab->ppe ? 'Oui' : 'Non' }}</span></label>
                                                </div>
                                                <div class="col-md-2">
                                                    <div id="habilite_ppe_data_id_{{ $index }}" style="{{ $hab->ppe ? '' : 'display:none' }}">
                                                        <label>Libellé PPE</label>
                                                        <select class="form-select" name="ppes_habilites_input[]">
                                                            <option value="">---</option>
                                                            @foreach($ppes as $ppe)
                                                                <option value="{{ $ppe->id }}" @selected($hab->libelle_ppe == $ppe->id)>{{ $ppe->libelle }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Lien PPE -->
                                                <div class="col-md-1 d-flex flex-column align-items-start">
                                                    <label>Lien PPE</label>
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="ppes_lien_habilites_check[]" value="{{ $hab->lien_ppe ? 1 : 0 }}" id="hidden_habilite_lien_{{ $index }}">
                                                        <input type="checkbox" class="form-check-input" id="habilite_ppe_lien_id_{{ $index }}" onchange="toggleHabiliteLienPPE({{ $index }})" @checked($hab->lien_ppe)>
                                                    </div>
                                                    <label><span id="label_habilite_lien_{{ $index }}">{{ $hab->lien_ppe ? 'Oui' : 'Non' }}</span></label>
                                                </div>
                                                <div class="col-md-2">
                                                    <div id="habilite_ppe_lien_data_id_{{ $index }}" style="{{ $hab->lien_ppe ? '' : 'display:none' }}">
                                                        <label>Libellé lien PPE</label>
                                                        <select class="form-select" name="ppes_lien_habilites_input[]">
                                                            <option value="">---</option>
                                                            @foreach($ppes as $ppe)
                                                                <option value="{{ $ppe->id }}" @selected($hab->libelle_ppe_lien == $ppe->id)>{{ $ppe->libelle }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="text-start">
                                    <button type="submit" class="update btn btn-success mt-3">Mettre à jour</button>
                                </div>
                            </form>

                            <div class="text-start mt-3">
                                <a href="{{ route('personneshabilites.create', ['etablissement_id' => $etablissement->id, 'redirect_to' => 'dashboard']) }}" class="btn btn-light-info btn-sm mb-2">
                                    Ajouter une personne habilitée
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="accordion-item text-center">
                    <h2 class="accordion-header" id="flush-headingObjet">
                        <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#flush-Objet"
                            aria-expanded="false"
                            aria-controls="flush-Objet"
                            style="display: flex; flex-direction: column;"
                        >
                            Objet et nature de la relation d'affaire
                        </button>
                    </h2>

                    <div
                        id="flush-Objet"
                        class="accordion-collapse collapse locked-mode p-4"
                        aria-labelledby="flush-Objet"
                        data-bs-parent="#accordionFlushExample"
                    >
                        <div class="accordion-body">

                            @php
                                $objetRelation = $etablissement->ObjetRelation ?? null;
                                $objets = [];

                                if ($objetRelation) {
                                    if (is_array($objetRelation->objet_relation)) {
                                        $objets = $objetRelation->objet_relation;
                                    } elseif (is_string($objetRelation->objet_relation)) {
                                        $objets = explode(',', $objetRelation->objet_relation);
                                    }
                                }
                            @endphp

                            @if($objetRelation)
                            <form
                                action="{{ route('objetrelation.update', $etablissement) }}"
                                method="POST"
                                enctype="multipart/form-data"
                            >
                                @csrf
                                    <div class="row mb-4">
                                    <div class="text-end">
                                        <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                            <i class="fs-5 ti ti-pencil"></i>
                                        </button>
                                    </div>
                                    <!-- Fréquence des opérations -->
                                    <div class="col-md-7">
                                        <h5>Fréquence des opérations :</h5>
                                        @foreach (['Quotidienne','Hebdomadaire','Mensuelle','Trimestrielle','Annuelle','Ponctuelle'] as $freq)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input"
                                                    type="radio"
                                                    name="relation_affaire_radio"
                                                    value="{{ $freq }}"
                                                    @checked($objetRelation->relation_affaire === $freq)>
                                                <label class="form-check-label">{{ $freq }}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Horizon de placement -->
                                    <div class="col-md-5">
                                        <h5>Horizon de placement :</h5>
                                        @foreach (['< 1 an','Entre 1 et 3 ans','Entre 3 et 5 ans','< 5 ans'] as $horizon)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input"
                                                    type="radio"
                                                    name="horizon_placement_radio"
                                                    value="{{ $horizon }}"
                                                    @checked($objetRelation->horizon_placement === $horizon)>
                                                <label class="form-check-label">{{ $horizon }}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>

                                <div class="row mb-4">

                                    <!-- Objet de la relation -->
                                    <div class="col-md-7">
                                        <h5>Objet de la relation d'affaire :</h5>
                                        @foreach (['Assurer des revenus réccurents','Profits à moyen et court terme','Gestion de la trésorerie'] as $objet)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input objetrelationcheck"
                                                    type="checkbox"
                                                    name="objet_relation[]"
                                                    value="{{ $objet }}"
                                                    @checked(in_array($objet, $objets))>
                                                <label class="form-check-label">{{ $objet }}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Mandataire -->
                                    <div class="col-md-5">
                                        <h5>Compte géré par mandataire :</h5>

                                        <div class="row mb-3 align-items-center">
                                            <div class="col-2 form-check form-switch">
                                                <input type="hidden" name="mandataire_check" value="0">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="mandataire_check"
                                                    value="1"
                                                    id="mandataire_id"
                                                    @checked($objetRelation->mandataire_check == 1)>
                                                <label id="mandataireLabel" class="form-check-label">
                                                    {{ $etablissement->ObjetRelation->mandataire_check ? 'Oui' : 'Non' }}
                                                </label>
                                            </div>

                                            <div class="col-4 mandat-hide d-none">
                                                <input type="text"
                                                    class="form-control  "
                                                    name="mandataire_label"
                                                    placeholder="Description"
                                                    value="{{ old('mandataire_label', $objetRelation->mandataire_input) }}">
                                            </div>

                                            <div class="col-2 mandat-hide d-none">
                                                <label>Date fin de mandat</label>
                                            </div>

                                            <div class="col-4 mandat-hide d-none">
                                                <input type="date"
                                                    class="form-control"
                                                    name="mandataire_fin_mandat_date"
                                                    value="{{ optional($objetRelation->mandataire_fin_mandat_date)->format('Y-m-d') }}">
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top:-10px;">
                                            <div class="col-4 mandat-hide d-none">
                                                <label for="mandat_file" class="btn btn-primary w-100">
                                                    <i class="ti ti-upload"></i> Mandat pouvoir
                                                </label>
                                                <input type="file" id="mandat_file" name="mandat_file" hidden>

                                                @if(!empty($objetRelation->mandat_file))
                                                    <a href="{{ asset('storage/' . $objetRelation->mandat_file) }}"
                                                    target="_blank"
                                                    class="text-primary ms-2">
                                                        <i class="ti ti-download"></i> Télécharger
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="text-start"><button type="submit" class="update btn btn-success mt-3 w-10">Mettre à jour</button></div>
                            </form>

                            @else
                                <h5 class="text-center">Aucun Objet et nature de la relation d'affaire</h5>
                            @endif

                        </div>
                    </div>
                </div>



                <div class="accordion-item justify-content-center text-center">
                    <div class="accordion-item text-center">
                        <h2 class="accordion-header" id="flush-headingRisque">
                            <button
                                class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#flush-risque"
                                aria-expanded="false"
                                aria-controls="flush-risque"
                                style="display: flex; flex-direction: column;"
                            >
                                Profil risque
                            </button>
                        </h2>

                        <div
                            id="flush-risque"
                            class="accordion-collapse collapse locked-mode p-4"
                            aria-labelledby="flush-headingRisque"
                            data-bs-parent="#accordionFlushExample"
                        >
                            <div class="accordion-body">
                            <div class="text-end">
                            <button type="button" class="btn mb-1 btn-light-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center edit-section-btn" title="Modifier cette section">
                                <i class="fs-5 ti ti-pencil"></i>
                            </button>
                        </div>
                                @php
                                    $profilRisque = $etablissement->ProfilRisque ?? null;
                                @endphp

                                @if($profilRisque)
                                <form method="POST"
                                    action="{{ route('profilrisque.update', $etablissement) }}">
                                    @csrf
                                    <div class="row">
                                        

                                        <!-- Département en charge -->
                                        <div class="col-md-6">
                                            <h5>Département en charge de la gestion des placements / investissements</h5>
                                            <div class="mb-3 row align-items-center">

                                                <!-- Switch -->
                                                <div class="col-1 form-check form-switch">
                                                    <input type="checkbox"
                                                        id="departement_gestion_id"
                                                        name="departement_en_charge_check"
                                                        class="form-check-input"
                                                        value="1"
                                                        @checked($profilRisque->departement_en_charge_check == 1)>
                                                    <label id="labelcheckbox">
                                                        {{ $profilRisque->departement_en_charge_check == 1 ? 'Oui' : 'Non' }}
                                                    </label>
                                                </div>

                                                <!-- Select: hidden if unchecked -->
                                                <div class="col-8" id="departement_gestion_data_container" style="{{ $profilRisque->departement_en_charge_check == 1 ? '' : 'display:none;' }}">
                                                    <select class="form-control" id="departement_gestion_data_id" name="departement_gestion_input">
                                                        @foreach([
                                                            'La part du portfeuille de valeurs mobilières',
                                                            'Inférieur à 5%',
                                                            'Entre 5% et 10%',
                                                            'Entre 10% et 25%',
                                                            'Entre 25% et 50%',
                                                            'Supérieure à 50%'
                                                        ] as $option)
                                                            <option value="{{ $option }}" @selected($profilRisque->departement_gestion_input == $option)>{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Instruments financiers -->
                                        <div class="col-md-6">
                                            <h5>Les instruments financiers souhaités</h5>

                                            @php
                                                $selectedInstruments = $profilRisque->instruments_souhaites_input ?? [];
                                                if (is_string($selectedInstruments)) {
                                                    $selectedInstruments = explode(',', $selectedInstruments);
                                                }
                                                $instruments = [
                                                    'OPCVM Monétaires', 'OPCVM Obligataires', 'OPCVM Actions',
                                                    'OPCVM Diversifiés', 'Bons de Trésor', 'Titres de dette privé', 'Actions'
                                                ];
                                            @endphp

                                            <div class="mb-3 row">
                                                <div class="col-12">
                                                    <!-- Checkboxes -->
                                                        @php
                                                        $selectedInstruments = [];

                                                        if (!empty($profilRisque->instruments_souhaites_input)) {
                                                            $selectedInstruments = array_map(
                                                                'trim',
                                                                explode(',', $profilRisque->instruments_souhaites_input)
                                                            );
                                                        }

                                                        $instruments = [
                                                            'OPCVM Monétaires', 'OPCVM Obligataires', 'OPCVM Actions',
                                                            'OPCVM Diversifiés', 'Bons de Trésor', 'Titres de dette privé', 'Actions'
                                                        ];
                                                    @endphp

                                                        @foreach($instruments as $index => $instrument)
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input"
                                                                    type="checkbox"
                                                                    name="instruments_souhaites_input[]"
                                                                    value="{{ $instrument }}"
                                                                    @checked(in_array($instrument, $selectedInstruments))>
                                                                <label class="form-check-label">{{ $instrument }}</label>
                                                            </div>
                                                        @endforeach

                                                        @php
                                                            $autres = array_diff($selectedInstruments, $instruments);
                                                        @endphp

                                                        <input type="text"
                                                            class="form-control"
                                                            placeholder="Autres instruments"
                                                            name="instruments_souhaites_autres"
                                                            value="{{ implode(', ', $autres) }}">

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Second Row: Niveau de risque et années d'investissement -->
                                    <div class="row">

                                        <!-- Niveau de risque toléré -->
                                        <div class="col-md-6">
                                            <h5>Le niveau de risque toléré</h5>
                                            @php $niveauRisque = $profilRisque->niveau_risque_tolere_radio ?? ''; @endphp
                                            <div class="mb-3 row">
                                                <div class="col-12">
                                                    @foreach(['Faible','Moyen','Elevé'] as $index => $val)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                id="nrTolere{{ $index+1 }}"
                                                                name="niveau_risque_tolere_radio"
                                                                value="{{ $val }}"
                                                                @checked($niveauRisque === $val)>
                                                            <label class="form-check-label" for="nrTolere{{ $index+1 }}">
                                                                {{ $val }}
                                                                @if($val == 'Faible') (Stratégie défensive)
                                                                @elseif($val == 'Moyen') (Stratégie équilibrée)
                                                                @elseif($val == 'Elevé') (Stratégie agressive)
                                                                @endif
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Années d'investissement -->
                                        <div class="col-md-6">
                                            <h5>Années d'investissement dans les produits financiers</h5>
                                            @php $anneesInvest = $profilRisque->annees_investissement_produits_finaniers ?? ''; @endphp
                                            <div class="mb-3 row">
                                                <div class="col-12">
                                                    @foreach(['Jamais', 'Jusqu’à 1 an', 'Entre 1 et 5 ans', 'Plus que 5 ans'] as $index => $val)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                id="anneesProduitsFinanciers{{ $index+1 }}"
                                                                name="annees_investissement_produits_finaniers"
                                                                value="{{ $val }}"
                                                                @checked($anneesInvest === $val)>
                                                            <label class="form-check-label" for="anneesProduitsFinanciers{{ $index+1 }}">{{ $val }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Third Row: Transactions -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Transactions en moyenne réalisés sur le marché courant 2 dernières années</h5>
                                            @php $transactions = $profilRisque->transactions_courant_2_annees ?? ''; @endphp
                                            <div class="mb-3 row">
                                                <div class="col-12">
                                                    @foreach(['Aucune', 'Moins de 30', 'Plus de 30'] as $index => $val)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                id="transactions2annees{{ $index+1 }}"
                                                                name="transactions_courant_2_annees"
                                                                value="{{ $val }}"
                                                                @checked($transactions === $val)>
                                                            <label class="form-check-label" for="transactions2annees{{ $index+1 }}">{{ $val }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="text-start"><button type="submit" class="update btn btn-success mt-3 w-10">Mettre à jour</button></div>
                                    </form>
                                @else
                                    <h5 class="text-center">Aucun profil risque disponible</h5>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- ---------------------
                            end Accordian Flush
                        ---------------- -->
              </div>    
    @endsection


