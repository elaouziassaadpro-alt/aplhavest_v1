 
@extends('layouts.app')
@section('content')

    <div class="container-fluid mw-100">
        <x-breadcrumb-header
            :etablissementName="$etablissement->name"
            activePage="Dossier Etablissement"
        />

        <div class="card shadow-sm border mt-3">
            <div class="card-header">
                <h5 class="card-title">Etablissement : {{ $etablissement->name }}</h5>
            </div>
            <div class="card-body">
                <div class="accordion accordion-flush" id="accordionFlushExample">

                    <!-- 1. Informations générales -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
                                Informations générales
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-info-general :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 2. Coordonnées bancaires -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo">
                                Coordonnées bancaires
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-coordonnees-bancaires :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 3. Typologie du client -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree">
                                Typologie du client
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-typologie-client :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 4. Statut FATCA -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour">
                                Statut FATCA
                            </button>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-statut-fatca :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 5. Situation financière -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive">
                                Situation financière et patrimoniale
                            </button>
                        </h2>
                        <div id="flush-collapseFive" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-situation-financiere :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 6. Actionnariat -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix">
                                Actionnariat
                            </button>
                        </h2>
                        <div id="flush-collapseSix" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-actionnariat :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 7. Bénéficiaire effectif -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingSeven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven">
                                Bénéficiaire effectif
                            </button>
                        </h2>
                        <div id="flush-collapseSeven" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-benificiaire-effectif :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 8. Les administrateurs -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingEight">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight">
                                Les administrateurs
                            </button>
                        </h2>
                        <div id="flush-collapseEight" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-administrateurs :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 9. Les personnes habilitées -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingNine">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine">
                                Les personnes habilitées à faire fonctionner le compte
                            </button>
                        </h2>
                        <div id="flush-collapseNine" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-personne-habilite :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 10. Objet et nature de la relation -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingTen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTen">
                                Objet et nature de la relation d'affaire
                            </button>
                        </h2>
                        <div id="flush-collapseTen" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-objet-relation :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                    <!-- 11. Profil risque -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingEleven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEleven">
                                Profil risque
                            </button>
                        </h2>
                        <div id="flush-collapseEleven" class="accordion-collapse collapse p-4" data-bs-parent="#accordionFlushExample">
                            <livewire:edit-profil-risque :etablissement_id="$etablissement->id" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection