<?php

namespace App\Http\Controllers;

use App\Models\formejuridique;
use App\Models\InfoGeneral;
use Illuminate\Http\Request;
use App\Models\Pays;
use Illuminate\Support\Str;
use App\Models\Etablissement;
use App\Models\Contact;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        return view("etablissements.infoetablissement.InfoGenerals.create", compact('formejuridiques', 'pays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'raisonSocial'            => 'required|string|max:200',
            'capitalSocialPrimaire'   => 'required|numeric|min:0',
            'FormeJuridique'          => 'nullable|exists:formes_juridiques,id',
            'dateImmatriculation'     => 'nullable|date',
            'ice'                     => 'nullable|string|max:100',
            'rc_input'                => 'nullable|string|max:100',
            'ifiscal'                 => 'nullable|string|max:100',
            'siegeSocial'             => 'nullable|string|max:350',
            'paysActivite'            => 'nullable|exists:pays,id',
            'paysResidence'           => 'nullable|exists:pays,id',
            'note'                    => 'nullable|numeric',
            'percentage'              => 'nullable|numeric',
            'table_match'             => 'nullable|string',
            'match_id'              => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
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
                'note'                   => $request->note ?? 1,
                'percentage'             => $request->percentage ?? 0,
                'table_match'            => $request->table_match,
                'match_id'             => $request->match_id,
            ]);

            // File Uploads
            $slug = Str::slug($request->raisonSocial);
            $basePath = "informations_generales/{$info->id}";
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
                    $info->$input = $file->storeAs("{$basePath}/{$folder}", $filename, 'public');
                }
            }
            $info->save();

            // Contacts
            $noms = $request->input('noms_contacts', []);
            foreach ($noms as $i => $nom) {
                if (!$nom) continue;
                $info->contacts()->create([
                    'nom'       => $nom,
                    'prenom'    => $request->input("prenoms_contacts.$i"),
                    'fonction'  => $request->input("fonctions_contacts.$i"),
                    'telephone' => $request->input("telephones_contacts.$i"),
                    'email'     => $request->input("emails_contacts.$i"),
                ]);
            }

            $etablissement->updateRiskRating();

            DB::commit();

            return redirect()
                ->route('coordonneesbancaires.create', ['etablissement_id' => $etablissement->id])
                ->with('success', 'Informations enregistrées avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing InfoGeneral: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l’enregistrement : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InfoGeneral $infoGeneral)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InfoGeneral $infoGeneral)
    {
        $request->validate([
            'raisonSocial'            => 'required|string|max:200',
            'capitalSocialPrimaire'   => 'required|numeric|min:0',
            'FormeJuridique'          => 'nullable|exists:formes_juridiques,id',
            'dateImmatriculation'     => 'nullable|date',
            'note'                    => 'nullable|numeric',
            'percentage'              => 'nullable|numeric',
            'table_match'             => 'nullable|string',
            'match_id'              => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $infoGeneral->update([
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
                'note'                   => $request->note ?? 1,
                'percentage'             => $request->percentage ?? 0,
                'table_match'            => $request->table_match,
                'match_id'             => $request->match_id,
            ]);

            // File Uploads
            $slug = Str::slug($request->raisonSocial);
            $basePath = "informations_generales/{$infoGeneral->id}";
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
                    if ($infoGeneral->$input) Storage::disk('public')->delete($infoGeneral->$input);
                    $file = $request->file($input);
                    $filename = "{$slug}-{$folder}-" . time() . "." . $file->getClientOriginalExtension();
                    $infoGeneral->$input = $file->storeAs("{$basePath}/{$folder}", $filename, 'public');
                }
            }
            $infoGeneral->save();

            // Contacts
            $infoGeneral->contacts()->delete();
            $noms = $request->input('noms_contacts', []);
            foreach ($noms as $i => $nom) {
                if (!$nom) continue;
                $infoGeneral->contacts()->create([
                    'nom'       => $nom,
                    'prenom'    => $request->input("prenoms_contacts.$i"),
                    'fonction'  => $request->input("fonctions_contacts.$i"),
                    'telephone' => $request->input("telephones_contacts.$i"),
                    'email'     => $request->input("emails_contacts.$i"),
                ]);
            }

            if ($infoGeneral->etablissement) {
                $infoGeneral->etablissement->updateRiskRating();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Informations mises à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating InfoGeneral: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function checkRisque(Request $request)
    {
        $request->validate([
            'raisonSocial' => 'required|string|max:255',
        ]);

        $info = new InfoGeneral();
        $info->nom_rs = $request->raisonSocial; // trait uses nom_rs
        
        $risk = $info->checkRisk();
        
        $info->raisonSocial = $request->raisonSocial;
        $info->note = $risk['note'] ?? 1;
        $info->percentage = $risk['percentage'] ?? 0;
        $info->table_match = $risk['table'] ?? null;
        $info->match_id = $risk['match_id'] ?? null;

        return view('etablissements.infoetablissement.InfoGenerals.check_risque', [
            'info' => $info,
            'risk' => $risk,
            'requestData' => $request->all()
        ]);
    }

    public function deleteContact(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:contacts,id',
        ]);

        try {
            $firstContact = Contact::with('infoGeneral.etablissement')->find($request->ids[0]);
            $etablissement = $firstContact?->infoGeneral?->etablissement;

            Contact::whereIn('id', $request->ids)->delete();

            // Trigger rating update
            $etablissement?->updateRiskRating();

            return response()->json([
                'success' => true,
                'message' => 'Contacts supprimés avec succès.'
            ]);
        } catch (\Exception $e) {
            Log::error("Delete contact error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    public function Contactindex()
    {
        return view('etablissements.infoetablissement.InfoGenerals.Contact.index');
    }
}
