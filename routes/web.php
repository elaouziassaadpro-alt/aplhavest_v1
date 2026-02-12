<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\InfoGeneralController;
use App\Http\Controllers\CoordonneesBancairesController;
use App\Http\Controllers\TypologieClientController;
use App\Http\Controllers\StatutFATCAController;
use App\Http\Controllers\SituationFinanciereController;
use App\Http\Controllers\ActionnariatController;
use App\Http\Controllers\BenificiaireEffectifController;
use App\Http\Controllers\AdministrateursController;
use App\Http\Controllers\PersonnesHabilitesController;
use App\Http\Controllers\ObjetRelationController;
use App\Http\Controllers\ProfilRisqueController;
use App\Http\Controllers\RatingEtablissementController;

// Dashboard
Route::get('/', [Dashboard::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Etablissements
Route::prefix('etablissements')->group(function () {

    Route::get('/', [EtablissementController::class,'index'])->name('etablissements.index');
    Route::get('/{etablissement}', [EtablissementController::class, 'show'])->name('etablissements.show');
    Route::post('etablissements/destroy-multiple', [EtablissementController::class, 'destroyMultiple'])->name('etablissements.destroy-multiple');



    // Validation update
    Route::post('/update-validation', [EtablissementController::class, 'updateValidation'])->name('etablissement.update.validation');

    // Info General
    Route::prefix('create/infogeneral')->group(function () {
        Route::get('create',[InfoGeneralController::class,'create'])->name('infogeneral.create');
        Route::post('store',[InfoGeneralController::class,'store'])->name('infogeneral.store');
        Route::get('contact', [InfoGeneralController::class, 'Contactindex'])->name('contacts.index');
        Route::post('delete-multiple', [InfoGeneralController::class, 'deleteContact'])->name('contacts.delete-multiple');
        Route::post('update/{infoGeneral}', [InfoGeneralController::class, 'update'])->name('infoGenerales.update');


    });

    // Coordonnees bancaires
    Route::prefix('create/coordonneesbancaires')->group(function () {
        Route::get('index',[CoordonneesBancairesController::class,'index'])->name('coordonneesbancaires.index');
        Route::get('create',[CoordonneesBancairesController::class,'create'])->name('coordonneesbancaires.create');
        Route::post('store',[CoordonneesBancairesController::class,'store'])->name('coordonneesbancaires.store');
        Route::post('update/{etablissement}',[CoordonneesBancairesController::class,'update'])->name('coordonneesbancaires.update');
        Route::post('bulk-delete', [CoordonneesBancairesController::class, 'bulkDelete'])->name('coordonneesbancaires.bulkDelete');
    });

    // Typologie
    Route::prefix('create/typologie')->group(function () {
        Route::get('create',[TypologieClientController::class,'create'])->name('typologie.create');
        Route::post('store',[TypologieClientController::class,'store'])->name('typologie.store');
        Route::post('update/{etablissement}',[TypologieClientController::class,'update'])->name('typologie.update');
    });

    // Statut FATCA
    Route::prefix('create/statutfatca')->group(function () {
        Route::get('create',[StatutFATCAController::class,'create'])->name('statutfatca.create');
        Route::post('store',[StatutFATCAController::class,'store'])->name('statutfatca.store');
        Route::post('update/{statutFatca}', [StatutFATCAController::class, 'update'])
    ->name('statutfatca.update');
    });

    // Situation Financiere
    Route::prefix('create/situationfinanciere')->group(function () {
        Route::get('create',[SituationFinanciereController::class,'create'])->name('situationfinanciere.create');
        Route::post('store',[SituationFinanciereController::class,'store'])->name('situationfinanciere.store');
        Route::post('update/{etablissement}', [SituationFinanciereController::class, 'update'])->name('situationfinanciere.update');

    });

    // Actionnariat
    Route::prefix('create/actionnariat')->group(function () {
        Route::get('create',[ActionnariatController::class,'create'])->name('actionnariat.create');
        Route::post('store',[ActionnariatController::class,'store'])->name('actionnariat.store');
        Route::get('index',[ActionnariatController::class,'index'])->name('actionnariat.index');
        Route::post('/actionnariat/bulk-delete', [ActionnariatController::class, 'bulkDelete'])
    ->name('actionnariat.bulkDelete');
        Route::post('update/{etablissement}',[ActionnariatController::class,'update'])->name('actionnariat.update');


    });

    // Benificiaire Effectif
    Route::prefix('create/benificiaireeffectif')->group(function () {
        Route::get('index',[BenificiaireEffectifController::class,'index'])->name('benificiaireeffectif.index');
        Route::get('create',[BenificiaireEffectifController::class,'create'])->name('benificiaireeffectif.create');
        Route::post('store',[BenificiaireEffectifController::class,'store'])->name('benificiaireeffectif.store');
                Route::post('bulk-delete',[BenificiaireEffectifController::class,'bulkDelete'])->name('beneficiaire.bulkDelete');
        Route::post('update/{etablissement}',[BenificiaireEffectifController::class,'update'])->name('benificiaireeffectif.update');

    });

    // Administrateurs
    Route::prefix('create/administrateurs')->group(function () {
        Route::get('index',[AdministrateursController::class,'index'])->name('administrateurs.index');
        Route::get('create',[AdministrateursController::class,'create'])->name('administrateurs.create');
        Route::post('store',[AdministrateursController::class,'store'])->name('administrateurs.store');
                Route::post('/administrateurs/bulk-delete', [AdministrateursController::class, 'bulkDelete'])->name('administrateurs.bulkDelete');
        Route::post('update/{etablissement}',[AdministrateursController::class,'update'])->name('administrateurs.update');

    });

    // Personnes Habilites
    Route::prefix('create/personneshabilites')->group(function () {
        Route::get('index',[PersonnesHabilitesController::class,'index'])->name('personneshabilites.index');
        Route::get('create',[PersonnesHabilitesController::class,'create'])->name('personneshabilites.create');
        Route::post('store',[PersonnesHabilitesController::class,'store'])->name('personneshabilites.store');
        Route::post('bulk-delete',[PersonnesHabilitesController::class,'bulkDelete'])->name('personneshabilites.bulkDelete');
        Route::post('update/{etablissement}',[PersonnesHabilitesController::class,'update'])->name('personneshabilites.update');

    });

    // Objet Relation
    Route::prefix('create/objetrelation')->group(function () {
        Route::get('create',[ObjetRelationController::class,'create'])->name('objetrelation.create');
        Route::post('store',[ObjetRelationController::class,'store'])->name('objetrelation.store');
        Route::post('update/{etablissement}',[ObjetRelationController::class,'update'])->name('objetrelation.update');
    });

    // Profil Risque
    Route::prefix('create/profilrisque')->group(function () {
        Route::get('create',[ProfilRisqueController::class,'create'])->name('profilrisque.create');
        Route::post('store',[ProfilRisqueController::class,'store'])->name('profilrisque.store');
        Route::post('update/{etablissement}',[ProfilRisqueController::class,'update'])->name('profilrisque.update');
    });
});

// Rating
Route::get('rating',[RatingEtablissementController::class,'Rating'])->name('Rating');

require __DIR__.'/auth.php';
