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




Route::get('/', [Dashboard::class,'index' ])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('etablissements',[EtablissementController::class,'index'])->name('etablissements.index');
//create etablissement
//create infogeneral
Route::get('etablissements/create/infogeneral/create',[InfoGeneralController::class,'create'])->name('infogeneral.create');
Route::post('etablissements/create/infogeneral/store',[InfoGeneralController::class,'store'])->name('infogeneral.store');
//create coordonneesbancaires
Route::get('etablissements/create/coordonneesbancaires/create',[CoordonneesBancairesController::class,'create'])->name('coordonneesbancaires.create');
Route::post('etablissements/create/coordonneesbancaires/store',[CoordonneesBancairesController::class,'store'])->name('coordonneesbancaires.store');
//create typologies
Route::get('etablissements/create/Typologie/create',[TypologieClientController::class,'create'])->name('typologie.create');
Route::post('etablissements/create/Typologie/store',[TypologieClientController::class,'store'])->name('typologie.store');
//create StatutFATCA

Route::get('etablissements/create/statutfatca/create',[StatutFATCAController::class,'create'])->name('statutfatca.create');
Route::post('etablissements/create/statutfatca/store',[StatutFATCAController::class,'store'])->name('statutfatca.store');
//create Situation financière et patrimoniale
Route::get('etablissements/create/situationfinanciere/create',[SituationFinanciereController::class,'create'])->name('situationfinanciere.create');
Route::post('etablissements/create/situationfinanciere/store',[SituationFinanciereController::class,'store'])->name('situationfinanciere.store');
// create Actionnariat
Route::get('etablissements/create/actionnariat/create',[ActionnariatController::class,'create'])->name('actionnariat.create');
Route::post('etablissements/create/actionnariat/store',[ActionnariatController::class,'store'])->name('actionnariat.store');
// create Benificiaire Effectif
Route::get('etablissements/create/benificiaireeffectif/create',[BenificiaireEffectifController::class,'create'])->name('benificiaireeffectif.create');
Route::post('etablissements/create/benificiaireeffectif/store',[BenificiaireEffectifController::class,'store'])->name('benificiaireeffectif.store');
// create Administrateurs
Route::get('etablissements/create/administrateurs/create',[AdministrateursController::class,'create'])->name('administrateurs.create');
Route::post('etablissements/create/administrateurs/store',[AdministrateursController::class,'store'])->name('administrateurs.store');
// create PersonnesHabilites
Route::get('etablissements/create/personneshabilites/create',[PersonnesHabilitesController::class,'create'])->name('personneshabilites.create');
Route::post('etablissements/create/personneshabilites/store',[PersonnesHabilitesController::class,'store'])->name('personneshabilites.store');
// create ObjetRelation
Route::get('etablissements/create/objetrelation/create',[ObjetRelationController::class,'create'])->name('objetrelation.create');
Route::post('etablissements/create/objetrelation/store',[ObjetRelationController::class,'store'])->name('objetrelation.store');
// create profilRisque
Route::get('etablissements/create/profilrisque/create',[ProfilRisqueController::class,'create'])->name('profilrisque.create');
Route::post('etablissements/create/profilrisque/store',[ProfilRisqueController::class,'store'])->name('profilrisque.store');

#infogeneral

require __DIR__.'/auth.php';
