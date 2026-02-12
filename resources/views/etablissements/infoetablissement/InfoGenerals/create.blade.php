@extends('layouts.app')

@section('content')
<script src="{{ asset('dist/js/pages/info_general.js') }}"></script>
<script>window.dropdownData = {
        formejuridiques: @json($formejuridiques),

        pays: @json($pays)
    };</script>

<div class="container-fluid mw-100">

    <!-- ===================== Breadcrumb ===================== -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-2">Establishment</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ url('/') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Nouvel Établissement</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-center">
                    <img src="{{ asset('dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid" alt="Breadcrumb image">
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== FORM ===================== -->
    <form action="{{ route('infogeneral.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <!-- ===================== MAIN CARD ===================== -->
        <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">

            <!-- Card header -->
            <div class="px-6 py-4 border-b bg-gray-50 rounded-top">
                <h2 class="text-xl font-semibold text-gray-800">Informations Générales</h2>
            </div>

            <div class="card-body">

                <!-- ===================== ROW 1 ===================== -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Raison sociale</label>
                        <input type="text" class="form-control" name="raisonSocial" placeholder="Ex: Société ABC SARL"required >
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Capital social</label>
                        <input type="number" class="form-control" name="capitalSocialPrimaire" placeholder="Ex: 100000" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Forme juridique</label>
                        <div class="custom-select-wrapper">
                            <input type="text" id="formeInput" class="form-control" placeholder="Tapez pour rechercher...">
                            <ul id="formeDropdown" class="custom-dropdown">
                                @foreach($formejuridiques as $forme)
                                    <li data-value="{{ $forme->id }}">{{ $forme->libelle }}</li>
                                @endforeach
                            </ul>
                            <input type="hidden" id="formeSelect" name="FormeJuridique" >
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Date d'immatriculation</label>
                        <input type="date" class="form-control" name="dateImmatriculation" >
                    </div>
                </div>

                <!-- ===================== ROW 2 ===================== -->
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label>ICE</label>
                        <input type="text" class="form-control" name="ice" placeholder="Identifiant Commun de l’Entreprise" >
                    </div>
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <label class=" btn btn-primary w-100 mb-0">
                            <i class="ti ti-upload"></i> ICE
                            <input type="file" name="ice_file" hidden accept=".pdf,.jpg,.png" >
                        </label>
                    </div>

                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <label class="btn btn-primary w-100 mb-0">
                            <i class="ti ti-upload"></i> Statut
                            <input type="file" name="status_file" hidden accept=".pdf,.jpg,.png">
                        </label>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>RC</label>
                        <input type="text" class="form-control" name="rc_input" placeholder="Registre de commerce">
                    </div>
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <label class="btn btn-primary w-100 mb-0">
                            <i class="ti ti-upload"></i> RC
                            <input type="file" name="rc_file" hidden accept=".pdf,.jpg,.png">
                        </label>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>IF</label>
                        <input type="text" class="form-control" name="ifiscal" placeholder="Identifiant fiscal" >
                    </div>
                </div>

                <!-- ===================== ROW 3 ===================== -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Siège social</label>
                        <input type="text" class="form-control" name="siegeSocial" placeholder="Adresse complète" >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Lieu d'activité</label>
                        <div class="custom-select-wrapper">
                            <input type="text" id="formeInputpayact" class="form-control" placeholder="Tapez pour rechercher...">
                            <ul id="formeDropdownpayact" class="custom-dropdown">
                                @foreach($pays as $pay)
                                    <li data-value="{{ $pay->id }}">{{ $pay->libelle }}</li>
                                @endforeach
                            </ul>
                            <input type="hidden" id="formeSelectpayact" name="paysActivite" >
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Pays de résidence fiscale</label>
                        <div class="custom-select-wrapper">
                            <input type="text" id="formeInputpayresidence" class="form-control" placeholder="Tapez pour rechercher...">
                            <ul id="formeDropdownpayresidence" class="custom-dropdown">
                                @foreach($pays as $pay)
                                    <li data-value="{{ $pay->id }}">{{ $pay->libelle }}</li>
                                @endforeach
                            </ul>
                            <input type="hidden" id="formeSelectpayresidence" name="paysResidence" >
                        </div>
                    </div>
                </div>

                <!-- ===================== ROW 4 ===================== -->
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label>Autorité de régulation</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autorite_regulation_check" name="regule" value="1">
                            <label class="form-check-label"><span id="autoriteLabel">Non</span></label>
                        </div>
                        <div class="mt-2 d-none" id="autoriteInputWrapper">
                            <input type="text" class="form-control" name="nomRegulateur" placeholder="Nom de l’autorité">
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Téléphone</label>
                        <input type="tel" class="form-control" name="telephone" placeholder="+212 6XX XXX XXX" >
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>E-mail</label>
                        <input type="email" class="form-control" name="email" placeholder="contact@entreprise.com" >
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Site web</label>
                        <input type="text" class="form-control" name="siteweb" placeholder="https://www.site.com">
                    </div>
                </div>

                <!-- ===================== ROW 5: Société de gestion ===================== -->
                    <div class="row align-items-end">

                        <!-- Société de gestion toggle -->
                        <div class="col-md-2 mb-3">
                            <label>Société gestion</label>
                            <div class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="Societe_gestion_check"
                                    name="societe_gestion"
                                    value="1"
                                >
                                <label class="form-check-label">
                                    <span id="Societe_gestionLabel">Non</span>
                                </label>
                            </div>
                        </div>

                        <!-- File upload buttons -->
                        <div
                            id="societeGestionFiles"
                            class="row mt-3 d-none"
                        >
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <label class="btn btn-primary w-100 mb-0">
                                    <i class="ti ti-upload"></i> Agrément
                                    <input type="file" name="agrement_file" hidden accept=".pdf,.jpg,.png">
                                </label>
                            </div>

                            <div class="col-md-2 mb-3 d-flex align-items-end">
                                <label class="btn btn-primary w-100 mb-0">
                                    <i class="ti ti-upload"></i> NI
                                    <input type="file" name="NI" hidden accept=".pdf,.jpg,.png">
                                </label>
                            </div>

                            <div class="col-md-2 mb-3 d-flex align-items-end">
                                <label class="btn btn-primary w-100 mb-0">
                                    <i class="ti ti-upload"></i> FS
                                    <input type="file" name="FS" hidden accept=".pdf,.jpg,.png">
                                </label>
                            </div>

                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <label class="btn btn-primary w-100 mb-0">
                                    <i class="ti ti-upload"></i> RG
                                    <input type="file" name="RG" hidden accept=".pdf,.jpg,.png">
                                </label>
                            </div>
                        </div>
                    </div>
                <!-- ===================== CONTACTS ===================== -->
                <div class="mt-4">
                    <h5>Contacts</h5>
                    <button type="button" class="btn btn-light-info btn-sm mb-2" id="addContactRowBtn" onclick="ajoutercontact()">
                        Ajouter un contact
                    </button>
                    <div class="contactsRows"></div>
                </div>


            </div>
        </div>
</div>
        <!-- ===================== SAVE BUTTON ===================== -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-save d-flex align-items-center justify-content-center mx-auto">
                <svg class="w-6 h-6 me-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z"/>
                </svg>
                Save
            </button>
        </div>

    </form>


@endsection
