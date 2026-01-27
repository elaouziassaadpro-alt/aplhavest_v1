<?php

namespace App\Http\Controllers;

use App\Models\formejuridique;
use App\Models\InfoGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;   
use App\Models\Pays;
use Illuminate\Support\Str;

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
    $info = InfoGeneral::create([
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
$basePath = "documents/{$info->id}";

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


    /* ===================== 4. REDIRECT ===================== */
    return redirect()
        ->route('coordonneesbancaires.create', ['info_generales_id' => $info->id])
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InfoGeneral $infoGeneral)
    {
        //
    }
}
