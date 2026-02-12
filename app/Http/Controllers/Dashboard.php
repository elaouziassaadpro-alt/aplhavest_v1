<?php

namespace App\Http\Controllers;

use App\Models\Actionnariat;
use App\Models\Administrateur;
use App\Models\BenificiaireEffectif;
use App\Models\Contact;
use App\Models\Etablissement;
use App\Models\PersonnesHabilites;
use Illuminate\Http\Request;


class Dashboard extends Controller
{
    public function index(){
        $etablissments = Etablissement::all();
        $actionnaires = Actionnariat::all();
        $benificiaireseffectifs = BenificiaireEffectif::all();
        $administrateurs = Administrateur::all();
        $personnesHabilites = PersonnesHabilites::all();
        $contacts = Contact::all();
        return view("dashboard" ,compact('etablissments', 'actionnaires', 'benificiaireseffectifs' ,'administrateurs' ,'personnesHabilites', 'contacts'));
    }
}
