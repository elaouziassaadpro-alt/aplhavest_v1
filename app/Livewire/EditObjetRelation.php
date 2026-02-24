<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;

class EditObjetRelation extends Component
{
    use WithFileUploads;

    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $relation_affaire = '';
    public $horizon_placement = '';
    public $objet_relation = [];
    public $mandataire_check = false;
    public $mandataire_input = '';
    public $mandataire_fin_mandat_date = '';
    public $mandat_file;
    public $existing_mandat_file = '';

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::with('ObjetRelation')->findOrFail($etablissement_id);

        $or = $this->etablissement->ObjetRelation;
        if ($or) {
            $this->relation_affaire = $or->relation_affaire ?? '';
            $this->horizon_placement = $or->horizon_placement ?? '';
            $this->mandataire_check = (bool) $or->mandataire_check;
            $this->mandataire_input = $or->mandataire_input ?? '';
            $this->mandataire_fin_mandat_date = $or->mandataire_fin_mandat_date ? \Carbon\Carbon::parse($or->mandataire_fin_mandat_date)->format('Y-m-d') : '';
            $this->existing_mandat_file = $or->mandat_file ?? '';

            if (!empty($or->objet_relation)) {
                if (is_array($or->objet_relation)) {
                    $this->objet_relation = $or->objet_relation;
                } elseif (is_string($or->objet_relation)) {
                    $this->objet_relation = explode(',', $or->objet_relation);
                }
            }
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function update()
    {
        $data = [
            'relation_affaire' => $this->relation_affaire,
            'horizon_placement' => $this->horizon_placement,
            'objet_relation' => implode(',', $this->objet_relation),
            'mandataire_check' => $this->mandataire_check,
            'mandataire_input' => ($this->mandataire_check && !empty($this->mandataire_input)) ? $this->mandataire_input : null,
            'mandataire_fin_mandat_date' => ($this->mandataire_check && !empty($this->mandataire_fin_mandat_date)) ? $this->mandataire_fin_mandat_date : null,
        ];

        if ($this->mandat_file) {
            if ($this->existing_mandat_file) {
                \Storage::disk('public')->delete($this->existing_mandat_file);
            }
            $data['mandat_file'] = $this->mandat_file->store('objet_relation', 'public');
            $this->existing_mandat_file = $data['mandat_file'];
        }

        $this->etablissement->ObjetRelation()->updateOrCreate(
            ['etablissement_id' => $this->etablissement_id],
            $data
        );

        $this->mandat_file = null;
        $this->etablissement->updateRiskRating();

        $this->editing = false;
        session()->flash('message', 'Objet de la relation mis à jour avec succès.');
    }
        

    public function render()
    {
        return view('livewire.edit-objet-relation');
    }
}
