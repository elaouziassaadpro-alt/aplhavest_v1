<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;
use App\Models\ObjetRelation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateObjetRelation extends Component
{
    use WithFileUploads;

    public $etablissement;
    public $etablissement_id;
    public $objetRelation;

    // Form Fields
    public $relation_affaire;
    public $horizon_placement;
    public $objet_relation_checked = []; // Array for checkboxes
    public $mandataire_check = false;
    public $mandataire_label;
    public $mandataire_fin_mandat_date;
    public $mandat_file;
    
    public $redirect_to;

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::findOrFail($etablissement_id);
        $this->redirect_to = request()->query('redirect_to');

        // Load existing data if available
        $this->objetRelation = ObjetRelation::where('etablissement_id', $etablissement_id)->first();

        if ($this->objetRelation) {
            $this->relation_affaire = $this->objetRelation->relation_affaire;
            $this->horizon_placement = $this->objetRelation->horizon_placement;
            
            // objet_relation is stored as array in model (casted) or string in DB? 
            // Controller implies array in request but implode in store. Model has casts => 'array'.
            // Let's assume model accessor works if casted, otherwise we might need to explode.
            $this->objet_relation_checked = $this->objetRelation->objet_relation ?? [];
            if (is_string($this->objet_relation_checked)) {
                 // Fallback if casting fail or old data
                 $this->objet_relation_checked = explode(',', $this->objet_relation_checked);
            }

            $this->mandataire_check = $this->objetRelation->mandataire_check;
            $this->mandataire_label = $this->objetRelation->mandataire_input;
            $this->mandataire_fin_mandat_date = $this->objetRelation->mandataire_fin_mandat_date ? $this->objetRelation->mandataire_fin_mandat_date->format('Y-m-d') : null;
        }
    }

    public function save()
    {
        $this->validate([
            'relation_affaire' => 'required|string',
            'horizon_placement' => 'required|string',
            'objet_relation_checked' => 'nullable|array',
            'mandataire_check' => 'boolean',
            'mandataire_label' => 'nullable|required_if:mandataire_check,true|string|max:255',
            'mandataire_fin_mandat_date' => 'nullable|required_if:mandataire_check,true|date',
            'mandat_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ], [
            'relation_affaire.required' => 'La fréquence des opérations est requise.',
            'horizon_placement.required' => 'L\'horizon de placement est requis.',
            'mandataire_label.required_if' => 'Le nom du mandataire est requis si la case est cochée.',
            'mandataire_fin_mandat_date.required_if' => 'La date de fin de mandat est requise si la case est cochée.',
        ]);

        // Create or Update
        $objetRelation = $this->objetRelation ?? new ObjetRelation();
        $objetRelation->etablissement_id = $this->etablissement_id;

        $objetRelation->relation_affaire = $this->relation_affaire;
        $objetRelation->horizon_placement = $this->horizon_placement;
        $objetRelation->objet_relation = $this->objet_relation_checked;
        
        $objetRelation->mandataire_check = $this->mandataire_check;

        if ($this->mandataire_check) {
            $objetRelation->mandataire_input = !empty($this->mandataire_label) ? $this->mandataire_label : null;
            $objetRelation->mandataire_fin_mandat_date = !empty($this->mandataire_fin_mandat_date) ? $this->mandataire_fin_mandat_date : null;

            if ($this->mandat_file) {
                 // Delete old file if exists
                if ($objetRelation->mandat_file && Storage::disk('public')->exists($objetRelation->mandat_file)) {
                    Storage::disk('public')->delete($objetRelation->mandat_file);
                }

                $slug = Str::slug($this->etablissement->raisonSocial ?? 'mandat');
                $path = $this->mandat_file->storeAs(
                    "objet_relation/{$this->etablissement->id}",
                    "{$slug}-mandat-" . time() . '.' . $this->mandat_file->getClientOriginalExtension(),
                    'public'
                );
                $objetRelation->mandat_file = $path;
            }
        } else {
            $objetRelation->mandataire_input = null;
            $objetRelation->mandataire_fin_mandat_date = null;
             // We generally keep the file or delete it? Controller logic implies nulling fields but didn't explicitly delete file on DB column, just logic check.
             // Let's keep it consistent with controller:
             $objetRelation->mandat_file = null; 
        }

        $objetRelation->save();

        // Update Risk Rating
        $this->etablissement->updateRiskRating();

        session()->flash('success', 'Objet et nature de la relation d\'affaire enregistrés avec succès.');

        if ($this->redirect_to === 'dashboard') {
            return redirect()->route('etablissements.show', $this->etablissement->id);
        }

        return redirect()->route('profilrisque.create', ['etablissement_id' => $this->etablissement->id]);
    }

    public function render()
    {
        return view('livewire.create-objet-relation');
    }
}
