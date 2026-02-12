<?php

namespace App\Http\Controllers;

use App\Models\formejuridique;
use App\Models\InfoGeneral;
use Illuminate\Http\Request;
use App\Models\Pays;
use Illuminate\Support\Str;
use App\Models\Etablissement;
use App\Models\Contact;
use App\Http\Controllers\RatingEtablissementController;
use Illuminate\Support\Facades\Storage;



class InfoGeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $formejuridiques = formejuridique::all();
        $pays = Pays::all();
        return view("etablissements.infoetablissement.infogenerals.create", compact('formejuridiques', 'pays'));
    }

    /**
     * Store a newly created resource in storage.
     */


public function store(Request $request)
{
    /* ===================== 1. VALIDATION ===================== */
    $validated = $request->validate([
        'raisonSocial'            => 'required|string|max:200',
        'capitalSocialPrimaire'   => 'required|numeric|min:0',
        'FormeJuridique'          => 'nullable|exists:formes_juridiques,id',
        'dateImmatriculation'     => 'nullable|date',

        'ice'                     => 'nullable|string|max:100',
        'ice_file'                => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'status_file'                => 'nullable|file|mimes:pdf,jpg,png|max:2048',

        'rc_input'                => 'nullable|string|max:100',
        'rc_file'                => 'nullable|file|mimes:pdf,jpg,png|max:2048',

        'ifiscal'                 => 'nullable|string|max:100',

        'siegeSocial'             => 'nullable|string|max:350',
        'paysActivite'            => 'nullable|exists:pays,id',
        'paysResidence'           => 'nullable|exists:pays,id',

        'regule'                  => 'nullable|boolean',
        'nomRegulateur'           => 'nullable|string|max:200',

        'telephone'               => 'nullable|string|max:15',
        'email'                   => 'nullable|email|max:200',
        'siteweb'                 => 'nullable|string|max:100',

        'societe_gestion'         => 'nullable|boolean',

        /* Files */
        'agrement_file'           => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'NI'                      => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'FS'                      => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'RG'                      => 'nullable|file|mimes:pdf,jpg,png|max:2048',
    ]);

    /* ===================== 2. CREATE BASE RECORD ===================== */

    $etablissement = Etablissement::create([
        'name' => $request->raisonSocial,
    ]);
    $info = InfoGeneral::create([
        'etablissement_id'       => $etablissement->id,
        'raisonSocial'           => $request->raisonSocial,
        'capitalSocialPrimaire'  => $request->capitalSocialPrimaire,
        'FormeJuridique'         => $request->FormeJuridique,
        'dateImmatriculation'    => $request->dateImmatriculation,

        'ice'                    => $request->ice,
        'rc'                     => $request->rc_input,
        'ifiscal'                => $request->ifiscal,
        'siegeSocial'            => $request->siegeSocial,

        'paysActivite'           => $request->paysActivite,
        'paysResidence'          => $request->paysResidence,

        'regule'                 => $request->boolean('regule'),
        'nomRegulateur'          => $request->nomRegulateur,

        'telephone'              => $request->telephone,
        'email'                  => $request->email,
        'siteweb'                => $request->siteweb,

        'societe_gestion'        => $request->boolean('societe_gestion'),
    ]);

    /* ===================== 3. FILE UPLOADS ===================== */
$slug = Str::slug($request->raisonSocial);
$basePath = "informations_generales/{$info->id}";

$uploadedPaths = [];

$files = [
    'ice_file'      => 'ice_file',
    'status_file'   => 'status_file',
    'rc_file'       => 'rc_file',
    'agrement_file' => 'agrement',
    'NI'            => 'NI',
    'FS'            => 'FS',
    'RG'            => 'RG',
];

foreach ($files as $input => $folder) {
    if ($request->hasFile($input)) {
        $file = $request->file($input);
        $filename = "{$slug}-{$folder}-" . time() . "." . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            "{$basePath}/{$folder}",
            $filename,
            'public'
        );

        // Collect for single update
        $uploadedPaths[$input] = $path;
    }
}

// Update all uploaded files at once
if (!empty($uploadedPaths)) {
    $info->update($uploadedPaths);
}
/* ===================== 4. SAVE CONTACTS ===================== */
$contactsCount = count($request->input('noms_contacts', []));

