<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EtablissementController;
// use App\Http\Controllers\Dashboard; // Removed to avoid conflict with Livewire
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
use App\Http\Controllers\ImportFilesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\Dashboard;

/*
|--------------------------------------------------------------------------
| Public / Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', [Dashboard::class ,'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Shared View Routes (Admin, CI, AK, BAK)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,CI,AK,BAK')->group(function () {

        // Etablissements View
        Route::prefix('etablissements')->group(function () {
            Route::get('/', [EtablissementController::class, 'index'])->name('etablissements.index');
            Route::get('/{etablissement}', [EtablissementController::class, 'show'])->name('etablissements.show');
        });

        // Info General View
        Route::get('etablissements/infogeneral/contact', [InfoGeneralController::class, 'Contactindex'])->name('contacts.index');

        // Other Index Views
        Route::get('etablissements/coordonneesbancaires/index', [CoordonneesBancairesController::class, 'index'])->name('coordonneesbancaires.index');
        Route::get('etablissements/actionnariat/index', [ActionnariatController::class, 'index'])->name('actionnariat.index');
        Route::get('etablissements/benificiaireeffectif/index', [BenificiaireEffectifController::class, 'index'])->name('benificiaireeffectif.index');
        Route::get('etablissements/administrateurs/index', [AdministrateursController::class, 'index'])->name('administrateurs.index');
        Route::get('etablissements/personneshabilites/index', [PersonnesHabilitesController::class, 'index'])->name('personneshabilites.index');

        // Rating
        Route::get('rating', [RatingEtablissementController::class, 'Rating'])->name('Rating');

        // Files List
        Route::get('/files-list', [ImportFilesController::class, 'filesList'])->name('files_list.index');

        // Profile
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
        });

        // Operations Bourse
        Route::get('operations/create', [OperationController::class, 'create'])->name('operations.create');
    });

    /*
    |--------------------------------------------------------------------------
    | Editor Routes (Admin, AK)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,AK,BAK')->group(function () {

        Route::prefix('etablissements')->group(function () {
            // Destruction and Validation
            Route::post('destroy-multiple', [EtablissementController::class, 'destroyMultiple'])->name('etablissements.destroy-multiple');
            Route::post('update-validation', [EtablissementController::class, 'updateValidation'])->name('etablissement.update.validation');

            // Info General Update/Create
            Route::prefix('create/infogeneral')->group(function () {
                Route::get('create', [InfoGeneralController::class, 'create'])->name('infogeneral.create');
                Route::post('store', [InfoGeneralController::class, 'store'])->name('infogeneral.store');
                Route::post('check-risque', [InfoGeneralController::class, 'checkRisque'])->name('infogeneral.checkRisque');
                Route::post('delete-multiple', [InfoGeneralController::class, 'deleteContact'])->name('contacts.delete-multiple');
                Route::post('update/{infoGeneral}', [InfoGeneralController::class, 'update'])->name('infoGenerales.update');
            });

            // Coordonnees Bancaires Update/Create
            Route::prefix('create/coordonneesbancaires')->group(function () {
                Route::get('create', [CoordonneesBancairesController::class, 'create'])->name('coordonneesbancaires.create');
                Route::post('store', [CoordonneesBancairesController::class, 'store'])->name('coordonneesbancaires.store');
                Route::post('update/{etablissement}', [CoordonneesBancairesController::class, 'update'])->name('coordonneesbancaires.update');
                Route::post('bulk-delete', [CoordonneesBancairesController::class, 'bulkDelete'])->name('coordonneesbancaires.bulkDelete');
            });

            // Typologie Update/Create
            Route::prefix('create/typologie')->group(function () {
                Route::get('create', [TypologieClientController::class, 'create'])->name('typologie.create');
                Route::post('store', [TypologieClientController::class, 'store'])->name('typologie.store');
                Route::post('update/{etablissement}', [TypologieClientController::class, 'update'])->name('typologie.update');
            });

            // Statut FATCA Update/Create
            Route::prefix('create/statutfatca')->group(function () {
                Route::get('create', [StatutFATCAController::class, 'create'])->name('statutfatca.create');
                Route::post('store', [StatutFATCAController::class, 'store'])->name('statutfatca.store');
                Route::post('update/{etablissement}', [StatutFATCAController::class, 'update'])->name('statutfatca.update');
            });

            // Situation Financiere Update/Create
            Route::prefix('create/situationfinanciere')->group(function () {
                Route::get('create', [SituationFinanciereController::class, 'create'])->name('situationfinanciere.create');
                Route::post('store', [SituationFinanciereController::class, 'store'])->name('situationfinanciere.store');
                Route::post('update/{etablissement}', [SituationFinanciereController::class, 'update'])->name('situationfinanciere.update');
            });

            // Actionnariat Update/Create
            Route::prefix('create/actionnariat')->group(function () {
                Route::get('create', [ActionnariatController::class, 'create'])->name('actionnariat.create');
                Route::post('store', [ActionnariatController::class, 'store'])->name('actionnariat.store');
                Route::post('bulk-delete', [ActionnariatController::class, 'bulkDelete'])->name('actionnariat.bulkDelete');
                Route::post('check-risque', [ActionnariatController::class, 'checkRisque'])->name('actionnariat.checkRisque');
                Route::post('update/{etablissement}', [ActionnariatController::class, 'update'])->name('actionnariat.update');
            });

            // Benificiaire Effectif Update/Create
            Route::prefix('create/benificiaireeffectif')->group(function () {
                Route::get('create', [BenificiaireEffectifController::class, 'create'])->name('benificiaireeffectif.create');
                Route::post('store', [BenificiaireEffectifController::class, 'store'])->name('benificiaireeffectif.store');
                Route::post('bulk-delete', [BenificiaireEffectifController::class, 'bulkDelete'])->name('beneficiaire.bulkDelete');
                Route::post('check-risque', [BenificiaireEffectifController::class, 'checkRisque'])->name('benificiaireeffectif.checkRisque');
                Route::post('update/{etablissement}', [BenificiaireEffectifController::class, 'update'])->name('benificiaireeffectif.update');
            });

            // Administrateurs Update/Create
            Route::prefix('create/administrateurs')->group(function () {
                Route::get('create', [AdministrateursController::class, 'create'])->name('administrateurs.create');
                Route::post('store', [AdministrateursController::class, 'store'])->name('administrateurs.store');
                Route::post('bulk-delete', [AdministrateursController::class, 'bulkDelete'])->name('administrateurs.bulkDelete');
                Route::post('check-risque', [AdministrateursController::class, 'checkRisque'])->name('administrateurs.checkRisque');
                Route::post('update/{etablissement}', [AdministrateursController::class, 'update'])->name('administrateurs.update');
            });

            // Personnes Habilites Update/Create
            Route::prefix('create/personneshabilites')->group(function () {
                Route::get('create', [PersonnesHabilitesController::class, 'create'])->name('personneshabilites.create');
                Route::post('store', [PersonnesHabilitesController::class, 'store'])->name('personneshabilites.store');
                Route::post('bulk-delete', [PersonnesHabilitesController::class, 'bulkDelete'])->name('personneshabilites.bulkDelete');
                Route::post('check-risque', [PersonnesHabilitesController::class, 'checkRisque'])->name('personneshabilites.checkRisque');
                Route::post('update/{etablissement}', [PersonnesHabilitesController::class, 'update'])->name('personneshabilites.update');
            });

            // Objet Relation
            Route::prefix('create/objetrelation')->group(function () {
                Route::get('create', [ObjetRelationController::class, 'create'])->name('objetrelation.create');
                Route::post('store', [ObjetRelationController::class, 'store'])->name('objetrelation.store');
                Route::post('update/{etablissement}', [ObjetRelationController::class, 'update'])->name('objetrelation.update');
            });

            // Profil Risque
            Route::prefix('create/profilrisque')->group(function () {
                Route::get('create', [ProfilRisqueController::class, 'create'])->name('profilrisque.create');
                Route::post('store', [ProfilRisqueController::class, 'store'])->name('profilrisque.store');
                Route::post('update/{etablissement}', [ProfilRisqueController::class, 'update'])->name('profilrisque.update');
            });
        });

        // Import Files Write Actions
        Route::controller(ImportFilesController::class)->group(function () {
            Route::get('/import-files', 'index')->name('import_files.index');
            Route::post('/import-files/cnasnus', 'importCnasnus')->name('import.cnasnus');
            Route::post('/import-files/anrf', 'importAnrf')->name('import.anrf');
        });

    });

    /*
    |--------------------------------------------------------------------------
    | Role Landing Pages
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::patch('/admin/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.update-status');
    });
    
  /*
    |--------------------------------------------------------------------------
    | Editor Routes (Admin, CI)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,CI')->group(function(){
        // Import Files Write Actions
        Route::controller(ImportFilesController::class)->group(function () {
            Route::get('/import-files', 'index')->name('import_files.index');
            Route::post('/import-files/cnasnus', 'importCnasnus')->name('import.cnasnus');
            Route::post('/import-files/anrf', 'importAnrf')->name('import.anrf');
        });
    });

});

require __DIR__.'/auth.php';