for ($i = 0; $i < $contactsCount; $i++) {
    $nom = $request->input('noms_contacts')[$i] ?? null;
    $prenom = $request->input('prenoms_contacts')[$i] ?? null;
    $fonction = $request->input('fonctions_contacts')[$i] ?? null;
    $telephone = $request->input('telephones_contacts')[$i] ?? null;
    $email = $request->input('emails_contacts')[$i] ?? null;

    // Skip empty rows
    if (!$nom && !$prenom && !$telephone && !$email) continue;

    // Save in DB
    $info->contacts()->create([
        'nom'      => $nom,
        'prenom'   => $prenom,
        'fonction' => $fonction,
        'telephone'    => $telephone,
        'email'    => $email,
    ]);
} 


            

    $etablissement = Etablissement::findOrFail($info->etablissement_id);

    // Trigger rating update
    $etablissement->updateRiskRating();

    if ($etablissement->fresh()->isCompleted()) {
        return redirect()->route('Rating', ['etablissement_id' => $etablissement->id]);
    }
    
    
    /* ===================== 4. REDIRECT ===================== */
    return redirect()
       ->route('coordonneesbancaires.create', ['etablissement_id' => $etablissement->id])
    ->with('success', 'Informations enregistrées avec succès');
}


    /**
     * Display the specified resource.
     */
    public function show(InfoGeneral $infoGeneral)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InfoGeneral $infoGeneral)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, InfoGeneral $infoGeneral)
{
    // 1️⃣ Validation
    $validated = $request->validate([
        'raisonSocial'            => 'required|string|max:200',
        'capitalSocialPrimaire'   => 'required|numeric|min:0',
        'FormeJuridique'          => 'nullable|exists:formes_juridiques,id',
        'dateImmatriculation'     => 'nullable|date',

        'ice'                     => 'nullable|string|max:100',
        'ice_file'                => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'status_file'             => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'rc_input'                => 'nullable|string|max:100',
        'rc_file'                 => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'ifiscal'                 => 'nullable|string|max:100',
        'siegeSocial'             => 'nullable|string|max:350',
        'paysActivite'            => 'nullable|exists:pays,id',
        'paysResidence'           => 'nullable|exists:pays,id',
        'regule'                  => 'nullable|boolean',
        'nomRegulateur'           => 'nullable|string|max:200',
        'telephone'               => 'nullable|string|max:15',
        'email'                   => 'nullable|email|max:200',
        'siteweb'                 => 'nullable|string|max:100',
        'societe_gestion'         => 'nullable|boolean',

        /* Files */
        'agrement_file'           => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'NI'                      => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'FS'                      => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'RG'                      => 'nullable|file|mimes:pdf,jpg,png|max:2048',
    ]);

// Mettre à jour l'enregistrement existant
    $infoGeneral->update([
        'raisonSocial' => $request->raisonSocial,
        'capitalSocialPrimaire' => $request->capitalSocialPrimaire,
        'FormeJuridique' => $request->FormeJuridique,
        'dateImmatriculation' => $request->dateImmatriculation,
        'ice' => $request->ice,
        'rc' => $request->rc_input,
        'ifiscal' => $request->ifiscal,
        'siegeSocial' => $request->siegeSocial,
        'paysActivite' => $request->paysActivite,
        'paysResidence' => $request->paysResidence,
        'regule' => $request->boolean('regule'),
        'nomRegulateur' => $request->nomRegulateur,
        'telephone' => $request->telephone,
        'email' => $request->email,
        'siteweb' => $request->siteweb,
        'societe_gestion' => $request->boolean('societe_gestion'),
    ]);

    /* ===================== 3. FILE UPLOADS ===================== */
$slug = Str::slug($request->raisonSocial);
$basePath = "informations_generales/{$infoGeneral->id}";

$uploadedPaths = [];

$files = [
    'ice_file'      => 'ice_file',
    'status_file'   => 'status_file',
    'rc_file'       => 'rc_file',
    'agrement_file' => 'agrement',
    'NI'            => 'NI',
    'FS'            => 'FS',
    'RG'            => 'RG',
];

foreach ($files as $input => $folder) {
    // Si societe_gestion est false, on supprime tous les fichiers concernés
    if (!$infoGeneral->societe_gestion && in_array($input, ['agrement_file', 'NI', 'FS', 'RG'])) {
        if ($infoGeneral->$input) {
            Storage::disk('public')->delete($infoGeneral->$input);
        }
        $uploadedPaths[$input] = null; // Mettre à null dans la BDD
        continue; // Passe à l'itération suivante
    }

    // Upload du nouveau fichier s'il y a un fichier
    if ($request->hasFile($input)) {
        // Supprimer l'ancien fichier si existant
        if ($infoGeneral->$input) {
            Storage::disk('public')->delete($infoGeneral->$input);
        }

        $file = $request->file($input);
        $filename = "{$slug}-{$folder}-" . time() . "." . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            "{$basePath}/{$folder}",
            $filename,
            'public'
        );

        $uploadedPaths[$input] = $path;
    }
}




// Update all uploaded files at once
if (!empty($uploadedPaths)) {
    $infoGeneral->update($uploadedPaths);
}
$noms       = $request->input('noms_contacts', []);
$prenoms    = $request->input('prenoms_contacts', []);
$fonctions  = $request->input('fonctions_contacts', []);
$telephones = $request->input('telephones_contacts', []);
$emails     = $request->input('emails_contacts', []);

$contacts = [];

foreach ($noms as $i => $nom) {

    $contact = [
        'nom'       => $nom,
        'prenom'    => $prenoms[$i] ?? null,
        'fonction'  => $fonctions[$i] ?? null,
        'telephone' => $telephones[$i] ?? null,
        'email'     => $emails[$i] ?? null,
    ];

    // ✅ Ignore lignes vides
    if (!$contact['nom'] && !$contact['prenom'] && !$contact['telephone'] && !$contact['email']) {
        continue;
    }

    $contacts[] = $contact;
}

// ✅ Delete once
$infoGeneral->contacts()->delete();

// ✅ Insert
$infoGeneral->contacts()->createMany($contacts);
    
    // Trigger rating update
    $infoGeneral->etablissement?->updateRiskRating();

    return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InfoGeneral $infoGeneral)
    {
        //
    }
    // Méthode suppression multiple
    public function deleteContact(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:contacts,id',
        ]);

        Contact::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Contacts supprimés avec succès.'
        ]);
    }
    public function Contactindex(){
        $contacts = Contact::all();
        return view('etablissements.infoetablissement.infoGenerals.Contact.index', compact('contacts'));
    }
}
